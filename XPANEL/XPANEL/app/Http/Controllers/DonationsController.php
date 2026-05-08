<?php

namespace App\Http\Controllers;

use App\Helpers\FunctionsHelper;
use App\Payments\Classes\MercadoPagoPayment;
use App\Payments\Classes\PaypalPayment;
use Illuminate\Support\Facades\Config;
use App\Models\Char;
use App\Models\GameAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Inertia\Inertia;
use App\Helpers\Logs\LogWriter;
use App\Helpers\Logs\LogDonation;
use App\Helpers\Logs\LogError;
use App\Helpers\Paypal\PaypalMethods;
use App\Models\MercadoPagoProcess;
use App\Models\PaypalProccess;
use App\Services\PaymentContext;

class DonationsController extends Controller
{
    public function index()
    {
        $donations = Config::get('donations');
        $shopType = Config::get('xpanel.donations.shop_type');

        if($shopType === 'points'){
            $donationLogs = PaypalProccess::where('payment_status', 'COMPLETED')->where('master_id', Auth::user()->id)->paginate(5);
            $mpDonationLogs = MercadoPagoProcess::where('payment_status', 'approved')->where('master_id', Auth::user()->id)->paginate(5);
        }

        return Inertia::render('Donations/Index', ['donations' => $donations, 'paypal_donation_logs' => $donationLogs ?? [], 'mp_donation_logs' => $mpDonationLogs ?? []]);
    }

    public function create(Request $request)
    {
        $paymentType = $request->payment_method;
        $paymentMethod = null;

        if ($paymentType == "Paypal") {
            $paymentMethod = new PaypalPayment($request->items, route('donations.success'), route('donations.cancel'));
        } else {
            $paymentMethod = new MercadoPagoPayment($request->items, route('donations.mp.success'), route('donations.cancel'));
        }

        $paymentContext = new PaymentContext($paymentMethod);

        $log = new LogWriter(new LogDonation());
        $response = $paymentContext->processPayment();
        $response = json_decode($response);
        // $log->write('********** NEW DONATION **********');
        // $log->write('Donation Info: ' . json_encode($request->all()));
        // $log->write('Payment ID: ' . $response->id);
        // $log->write('Payment Status: ' . $response->status);
        if ($paymentType == "Paypal") {
            $response = $response->links[1]->href;
        } else {
            $response = $response->init_point;
        }

        return response()->json(['status' => 'success', 'payment_url' => $response], 200);
    }

    public function cancel()
    {
        $log = new LogWriter(new LogDonation());
        $gameAccounts = GameAccount::where('master_acc_id', Auth::user()->id)->with('user')->orderBy('account_id', 'asc')->get();

        if (FacadesRequest::exists('user')) {
            $charAccount = Char::select(['char.char_id', 'char.name'])
                ->join('login', 'login.account_id', '=', 'char.account_id')
                ->where('login.userid', '=', FacadesRequest::get('user'))
                ->get();
        }

        $log->write('********** DONATION CANCELED **********');

        return Inertia::render('Transfer/Index', [
            'donation' => [
                'status' => 'error',
                'message' => 'Donation canceled.',
            ], 'game_accounts' => $gameAccounts,
            'char_account' => (isset($charAccount) ? $charAccount : NULL)
        ]);
    }

    public function success(Request $request)
    {
        $payment = new PaypalMethods();
        $points = Config::get('xpanel.donations.points_per_currency');
        $shopType = Config::get('xpanel.donations.shop_type');
        $gameAccounts = GameAccount::where('master_acc_id', Auth::user()->id)->with('user')->orderBy('account_id', 'asc')->get();
        $log = new LogWriter(new LogDonation());

        if (FacadesRequest::exists('user')) {
            $charAccount = Char::select(['char.char_id', 'char.name'])
                ->join('login', 'login.account_id', '=', 'char.account_id')
                ->where('login.userid', '=', FacadesRequest::get('user'))
                ->get();
        }

        try {
            $response = json_decode($payment->capturePayment($request->token));
            if (isset($response->status) && $response->status === "COMPLETED") {
                $helper = new FunctionsHelper();
                $user = Auth::user();
                $orderInfo = $payment->getOrderInfo($response->id);

                foreach ($orderInfo->purchase_units[0]->items as $item) {
                    for ($i = 0; $i < $item->quantity; $i++) {

                        $bonus = ($shopType === 'packages' ? $helper->getDonationBonus($item->unit_amount->value) : 0);
                        $user->donation_points += ($item->unit_amount->value + $bonus) * $points;
                        $user->save();
                    }
                }

                $bonus = array_reduce($orderInfo->purchase_units[0]->items, function ($carry, $item) use ($helper, $shopType) {
                    $bonus = ($shopType === 'packages' ? $helper->getDonationBonus($item->unit_amount->value) : 0);
                    return $carry + $bonus;
                }, 0);

                $credits = array_reduce($orderInfo->purchase_units[0]->items, function ($carry, $item) {
                    return $carry + $item->unit_amount->value;
                }, 0);

                $data = [
                    'master_id' => $user->id,
                    'server_name' => env('APP_NAME'),
                    'credits' => $credits,
                    'bonus' => $bonus,
                    'items' => json_encode($orderInfo->purchase_units[0]->items[0]),
                    'payment_status' => $orderInfo->status,
                    'pending_reason' => null,
                    'payment_date' => $orderInfo->create_time,
                    'mc_gross' => $orderInfo->purchase_units[0]->amount->value,
                    'mc_fee' => $orderInfo->purchase_units[0]->payments->captures[0]->seller_receivable_breakdown->paypal_fee->value,
                    'mc_currency' => $orderInfo->purchase_units[0]->amount->currency_code,
                    'txn_id' => $orderInfo->id,
                    'txn_type' => 'Web Accept',
                    'first_name' => $orderInfo->payer->name->given_name,
                    'last_name' => $orderInfo->payer->name->surname,
                    'payer_email' => $orderInfo->payer->email_address,
                    'payer_status' => $orderInfo->payment_source->paypal->account_status,

                ];

                $log->writeInTable($data);
                $log->write('********** DONATION COMPLETED **********');
                $log->write('Paypal Info: ' . json_encode($request->all()));
                $log->write('Paypal ID: ' . $response->id);
                $log->write('Paypal Status: ' . $response->status);
                $log->write('Paypal Order Info: ' . json_encode($orderInfo));
                $log->write('Paypal Payer Info: ' . json_encode($response->payment_source->paypal));
                $log->write('Master Account: ' . $user->id);
                $log->write('********** END DONATION **********');

                return Inertia::render('Transfer/Index', [
                    'donation' => [
                        'status' => 'success',
                        'message' => 'Donation completed successfully.',
                    ],  'game_accounts' => $gameAccounts,
                    'char_account' => (isset($charAccount) ? $charAccount : NULL)
                ]);
            } else {
                // Payment failed
                $log->write('********** DONATION FAILED **********');
                $log->write('Paypal Info: ' . json_encode($request->all()));
                $log->write('Paypal Response: ' . json_encode($response));
                $log->write('********** END DONATION **********');

                return Inertia::render('Transfer/Index', [
                    'donation' => [
                        'status' => 'error',
                        'message' => 'Donation failed, contact support.',
                    ],
                    'game_accounts' => $gameAccounts,
                    'char_account' => (isset($charAccount) ? $charAccount : NULL)

                ]);
            }
        } catch (\Exception $e) {
            // Payment failed exception error
            $log->setLogType(new LogError());
            $log->write('********** ERROR DONATION **********');
            $log->write('Error Message: ' . $e->getMessage());
            $log->write('Error File: ' . $e->getFile());
            $log->write('Error Line: ' . $e->getLine());
            $log->write('********** END ERROR DONATION **********');
            return Inertia::render('Transfer/Index', [
                'donation' => [
                    'status' => 'error',
                    'error' => $e->getMessage(),
                    'message' => 'Donation failed, contact support.'
                ],

                'game_accounts' => $gameAccounts,
                'char_account' => (isset($charAccount) ? $charAccount : NULL)
            ]);
        }
    }

    public function successMP(Request $request)
    {
        $gameAccounts = GameAccount::where('master_acc_id', Auth::user()->id)->with('user')->orderBy('account_id', 'asc')->get();
        $log = new LogWriter(new LogDonation());
        $shopType = Config::get('xpanel.donations.shop_type');
        try {
            $payment = MercadoPagoProcess::where('preference_id', $request->preference_id)->first();
            if ($request->status === "approved") {
                $helper = new FunctionsHelper();
                $user = Auth::user();
                $orderInfo = json_decode($payment->items);
                foreach ($orderInfo as $item) {
                    for ($i = 0; $i < $item->quantity; $i++) {
                        $bonus = ($shopType === 'packages' ? $helper->getDonationBonus($item->item->unit_price) : 0);
                        $user->donation_points += (($item->unit_price) + $bonus);
                        $user->save();
                    }
                }
                MercadoPagoProcess::where('preference_id', $request->preference_id)->update(['payment_status' => 'approved']);
                // $log->writeInTable($data);
                $log->write('********** DONATION COMPLETED **********');
                $log->write('MP Info: ' . json_encode($request->all()));
                $log->write('MP ID: ' . $request->collection_id);
                $log->write('MP Status: ' . $request->status);
                $log->write('Master Account: ' . $user->id);
                $log->write('********** END DONATION **********');

                return Inertia::render('Transfer/Index', [
                    'donation' => [
                        'status' => 'success',
                        'message' => 'Donation completed successfully.',
                    ],  'game_accounts' => $gameAccounts,
                    'char_account' => (isset($charAccount) ? $charAccount : NULL)
                ]);
            } else {
                // Payment failed
                $log->write('********** DONATION FAILED **********');
                $log->write('MP Info: ' . json_encode($request->all()));
                $log->write('MP Response: ' . json_encode($request));
                $log->write('********** END DONATION **********');

                return Inertia::render('Transfer/Index', [
                    'donation' => [
                        'status' => 'error',
                        'message' => 'Donation failed, contact support.',
                    ],
                    'game_accounts' => $gameAccounts,
                    'char_account' => (isset($charAccount) ? $charAccount : NULL)

                ]);
            }
        } catch (\Exception $e) {
            // Payment failed exception error
            $log->setLogType(new LogError());
            $log->write('********** ERROR DONATION **********');
            $log->write('Error Message: ' . $e->getMessage());
            $log->write('Error File: ' . $e->getFile());
            $log->write('Error Line: ' . $e->getLine());
            $log->write('********** END ERROR DONATION **********');
            return Inertia::render('Transfer/Index', [
                'donation' => [
                    'status' => 'error',
                    'error' => $e->getMessage(),
                    'message' => 'Donation failed, contact support.'
                ],

                'game_accounts' => $gameAccounts,
                'char_account' => (isset($charAccount) ? $charAccount : NULL)
            ]);
        }
    }
}

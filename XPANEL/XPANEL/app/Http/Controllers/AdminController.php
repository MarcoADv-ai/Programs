<?php

namespace App\Http\Controllers;

use App\Models\GameAccount;
use App\Models\MercadoPagoProcess;
use App\Models\PaypalProccess;
use App\Models\StorageRequest;
use App\Models\User;
use App\Models\WikiCategories;
use App\Models\WikiPost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index(Request $request)
    {

        $query = null;

        if ($request->payment_method === 'MercadoPago') {
            $query = MercadoPagoProcess::select([
                'id',
                'master_id',
                'mc_gross',
                'preference_id as txn_id',
                'payment_status',
                'create_date as payment_date',
            ])->where('payment_status', 'approved');
        } else {
            $query = PaypalProccess::select([
                'id',
                'master_id',
                'mc_gross',
                'txn_id',
                'payment_status',
                'payment_date',
            ]);
        }

        $dona_summary = $query->with('user')
            ->orderBy('payment_date', 'desc')
            ->paginate(6)->setPageName('dona_summary');
        
        $dona_preformance = PaypalProccess::select([
            DB::raw("DATE_FORMAT(payment_date, '%Y') as year"),
            DB::raw("DATE_FORMAT(payment_date, '%b') as month"),
            DB::raw('SUM(mc_gross) as total'),
        ])->groupBy(DB::raw("YEAR(payment_date)"), DB::raw("MONTH(payment_date)"))
            ->orderBy(DB::raw("YEAR(payment_date)"), 'asc')
            ->orderBy(DB::raw("MONTH(payment_date)"), 'asc')
            ->get();



        $gepard_banned = DB::table('gepard_block_log')
            ->select([
                'unique_id',
                'unban_time',
                'violator_name as violator',
                'initiator_name as initiator',
                'reason',
            ])->paginate(6)->setPageName('gepard_banned');

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();


        $separateDB = Config::get('xpanel.use_separate_log_db');
        if($separateDB){
            $successfulLogins = DB::connection('mysql_logs')->table('loginlog')
                ->whereBetween('time', [$startOfWeek, $endOfWeek])
                ->where('log', 'login ok')
                ->get();
        } else {
            $successfulLogins = DB::table('loginlog')
                ->whereBetween('time', [$startOfWeek, $endOfWeek])
                ->where('log', 'login ok')
                ->get();
        }

        $loginsPerDay = $successfulLogins->groupBy(function ($login) {
            return Carbon::parse($login->time)->format('Y-m-d');
        })->map(function ($logins) {
            return $logins->pluck('user')->unique()->count();
        });


        return Inertia::render('Admin/Dashboard', ['logins_per_day' => $loginsPerDay, 'gepard_banned' => $gepard_banned, 'dona_summary' => $dona_summary, 'dona_preformance' => $dona_preformance]);
    }

    public function donations(Request $request)
    {
        $querySearch = $request->input("q", "");
        $donations = PaypalProccess::with('user')
            ->filter($querySearch)
            ->orderBy('payment_date', 'desc')
            ->paginate(10);

        $most_donated = PaypalProccess::select(['mc_gross', DB::raw('SUM(mc_gross) as total'), DB::raw('COUNT(mc_gross) as count')])->groupBy('mc_gross')->orderBy('mc_gross', 'desc')->get();


        $rank_doners = PaypalProccess::select([
            'master_id',
            DB::raw('SUM(mc_gross) as total_donated'),
            DB::raw('COUNT(mc_gross) as count_donations'),
        ])->with('user')
            ->groupBy('master_id')
            ->orderBy('total_donated', 'desc')
            ->paginate(6);

        return Inertia::render('Admin/Donations/Index', ['rank_doners' => $rank_doners, 'donations' => $donations, 'most_donated' => $most_donated]);
    }

    public function payouts()
    {
        // $payouts = DB::table('cp_payouts')->select(
        //     [
        //         'cp_payouts.id',
        //         'cp_payouts.master_id',
        //         'cp_users.email',
        //         'cp_payouts.receiver',
        //         'cp_payouts.amount',
        //         'cp_payouts.request_date',
        //         'cp_payouts.payout_date',
        //         'cp_payouts.status'
        //     ]
        // )->join('cp_users', 'cp_users.id', '=', 'cp_payouts.master_id')
        //     ->orderBy('cp_payouts.request_date', 'desc')->get();

        // return Inertia::render('Admin/Payouts/Index', ['payouts' => $payouts]);
    }

    public function game_accounts(Request $request)
    {
        $querySearch = $request->input("q", "");
        $gameAccounts = GameAccount::with('user')->filter($querySearch)->orderBy('account_id', 'desc')->paginate(10);
        $gepard_banned = DB::table('gepard_block')
            ->select([
                'unique_id',
                'unban_time',
                'reason',
            ])->paginate(6);
        return Inertia::render('Admin/GameAccounts/Index', ['game_accounts' => $gameAccounts, 'gepard_banned' => $gepard_banned]);
    }

    public function game_account_show($account_id)
    {
        $gameAccount = GameAccount::with('user')->find($account_id);
        if (!$gameAccount)
            return response()->json(['error' => 'Game account not found'], 404);

        return response()->json(['game_account' => $gameAccount], 200);
    }

    public function game_account_destroy($account_id)
    {
        $gameAccount = GameAccount::find($account_id);
        if (!$gameAccount)
            return response()->json(['error' => 'Game account not found'], 404);

        $gameAccount->delete();

        return response()->json(['success' => 'Game account deleted'], 200);
    }

    public function master_accounts(Request $request)
    {
        $querySearch = $request->input("q", "");
        $master_accounts = User::filter($querySearch)->paginate(10);
        return Inertia::render('Admin/MasterAccounts/Index', ['master_accounts' => $master_accounts]);
    }

    public function master_account_show($id)
    {

        $master_account = User::find($id);

        if (!$master_account)
            return response()->json(['error' => 'Master account not found'], 404);

        return response()->json(['master_account' => $master_account], 200);
    }

    public function master_account_destroy($id)
    {
        $master_account = User::find($id);
        if (!$master_account)
            return response()->json(['error' => 'Master account not found'], 404);

        $master_account->delete();

        return response()->json(['success' => 'Master account deleted'], 200);
    }

    public function master_account_update(Request $request)
    {
        $master_account = User::find($request->id);

        if (!$master_account)
            return response()->json(['error' => 'Master account not found'], 404);

        $master_account->id = $request->id;
        $master_account->email = $request->email;
        $master_account->country = $request->country;
        $master_account->donation_points = $request->donation_points;
        $master_account->vote_points = $request->vote_points;
        $master_account->role = $request->role;
        $master_account->status = $request->status;
        $master_account->save();

        return response()->json(['success' => 'Master account updated'], 200);
    }

    public function admin_wiki()
    {
        $wiki_posts = WikiPost::notArchived()->with(['category'])->paginate(10);
        $categories = WikiCategories::paginate(3);
        $archive_posts = WikiPost::archive()->with(['category'])->paginate(3);
        return Inertia::render('Admin/Wiki/Index', ['wiki_posts' => $wiki_posts, 'categories' => $categories, 'archive_posts' => $archive_posts]);
    }

    public function donation_show($id)
    {
        $donation = PaypalProccess::select(['x_donations_log.id as xid', 'x_donations_log.*', 'users.email'])->join('users', 'users.id', '=', 'x_donations_log.master_id')
            ->where('x_donations_log.id', $id)
            ->orderBy('payment_date', 'asc')->first();

        if (!$donation)
            return response()->json(['error' => 'Donation not found'], 404);

        $data = [
            'donation' => $donation,
        ];

        return response()->json($data, 200);
    }

    public function donation_destroy()
    {
        $donation = PaypalProccess::find(request()->id);

        if (!$donation)
            return response()->json(['error' => 'Donation not found'], 404);

        $donation->delete();

        return response()->json(['message' => 'Donation deleted'], 200);
    }

    public function donation_update()
    {
        $donation = PaypalProccess::find(request()->id);

        if (!$donation)
            return response()->json(['error' => 'Donation not found'], 404);

        $donation->credits = request()->credits;
        $donation->payer_email = request()->payer_email;
        $donation->receiver_email = request()->receiver_email;
        $donation->item_name = request()->item_name;
        $donation->quantity = request()->quantity;
        $donation->payment_status = request()->payment_status;
        $donation->payment_date = request()->payment_date;
        $donation->mc_gross = request()->mc_gross;
        $donation->txn_id = request()->txn_id;
        $donation->first_name = request()->first_name;
        $donation->last_name = request()->last_name;
        $donation->save();

        return response()->json(['message' => 'Donation updated'], 200);
    }

    public function storage()
    {
        $items_requested = StorageRequest::select([
            'storage_request.id as user_id',
            'storage_request.*',
            'item_db.slots',
            'item_db.name_english',
        ])->with('user')->join('item_db', 'storage_request.item_id', '=', 'item_db.id')->orderBy('master_id', 'ASC')->paginate(25);
        return Inertia::render('Admin/Storage/Recovery', ['items_requested' => $items_requested]);
    }
}

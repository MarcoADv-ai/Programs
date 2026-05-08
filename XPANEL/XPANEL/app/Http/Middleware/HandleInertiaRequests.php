<?php

namespace App\Http\Middleware;

use App\Models\Char;
use App\Models\MercadoPagoProcess;
use App\Models\PaypalProccess;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config; 
use Illuminate\Support\Facades\DB;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {

        return array_merge(parent::share($request), [
            //
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                ];
            },
            'xpanel' => function () use ($request) {
                $xpanel = Config::get('xpanel');
                return $xpanel;
            },
            'online' => function () use ($request) {
                $online_users = Char::where('online', '=', 1)->count();
                $online_users = ceil($online_users * 1.1);
                if (Cache::has('online_peak')) {
                    $online_peak = Cache::get('online_peak');
        
                    if ($online_users > $online_peak) {
                        Cache::put('online_peak', $online_users);
                    }
                } else {
                    $online_peak = $online_users;
                    Cache::forever('online_peak', $online_users);
                }

                return [
                    'users' => (int)$online_users * round(1.1,0),
                    'peak' => $online_peak,
                ];
            },
            'admin' => function () use ($request) {
                if(Auth::check() && Auth::user()->isAdmin()){
                    $newUsers = User::where('created_at', '>=', now()->subMonth(1))->count();
                    $totalUsers = User::count();
                    $totalDonationsPaypal = PaypalProccess::where('payment_status', 'Completed')->sum('mc_gross');
                    $totalDonationsMP = MercadoPagoProcess::where('payment_status', 'approved')->sum('mc_gross');
                    $totalDonations = $totalDonationsPaypal + $totalDonationsMP;
                    $donationsOfMonth = PaypalProccess::where('payment_status', 'Completed')->whereMonth('payment_date', now()->month)->sum('mc_gross');
                    
                    $data = [
                        'users' => [
                            'news' => $newUsers,
                            'total' => $totalUsers,
                        ],
                        'totalDonations' => $totalDonations,
                        'donationsOfMonth' => $donationsOfMonth,
                    ];
                }

                return $data ?? null;
            },
            'widgetbot'=> function () use ($request) {
                $data = [
                    'server' => env('WIDGETBOT_ID_SERVER'),
                    'channel' => env('WIDGETBOT_ID_CHANNEL'),
                ];


                return $data ?? null;
            },
        ]);
    }
}

use Inertia\Inertia;

// Synchronously
Inertia::share('appName', config('app.name'));

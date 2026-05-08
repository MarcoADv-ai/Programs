<?php

namespace App\Http\Controllers;

use App\Models\GameAccount;
use App\Models\MasterAccountLogs;
use App\Models\PaypalProccess;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class MasterAccountController extends Controller
{
    /**
     *   Fetching all the game accounts that belong to the logged in user.
     * 
     *  @return \Illuminate\Http\Response 
     * */
    public function index()
    {
        if(Auth::user()->api_token == null)
            User::where('id', Auth::user()->id)->update(['api_token' => Str::random(80)]);
        // Main
        $gameAccounts = GameAccount::where('master_acc_id', Auth::user()->id)->with('user')->orderBy('account_id', 'desc')->paginate(2);
        $charAccounts = DB::table('char')->join('login', 'char.account_id', '=', 'login.account_id')->where('login.master_acc_id', Auth::user()->id)->select('char.*')->get();
       
        // Logs
        $paypalLogs = PaypalProccess::where('master_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(10);
        $transferLogs = DB::table('x_transfer_log')->where('master_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(10);
        $vote4pointsLogs = DB::table('x_vote4points_log')->where('master_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(10);
        $masterAccountLogs = MasterAccountLogs::where('master_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(10);
        $gameAccountsIDs = $gameAccounts->pluck('account_id')->toArray();
        $bansLog = DB::table('gepard_block_log')->whereIn('violator_account_id', $gameAccountsIDs)->orderBy('id', 'desc')->paginate(10);


        return Inertia::render('MasterAccount/Index', ['game_accounts' => $gameAccounts, 'char_accounts' => $charAccounts, 'paypal_logs' => $paypalLogs, 'transfer_logs' => $transferLogs, 'vote4points_logs' => $vote4pointsLogs, 'master_account_logs' => $masterAccountLogs, 'ban_logs' => $bansLog]);
    }
}

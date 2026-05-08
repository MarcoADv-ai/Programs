<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Inertia\Inertia;
use App\Helpers\FunctionsHelper;
use App\Helpers\Logs\LogVote4Points;
use App\Helpers\Logs\LogWriter;
use App\Models\User;
use App\Models\Vote4Points;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

class Vote4PointsController extends Controller
{
    public function index()
    {
        $votePoints = Auth::user()->vote_points;
        $delayExpire = Vote4Points::select(['delay_expire', 'rank_id'])
            ->where('master_id', '=', Auth::user()->id)
            ->where('delay_expire', '>', Carbon::now()->toDateTimeString())
            ->get();

        return Inertia::render('Vote4Points/Index', [
            'rankings_info' => Config::get('vote4points.vote4points.rankings'),
            'vote_points' => ($votePoints ?? 0),
            'vote_delay' => $delayExpire
        ]);
    }

    public function store(Request $request)
    {
        $log = new LogWriter(new LogVote4Points());
        $funcHelper = new FunctionsHelper();
        $ip = $funcHelper->getRealIP();
        $date = Carbon::now();
        $today = $date->toDateTimeString();
        $delayExpire = $date->addDays(1)->toDateTimeString();
        $rank_id = $request->input('ranking_id');
        $points = $request->input('ranking_points');

        $verify = Vote4Points::select(['delay_expire'])
            ->where('ip', '=', $ip,)
            ->where('rank_id', '=', $rank_id)
            ->where('delay_expire', '>', $today)
            ->first();

        if (isset($verify)) {
            return response()->json(['error' => 'You have already voted for this server!'], 422);
        } else {
            Vote4Points::create([
                'master_acc_id' => Auth::user()->id,
                'rank_id' => $rank_id,
                'ip' => $ip,
                'delay_expire' => $delayExpire,
            ]);
            $data = [
                'master_id' => Auth::user()->id,
                'rank_id' => $rank_id,
                'points' => $points,
                'ip' => $ip,
                'date' => $today,
            ];
            $log->writeInTable($data);
            User::where('id', Auth::user()->id)->increment('vote_points', $points);

            return response()->json(['success' => 'You have successfully voted for the server!'], 200);
        }
    }
}

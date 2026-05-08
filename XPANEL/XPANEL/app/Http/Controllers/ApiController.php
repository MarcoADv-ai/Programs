<?php

namespace App\Http\Controllers;

use App\Models\PickLog;
use App\Models\WoeRank;


class ApiController extends Controller
{

    public function plist()
    {
        $url = asset('/patch/plist.txt');
        return redirect($url);
    }

    public function data()
    {
        $file = request()->route('file');
        $url = asset('/patch/data/' . $file);
        return redirect($url);
    }

    public function patcher()
    {
        $url = asset('/patch/index.html');
        return redirect($url);
    }

    public function vendingHistory($nameid)
    {

        return;
    }
}

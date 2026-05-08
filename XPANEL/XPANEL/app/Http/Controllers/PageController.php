<?php

namespace App\Http\Controllers;

use App\Helpers\Logs\LogVote4Points;
use App\Models\DataBase;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function index()
    {
        return Inertia::render('Home/Index');
    }

    public function pre_register()
    {
        $total_accounts = User::count();
        return Inertia::render('PreRegister/Index',['total_accounts' => $total_accounts]);
    }

    public function information(Request $request)
    {   
        $item_name = $request->input('item_name');
        $items = DataBase::select(['id', 'name_english','refineable','slots', 'type'])->filter($item_name)->paginate(30);
        return Inertia::render('Home/Information', ['auth_user' => auth()->user(), 'items' => $items]);
    }

    public function register()
    {
        return Inertia::render('MasterAccount/Create');
    }

    public function view($page_id)
    {
        // Making log for the view
        $log = new LogVote4Points();
        $output = $log->makeConfiguration($page_id);
        return redirect()->route('vote4points')->with('success', $output);
    }

    public function downloads()
    {
        return Inertia::render('Home/Downloads', ['auth_user' => auth()->user()]);
    }

}

<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CharController;
use App\Http\Controllers\ChargenController;
use App\Http\Controllers\DonationsController;
use App\Http\Controllers\GameAccountController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\ItemDBController;
use App\Http\Controllers\MasterAccountController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RankingsController;
use App\Http\Controllers\StorageRequestController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\Vote4PointsController;
use App\Http\Controllers\WikiController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Home
Route::get('/', [PageController::class, 'index'])
    ->name('home');

// View Page Controller 
Route::get('page/{page_id}', [PageController::class, 'view'])
    ->name('page.view');

// ROCHARGEN
Route::get('/rochar/character-head/{char_id}', [ChargenController::class, 'character_head'])
    ->name('character-head');

Route::get('/rochar/character/{char_id}', [ChargenController::class, 'character'])
    ->name('character');

// Rankings
Route::get('rankings/battleground', [RankingsController::class, 'battleground'])
    ->name('rank.bg');

Route::get('rankings/cashpoints', [RankingsController::class, 'cashPoints'])
    ->name('rank.cashpoints');

Route::get('rankings/zeny', [RankingsController::class, 'zeny'])
    ->name('rank.zeny');

Route::get('rankings/experience', [RankingsController::class, 'experience'])
    ->name('rank.experience');

Route::get('rankings/forge', [RankingsController::class, 'forge'])
    ->name('rank.forge');

Route::get('rankings/homunculus', [RankingsController::class, 'homunculus'])
    ->name('rank.homunculus');

Route::get('rankings/mvps', [RankingsController::class, 'mvps'])
    ->name('rank.mvps');

Route::get('rankings/playtime', [RankingsController::class, 'playtime'])
    ->name('rank.playtime');

Route::get('rankings/potions', [RankingsController::class, 'potions'])
    ->name('rank.potions');

Route::get('rankings/pvp', [RankingsController::class, 'pvp'])
    ->name('rank.pvp');

Route::get('rankings/woe', [RankingsController::class, 'woe'])
    ->name('rank.woe');

Route::get('rankings/woe-damage', [RankingsController::class, 'woe_damage'])
    ->name('rank.woe-damage');

Route::get('rankings/deaths', [RankingsController::class, 'deaths'])
    ->name('rank.deaths');

Route::get('rankings/leveling', [RankingsController::class, 'leveling'])
    ->name('rank.leveling');

Route::get('/downloads', [PageController::class, 'downloads'])
    ->name('downloads');

Route::get('rankings/bg-profile/{char_id}', [UserProfileController::class, 'bg_profile'])
    ->name('bg.profile');

Route::get('rankings/woe-profile/{char_id}', [UserProfileController::class, 'woe_profile'])
    ->name('woe.profile');


Route::get('information/general', [InformationController::class, 'general'])
    ->name('information.general');

Route::get('information/mvp-cards', [InformationController::class, 'mvp_cards'])
    ->name('information.mvp_cards');

Route::get('information/whosell', [InformationController::class, 'whosell'])
    ->name('information.whosell');

// Vote 4 Points
Route::get('master-account/vote4points', [Vote4PointsController::class, 'index'])
    ->middleware(['auth:sanctum'])
    ->name('vote4points');

Route::post('master-account/vote4points/store', [Vote4PointsController::class, 'store'])
    ->name('vote4points.store');

// Master Account
Route::get('master-account', [MasterAccountController::class, 'index'])
    ->name('master-account')
    ->middleware(['auth:sanctum', 'verified']);

// Transfer
Route::get('transfer', [TransferController::class, 'index'])
    ->name('transfer.index')
    ->middleware(['auth:sanctum', 'verified']);

Route::post('transfer/{account_id}', [TransferController::class, 'store'])
    ->name('transfer.store')
    ->middleware(['auth:sanctum', 'verified']);

// Game Account
Route::post('game-accounts/update/{account_id}', [GameAccountController::class, 'update'])
    ->name('game-accounts.update')->middleware(['auth:sanctum']);

Route::delete('game-accounts/destroy/{account_id}', [GameAccountController::class, 'destroy'])
    ->name('game-accounts.destroy')->middleware(['auth:sanctum']);

Route::post('game-accounts/create', [GameAccountController::class, 'store'])
    ->name('game-accounts.store')->middleware(['auth:sanctum']);

// Donations

$shopType = Config::get('xpanel.donations.shop_type');

if ($shopType === 'points') {
    Route::get('donations', [DonationsController::class, 'index'])
        ->name('donations.index')->middleware(['auth:sanctum']);
} else {
    Route::get('donations', [DonationsController::class, 'index'])
        ->name('donations.index');
}

Route::post('donations/create', [DonationsController::class, 'create'])
    ->name('donations.show');

Route::get('donations/success', [DonationsController::class, 'success'])
    ->name('donations.success');

Route::get('donations/mp/success', [DonationsController::class, 'successMP'])
    ->name('donations.mp.success');

Route::get('donations/cancel', [DonationsController::class, 'cancel'])
    ->name('donations.cancel');

// Game Characters
Route::post('game-accounts/reset/{char_id}', [CharController::class, 'update'])
    ->name('game-accounts.update')->middleware(['auth:sanctum']);

// Item DB
Route::get('information/itemdb', [ItemDBController::class, 'index'])
    ->name('information.item_db');

// Admin 
Route::middleware(['auth:sanctum', 'verified', 'admin'])->group(function () {
    Route::get('admin-panel', [AdminController::class, 'index'])
        ->name('admin.dashboard');

    // Admin Donations
    Route::get('admin-panel/donations', [AdminController::class, 'donations'])
        ->name('admin.donations');

    Route::get('admin-panel/donations/{id}/show', [AdminController::class, 'donation_show'])
        ->name('admin.donations.show');

    Route::delete('admin-panel/donations/{id}/destroy', [AdminController::class, 'donation_destroy'])
        ->name('admin.donations.destroy');

    Route::put('admin-panel/donations/{id}/update', [AdminController::class, 'donation_update'])
        ->name('admin.donations.update');

    // Admin Game Accounts
    Route::get('admin-panel/game-accounts', [AdminController::class, 'game_accounts'])
        ->name('admin.game_accounts');

    Route::get('admin-panel/game-accounts/{id}/show', [AdminController::class, 'game_account_show'])
        ->name('admin.game_accounts.show');

    Route::put('admin-panel/game-accounts/{id}/update', [AdminController::class, 'game_account_update'])
        ->name('admin.game_accounts.update');

    Route::delete('admin-panel/game-accounts/{account_id}/destroy', [AdminController::class, 'game_account_destroy'])
        ->name('admin.game_accounts.destroy');

    // Admin Master Accounts
    Route::get('admin-panel/master-accounts', [AdminController::class, 'master_accounts'])
        ->name('admin.master_accounts');

    Route::get('admin-panel/master-accounts/{id}/show', [AdminController::class, 'master_account_show'])
        ->name('admin.master_accounts.show');

    Route::delete('admin-panel/master-accounts/{id}/destroy', [AdminController::class, 'master_account_destroy'])
        ->name('admin.master_accounts.destroy');

    Route::put('admin-panel/master-accounts/{id}/update', [AdminController::class, 'master_account_update'])
        ->name('admin.master_accounts.update');

    // Route::get('admin-panel/payouts', [AdminController::class, 'payouts'])
    //     ->name('admin.payouts');


    // Admin Wiki
    Route::get('admin-panel/wiki', [AdminController::class, 'admin_wiki'])
        ->name('admin.wiki.index');

    Route::get('admin-panel/wiki/{id}/edit', [WikiController::class, 'edit'])
        ->name('admin.wiki.edit');

    Route::put('admin-panel/wiki/{id}/update', [WikiController::class, 'update'])
        ->name('admin.wiki.update');

    Route::put('admin-panel/wiki/{id}/archive', [WikiController::class, 'toggleArchive'])
        ->name('admin.wiki.archive');

    Route::delete('admin-panel/wiki/{id}/destroy', [WikiController::class, 'destroy'])
        ->name('admin.wiki.destroy');

    Route::get('admin-panel/wiki/create', [WikiController::class, 'create'])
        ->name('admin.wiki.create');

    Route::post('admin-panel/wiki/store', [WikiController::class, 'store'])
        ->name('admin.wiki.store');

    Route::get('admin-panel/wiki/category/{id}/show', [WikiController::class, 'show_category'])
        ->name('admin.wiki.category.show');

    Route::post('admin-panel/wiki/category/store', [WikiController::class, 'store_category'])
        ->name('admin.wiki.category.store');

    Route::delete('admin-panel/wiki/category/{id}/destroy', [WikiController::class, 'destroy_category'])
        ->name('admin.wiki.category.destroy');

    Route::put('admin-panel/wiki/category/{id}/update', [WikiController::class, 'update_category'])
        ->name('admin.wiki.category.update');

    Route::get('admin-panel/storage', [AdminController::class, 'storage'])
        ->name('admin.storage.index');
});

// Wiki
Route::get('wiki', [WikiController::class, 'index'])->name('wiki.index');
Route::get('wiki/{category}/{page}', [WikiController::class, 'show'])->name('wiki.show');

// Storage Request


Route::post('storage-request/store', [StorageRequestController::class, 'store'])
    ->name('storage-request.store');

Route::post('storage-request/accept', [StorageRequestController::class, 'accept_request'])
    ->name('storage-request.accept');

Route::delete('storage-request/destroy/{id}', [StorageRequestController::class, 'destroy'])
    ->name('storage-request.destroy');

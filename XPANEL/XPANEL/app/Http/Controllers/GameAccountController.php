<?php

namespace App\Http\Controllers;

use App\Helpers\Logs\LogMasterAccount;
use App\Helpers\Logs\LogWriter;
use App\Models\GameAccount;
use App\Http\Requests\GameAccountRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Redirect;
use Laravel\Fortify\Actions\ConfirmPassword;

class GameAccountController extends Controller
{

    /**
     * Delete the game account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, StatefulGuard $guard, $account_id)
    {
        $log = new LogWriter(new LogMasterAccount());
        $confirmed = app(ConfirmPassword::class)(
            $guard,
            $request->user(),
            $request->password
        );

        $gameAccount = GameAccount::findOrFail($account_id);

        if ($confirmed && $gameAccount) {
            $gameAccount->delete();
            $data = [
                'master_id' => Auth::user()->id,
                'action' => 'Account ' . $gameAccount->userid . ' was deleted',
                'date' => date('Y-m-d H:i:s'),
                'ip' => $request->ip(),
            ];

            return Redirect::back()->with('success', 'Account ' . $account_id . ' deleted successfully');
        } else {

            throw ValidationException::withMessages([
                'password' => __('The password is incorrect.'),
            ]);
            return Redirect::back()->with('error', 'Account ' . $account_id . ' could not be deleted');
        }
    }

    /**
     * Store a new Game Account.
     *
     * @param  \App\Http\Requests\RequestGameAccount  $request
     * @return Illuminate\Http\Response
     */
    public function store(GameAccountRequest $request)
    {
        $validated = $request->validated();
        $log = new LogWriter(new LogMasterAccount());
        $xpanel = Config::get('xpanel');
        if ($validated) {
            if (Hash::check($validated['masterAccountPassword'], Auth::user()->password)) {
                GameAccount::create([
                    'master_acc_id' => Auth::user()->id,
                    'userid'        => $validated['userid'],
                    'user_pass'      => ($xpanel['useMD5'] ? md5($validated['password']) : $validated['password']),
                    'email'         => Auth::user()->email,
                ]);
                $data = [
                    'master_id' => Auth::user()->id,
                    'action' => 'Account ' . $validated['userid'] . ' was created',
                    'date' => date('Y-m-d H:i:s'),
                    'ip' => $request->ip(),
                ];
                $log->writeInTable($data);
                return Redirect::back()->with('success', 'Account created successfully');
            } else {
                throw ValidationException::withMessages([
                    'masterAccountPassword' => __('The master account password is incorrect.'),
                ]);
            }
        } else {
            return Redirect::back()->with('error', 'Account could not be created');
        }
    }


    /**
     * Update password to Game Account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @param int  $account_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StatefulGuard $guard, $account_id)
    {
        $log = new LogWriter(new LogMasterAccount());
        $xpanel = Config::get('xpanel');
        // Validate Inputs
        $validated = $request->validate([
            'password'                      =>      'required|confirmed|min:5',
            'masterAccountPassword'         =>      'required|min:5',
        ]);

        // Check if master account password is correct
        $confirmed = app(ConfirmPassword::class)(
            $guard,
            $request->user(),
            $request->masterAccountPassword
        );

        // Verify if game account exists else return error
        $gameAccount = GameAccount::findOrFail($account_id);
        if ($gameAccount && $validated) {
            // dd($request->current_password, $gameAccount->user_pass);
            if ($confirmed) {
                $gameAccount->update([
                    'user_pass' => ($xpanel['useMD5'] ? md5($validated['password']) : $validated['password']),
                ]);
                $data = [
                    'master_id' => Auth::user()->id,
                    'action' => 'Account ' . $gameAccount->userid . ' password was changed',
                    'date' => date('Y-m-d H:i:s'),
                    'ip' => $request->ip(),
                ];
                $log->writeInTable($data);
                return Redirect::back()->with('success', 'Password ' . $account_id . ' updated successfully');
            } else {
                $data = [
                    'master_id' => Auth::user()->id,
                    'action' => 'Account ' . $gameAccount->userid . ' password could not be changed',
                    'date' => date('Y-m-d H:i:s'),
                    'ip' => $request->ip(),
                ];
                $log->writeInTable($data);
                throw ValidationException::withMessages([
                    'masterAccountPassword' => __('The master account password is incorrect.'),
                ]);
                return Redirect::back()->with('error', 'Password ' . $account_id . ' could not be changed');
            }
        } else {
            return Redirect::back()->with('error', 'Account could not be updated');
        }
    }
}

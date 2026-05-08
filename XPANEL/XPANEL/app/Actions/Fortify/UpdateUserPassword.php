<?php

namespace App\Actions\Fortify;

use App\Helpers\Logs\LogMasterAccount;
use App\Helpers\Logs\LogWriter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        $log = new LogWriter(new LogMasterAccount());
        $data = [
            'master_id' => $user->id,
            'action' => 'Master Account password was changed',
            'date' => date('Y-m-d H:i:s'),
            'ip' => request()->ip(),
        ];
        
        Validator::make($input, [
            'current_password' => ['required', 'string'],
            'password' => $this->passwordRules(),
        ])->after(function ($validator) use ($user, $input) {
            if (!isset($input['current_password']) || !Hash::check($input['current_password'], $user->password)) {
                $validator->errors()->add('current_password', __('The provided password does not match your current password.'));
            }
        })->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
        $log->writeInTable($data);
    }
}

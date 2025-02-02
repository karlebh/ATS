<?php

namespace App\Actions\Auth;

use App\Mail\SendResetPasswordLink;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordAction
{
    use ResponseTrait;

    public function execute(array $requestData)
    {
        $user = User::where('email', $requestData['email'])->first();

        if (! $user) {
            return $this->badRequestResponse('User not found');
        }

        $user->forceFill([
            'password' => Hash::make($requestData['password']),
            'remember_token' => Str::random(60),
        ])->save();

        DB::table('password_reset_tokens')->where('token', $requestData['token'])->delete();

        event(new PasswordReset($user));

        return $this->successResponse('Password updated succesfully');
    }
}

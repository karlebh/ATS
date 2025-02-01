<?php

namespace App\Actions\Auth;

use App\Mail\SendResetPasswordLink;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class SendForgotPasswordLinkAction
{
    use ResponseTrait;

    public function execute(array $requestData)
    {
        $user = User::where('email', $requestData['data'])
            ->orWhere('username', $requestData['data'])
            ->first();

        if (! $user) {
            return $this->errorResponse('This user does not exist.');
        }

        $otp = $this->generateOTP();

        $status = Password::sendResetLink(
            $requestData['email']
        );

        return $status === Password::ResetLinkSent
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);

        // Mail::to($user)->send(new SendResetPasswordLink($user, $otp));
    }

    public function generateOTP()
    {
        return str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}

<?php

namespace App\Actions\Auth;

use App\Mail\SendResetPasswordOTP;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SendOTPAction
{
    use ResponseTrait;

    public function execute($requestData)
    {
        $user = User::where('email', $requestData['data'])
            ->orWhere('username', $requestData['data'])
            ->first();

        if (! $user) {
            return $this->badRequestResponse('This user does not exist.');
        }

        $otp = $this->generateOTP();

        try {
            Mail::to($user)->send(new SendResetPasswordOTP($user, $otp));
        } catch (\Exception $e) {
            return $this->badRequestResponse('Failed to send password reset link.');
        }

        $user->otp = Hash::make($otp);
        $user->otp_created_at = now();
        $user->save();

        return $this->successResponse('OTP succesfully sent to user');
    }


    public function generateOTP()
    {
        return str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}

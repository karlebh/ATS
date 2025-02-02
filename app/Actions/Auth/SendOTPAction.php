<?php

namespace App\Actions\Auth;

use App\Mail\SendResetPasswordOTP;
use App\Models\User;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendOTPAction
{
    use ResponseTrait;

    public function execute($requestData)
    {
        $delayInMinutes = 10;

        $user = User::where('email', $requestData['data'])
            ->orWhere('username', $requestData['data'])
            ->first();

        if (! $user) {
            return $this->badRequestResponse('This user does not exist.');
        }

        //can not request for a new otp before the previous expires
        if ($user->otp_created_at && now()->lessThan(Carbon::parse($user->otp_created_at)->addMinutes($delayInMinutes))) {
            return $this->badRequestResponse("Wait {$delayInMinutes} minutes before making another request.");
        }

        $otp = $this->generateOTP();

        try {
            Mail::to($user)->send(new SendResetPasswordOTP($user, $otp));
        } catch (\Exception $e) {
            return $this->badRequestResponse('Failed to send password reset link.');
        }

        $token = Str::random(64);

        $user->otp = Hash::make($otp);
        $user->otp_created_at = now();
        $user->otp_token = $token;

        $user->save();

        return $this->successResponse('OTP succesfully sent to user', [
            'token' => $token,
            'url_param' => "?token={$token}&email={$user->email}",
            'user' => $user
        ]);
    }


    public function generateOTP()
    {
        return str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}

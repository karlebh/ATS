<?php

namespace App\Actions\Auth;

use App\Mail\SendResetPasswordOTP;
use App\Models\User;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendOTPAction
{
    use ResponseTrait;

    public function execute($requestData)
    {
        $delayInMinutes = config('ats.otp_delay_time');

        $user = User::where('email', $requestData['data'])
            ->orWhere('username', $requestData['data'])
            ->first();

        if (! $user) {
            return $this->badRequestResponse('This user does not exist.');
        }

        //delay otp creating for 1 minute
        if ($user->otp_created_at && now()->lessThan(Carbon::parse($user->otp_created_at)->addMinutes($delayInMinutes))) {

            $timeLeftInSeconds = now()->diffInSeconds(Carbon::parse($user->otp_created_at)->addMinutes($delayInMinutes));
            $minutesLeft = floor($timeLeftInSeconds / 60);
            $secondsLeft = $timeLeftInSeconds % 60;

            return $this->badRequestResponse(
                "Wait {$delayInMinutes} minutes before making another OTP request. Time left: {$minutesLeft} minutes and {$secondsLeft} seconds."
            );
        }


        $otp = $this->generateOTP();

        $token = Str::random(64);

        $user->otp = Hash::make($otp);
        $user->otp_created_at = now();
        $user->otp_token = $token;

        $user->save();

        $urlParam = "?token={$token}&email={$user->email}";

        try {
            Mail::to($user)->send(new SendResetPasswordOTP($user, $otp, $urlParam));
        } catch (\Exception $e) {
            return $this->badRequestResponse('Failed to send password reset link via message.', exception: $e);
        }

        return $this->successResponse('OTP succesfully sent to user. add the url_param to the authenticate-otp route', [
            'token' => $token,
            'otp' => $otp,
            'url_param' => $urlParam,
            'user' => $user->fresh(),
        ]);
    }


    public function generateOTP()
    {
        return str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}

<?php

namespace App\Actions\Auth;

use App\Mail\SendResetPasswordLink;
use App\Models\User;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthenticateOTPAction
{
    use ResponseTrait;

    public function execute(array $requestData)
    {
        $email = request('email');
        $token = request('token');
        $OTPExpirationTime = config('ats.otp_expiration_time');

        if (! $email || ! $token) {
            return $this->badRequestResponse('You need a valid url to authenticate token');
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            return $this->badRequestResponse("Invalid url. Url params needs a valid email and token. Please add the url_param generated while requesting for otp in th \fogot-password endpoint to the url.");
        }

        if ($token != $user->otp_token) {
            return $this->badRequestResponse('Invalid url token');
        }

        if (! $user->otp) {
            return $this->badRequestResponse('kindly reqest for another otp', 403);
        }

        if (Carbon::parse($user->otp_created_at)->diffInMinutes(now()) > $OTPExpirationTime) {
            $user->otp = null;
            $user->otp_token = null;
            $user->otp_created_at = null;
            $user->save();
            return $this->badRequestResponse('OTP has expired', 400);
        }

        if (! Hash::check($requestData['otp'], $user->otp)) {
            return $this->badRequestResponse(message: 'Invalid or expired OTP', code: 422);
        }

        $token = Str::random(64);

        $forgotPasswordToken = DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->first();

        if ($forgotPasswordToken) {
            DB::table('password_reset_tokens')
                ->where('email', $user->email)
                ->delete();
        }

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        $param = "?token={$token}";
        $url = config('app.url') . $param;

        try {
            Mail::to($user)->send(new SendResetPasswordLink($user, $url));
        } catch (\Exception $e) {
            return $this->badRequestResponse('Failed to send password reset link.', exception: $e);
        }

        $user->otp = null;
        $user->otp_token = null;
        $user->otp_created_at = null;
        $user->save();

        return $this->successResponse('OTP authenticated succesfully. Add the url_param to the reset-password route', [
            'user' => $user->fresh(),
            'url_param' => $param,
        ]);
    }
}

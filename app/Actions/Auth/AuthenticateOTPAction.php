<?php

namespace App\Actions\Auth;

use App\Mail\SendResetPasswordLink;
use App\Models\User;
use App\Traits\ResponseTrait;
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
        $email = filter_var(request('email'), FILTER_SANITIZE_EMAIL);

        if (! $email) {
            return $this->badRequestResponse('The url needs a valid email');
        }

        $user = User::where('email', $email)->first();

        if ($user->otp_created_at->diffInMinutes(now()) > 10) {
            return $this->badRequestResponse('OTP has expired', 400);
        }

        if (! Hash::check($requestData['otp'], $user->otp)) {
            return $this->badRequestResponse(message: 'Invalid or expired OTP', code: 422);
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        $url = config('app.url') . "/reset-password?token={$token}";

        try {
            Mail::to($user)->send(new SendResetPasswordLink($user, $url));
        } catch (\Exception $e) {
            return $this->badRequestResponse('Failed to send password reset link.');
        }

        return $this->successResponse('OTP authentucated succesfully');
    }
}

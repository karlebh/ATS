<?php

namespace App\Actions\Auth;

use App\Mail\SendResetPasswordLink;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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
            return $this->badRequestResponse('This user does not exist.');
        }


        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        $url = config('app.url') . "/password-reset?token={$token}";




        // $status = Password::sendResetLink(
        //     ['email' => $user->email]
        // );

        return response()->json([
            'status' => true,
            'message' => "Password reset link sent successfully.",
            'tokens' => DB::table('password_reset_tokens')->get(),
        ], 200);
    }

    public function generateOTP()
    {
        return str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}

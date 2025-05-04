<?php

namespace App\Actions\Auth;

use App\Mail\PasswordResetSuccess;
use App\Mail\SendResetPasswordLink;
use App\Models\User;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Exception;

class ResetPasswordAction
{
    use ResponseTrait;

    public function execute(array $requestData)
    {
        $token = request('token');
        $tokenLifetime = config('ats.password_reset_token_lifetime');

        if (!$token) {
            return $this->badRequestResponse('A valid URL is required to change your password.');
        }

        $user = User::where('email', $requestData['email'])->first();

        if (!$user) {
            return $this->notFoundResponse('No account associated with this email.');
        }

        $resetRecord = DB::table('password_reset_tokens')->where('email', $requestData['email'])->first();

        if (!$resetRecord) {
            return $this->badRequestResponse('You need to request a password reset before changing your password.');
        }

        if (! Hash::check($token, $resetRecord->token)) {
            return $this->badRequestResponse('Invalid token.');
        }

        if (Carbon::parse($resetRecord->created_at)->diffInMinutes(now()) > $tokenLifetime) {
            DB::table('password_reset_tokens')->where('email', $user->email)->delete();
            return $this->badRequestResponse('Password reset token has expired. Please request a new one.', 400);
        }

        try {
            DB::beginTransaction();

            $user->forceFill([
                'password' => Hash::make($requestData['password']),
                'remember_token' => Str::random(60),
            ])->save();

            DB::table('password_reset_tokens')->where('email', $user->email)->delete();

            event(new PasswordReset($user));

            DB::commit();

            Mail::to($user)->send(new PasswordResetSuccess($user));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->serverErrorResponse('Something went wrong while resetting your password. Please try again.');
        }

        return $this->successResponse('Password updated successfully.', ['user' => $user->fresh()]);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\AddOTPAction;
use App\Actions\Auth\ResetPasswordAction;
use App\Actions\Auth\SendForgotPasswordLinkAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    use ResponseTrait;

    public function sendLink(PasswordResetRequest $request)
    {
        try {
            (new SendForgotPasswordLinkAction())->execute($request->validated());
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function addOTP(Request $request)
    {
        $requestData = $request->validate([
            'otp' => 'required|numeric|digits:6',
        ], [
            'otp.required' => 'The OTP field is required.',
            'otp.numeric' => 'The OTP must be a number.',
            'otp.digits' => 'The OTP must be exactly 6 digits.',
        ]);

        try {
            (new AddOTPAction())->execute($requestData);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function updatePassword(Request $request)
    {
        $requestData = $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
        try {
            (new ResetPasswordAction())->execute($requestData);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }
}

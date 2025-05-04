<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\AddOTPAction;
use App\Actions\Auth\AuthenticateOTPAction;
use App\Actions\Auth\ResetPasswordAction;
use App\Actions\Auth\SendForgotPasswordLinkAction;
use App\Actions\Auth\SendOTPAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordOTPRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password as RulesPassword;

class ResetPasswordController extends Controller
{
    use ResponseTrait;

    public function sendOTP(PasswordResetRequest $request)
    {
        try {
            return (new SendOTPAction())->execute($request->validated());
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function authenticateOTP(PasswordOTPRequest $request)
    {
        try {
            return (new AuthenticateOTPAction())->execute($request->validated());
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            return (new ResetPasswordAction())->execute($request->validated());
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }
}

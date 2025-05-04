<?php

namespace App\Http\Controllers;

use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingController extends Controller
{
    use ResponseTrait;

    public function __invoke(Request $request)
    {
        $request->validate([
            'old_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return $this->successResponse('Password changed successfully', ['user' => auth()->user()->fresh()]);
    }
}

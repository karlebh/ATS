<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use ResponseTrait;

    public function store(LoginRequest $request)
    {
        $user = User::where('email', $request->data)
            ->orWhere('username', $request->data)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->badRequestResponse('This user does not exist. please register');
        }

        if (! Auth::attempt(['email' => $user->email, 'password' => $request->password])) {
            return response()->json(['message' => 'Invalid credentials, 401']);
        }

        $token =
            $this->cleanBearerToken(
                $user->createToken($user->email)->plainTextToken
            );

        return $this->successResponse('Login succesful', [
            'token' => $token,
            'user' => $user
        ]);
    }

    private function cleanBearerToken($token)
    {
        return (explode('|', $token))[1];
    }
}

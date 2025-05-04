<?php

namespace App\Http\Controllers\Auth;

use App\Constants\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Http\JsonResponse;

class RegisteredUserController extends Controller
{
    use ResponseTrait;

    private function cleanToken($token)
    {
        return (explode('|', $token))[1];
    }

    public function store(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'role' => UserRole::FLOOR_TEAM,
            'password' => Hash::make($request->string('password')),
        ]);

        event(new Registered($user));

        Auth::login($user);

        $token = $user->createToken('API TOKEN')->plainTextToken;
        $token = $this->cleanToken($token);

        return $this->successResponse("Registration succesful", [
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function storeAdmin(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'role' => UserRole::ADMIN,
            'password' => Hash::make($request->string('password')),
        ]);

        event(new Registered($user));

        Auth::login($user);

        $token = $user->createToken('API TOKEN')->plainTextToken;
        $token = $this->cleanToken($token);

        return $this->successResponse("Registration succesful", [
            'token' => $token,
            'user' => $user,
        ], 201);
    }
}

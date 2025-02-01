<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LogoutController extends Controller
{
    use ResponseTrait;

    public function destroy(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();

        return $this->successResponse('Logout succesful');
    }
}

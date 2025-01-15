<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Resources\Api\UserRegisterResource;
use App\Http\Responses\CustomJsonResponse;
use App\Models\User;

class UserController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $user = User::create($request->validated());

        return CustomJsonResponse::success(
            'User registered successfully',
            ["addedUser" => new UserRegisterResource($user)],
            201
        );
    }

    public function login(UserLoginRequest $request)
    {
        $credentials = $request->only('username', 'password');
        if (!$token = auth()->guard('api')->attempt($credentials)) {
            return CustomJsonResponse::fail(
                'Username or password is incorrect',
                null,
                401
            );
        }

        return CustomJsonResponse::success(
            'User logged in successfully',
            [
                'accessToken' => $token,
                "refreshToken" => $token,
            ],
            201
        );
    }
}

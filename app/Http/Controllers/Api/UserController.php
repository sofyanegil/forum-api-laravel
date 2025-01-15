<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
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
}

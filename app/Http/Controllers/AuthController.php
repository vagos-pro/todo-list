<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Register new user.
     *
     * @param RegisterRequest $request
     * @return UserResource
     */
    public function register(RegisterRequest $request): UserResource
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return new UserResource($user);
    }
}

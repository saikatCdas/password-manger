<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use Exception;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request)
    {
        try{
            $data = $request->validated();

            $data['password'] = bcrypt($data['password']);

            $user = User::create($data);
            $token = $user->createToken('main')->plainTextToken;

            return response([
                'user' => new UserResource($user),
                'token' => $token
            ], 200);
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|string|exists:users,email',
            'password' => [
                'required'
            ],
            'remember' => 'boolean'
        ]);

        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);

        if (!Auth::attempt($credentials, $remember)){
            return response([
                'error' => 'The Provided credentials are not correct'
            ], 422);
        }

        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => new UserResource($user),
            'token' => $token
        ], 200);
    }

    public function logout(Request  $request)
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response([
            'success' => true
        ]);
    }
}

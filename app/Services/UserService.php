<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class UserService extends UserRepository
{
    
    public function guard()
    {
        return Auth::guard();
    }

    public function login($credentials)
    {
        if ($token = $this->guard()->attempt($credentials))
        {
            return $this->respondWithToken($token);
        }
        return response()->json([
            'success' => false,
            'message' => 'Incorrect username or password.'
        ], 401);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
            'user' => $this->guard()->user(),
        ]);
    }

    public function logout()
    {
        if(!auth()->user())
        {
            return response()->json([
                'success' => false,
                'message' => 'Already logged out',
            ]);
        }
        
        $this->guard()->logout();
        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
        
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}

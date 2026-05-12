<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            request()->session()->regenerate();
            return ['status' => true,'message' => 'Login successful',];
        }
        return ['status' => false,'message' => 'Invalid credentials'];
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return ['status' => true,'message' => 'Logged out successfully'];
    }
}

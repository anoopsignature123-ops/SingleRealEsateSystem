<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $result = $this->authService->login($credentials);

        if ($result['status']) {
            return redirect()
                ->route('admin.dashboard')
                ->with('success', $result['message']);
        }

        return back()->withErrors([
            'email' => $result['message'],
        ]);
    }

    public function logout()
    {
        $result = $this->authService->logout();

        return redirect()
            ->route('login')
            ->with('success', $result['message']);
    }
}

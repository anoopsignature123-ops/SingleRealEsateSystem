<?php

namespace App\Http\Controllers;

use App\Services\ForgotPasswordService;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    protected $forgotPasswordService;

    public function __construct(ForgotPasswordService $forgotPasswordService)
    {
        $this->forgotPasswordService = $forgotPasswordService;
    }

    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request,)
    {
        
        $request->validate([
            'email' => 'required|email'
        ]);

        $response = $this->forgotPasswordService
            ->sendResetLink($request->email);

        if (!$response['status']) {
            return back()->with('error', $response['message']);
        }

        return back()->with('success', $response['message']);
    }

    public function showResetForm($token)
    {
        return view('auth.reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $response = $this->forgotPasswordService
            ->resetPassword(
                $request->token,
                $request->password
            );

        if (!$response['status']) {
            return back()->with('error', $response['message']);
        }

        return redirect()->route('login')
            ->with('success', $response['message']);
    }
}
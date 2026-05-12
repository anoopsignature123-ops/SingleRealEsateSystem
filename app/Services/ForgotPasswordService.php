<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordService
{
    public function sendResetLink($email)
    {

        $user = User::where('email', $email)->first();

        if (! $user) {
            return ['status' => false, 'message' => 'Email not found.'];
        }

        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(['email' => $email],
            [
                'email' => $email,
                'token' => $token,
                'created_at' => now(),
            ]
        );

        $link = route('password.reset', $token);

        Mail::raw("Click here to reset password: $link", function ($message) use ($email) {
            $message->to($email)->subject('Reset Password');
        });

        return ['status' => true, 'message' => 'Reset link sent successfully.'];
    }

    public function resetPassword($token, $password)
    {
        $resetData = DB::table('password_reset_tokens')->where('token', $token)->first();
        if (! $resetData) {
            return ['status' => false, 'message' => 'Invalid token.'];
        }
        $user = User::where('email', $resetData->email)->first();
        $user->update(['password' => Hash::make($password)]);
        DB::table('password_reset_tokens')->where('email', $resetData->email)->delete();

        return ['status' => true, 'message' => 'Password reset successfully.'];
    }
}

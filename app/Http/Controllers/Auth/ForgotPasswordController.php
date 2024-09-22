<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                'exists:users,email',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                'unique:password_resets,email',  // check unique email in password_resets table
            ],
        ],[
            'email.required' => 'Email Id is required.',
            'email.email' => 'Invalid email format.',
            'email.exists' => 'Email does not exist.',
            'email.regex' => 'Password must contain at least 8 characters, including uppercase, lowercase, number, and special character.',
            'email.unique' => 'This email has already requested password reset.',
          ]);

        $token = str::random(60);

        DB::table('password_resets')->insert(
            ['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]
        );

        Mail::send('auth.verify',['token' => $token], function($message) use ($request) {
            $message->from('codingthunder1997@gmail.com','WRSL');
            $message->to($request->email);
            $message->subject('Reset Password Notification', 'Password Reset Link');
        });

        return back()->with('message', 'Password reset link has been sent to your email.');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordEmail;
use App\Mail\TestMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed'
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'message' => "Account successfully created."
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return response()->json([
                'message' => 'Invalid Credentials',
                'error_code' => 401
            ], 401);
        }
        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => "Login successfully.",
            'user' => $user,
            'access_token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }

    public function getProfile ()
    {
        $user = Auth::user();
        return response()->json($user);
    }

    public function testMail(Request $request)
    {
        $data = [
            'name' => 'Joe Doe',
            'body' => 'This is a test message',
        ];
        Mail::to('fennich0011soufiane@gmail.com')->send(new TestMail('test subject', $data));
    }

    public function forgetPasswordRequest(Request $request)
    {
        $request->validate([
            'email' => 'email|required'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user) {
            return response()->json([
                'errors' => ['email' => ["Account with this email not found"]]
            ], 422);
        }
        $code = rand(11111, 99999);
        $user->remember_token = $code;
        $user->save();

        $data = [
            'name' => $user->first_name.' '.$user->last_name,
            'code' => $code,
        ];
        Mail::to($user->email)->send(new ForgotPasswordEmail('test subject', $data));

        return response()->json([
           'message' => 'We have sended code to your email.'
        ]);
    }

    public function verifyAndChangePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required',
            'password' => 'required|confirmed'
        ]);
        $user = User::where('email', $request->email)
                    ->where('remember_token', $request->code)
                    ->first();
        if(!$user) {
            return response()->json([
                'errors' => ['code' => ['Invalid otp']]
            ], 422);
        }

        $user->remember_token = null;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'message' => 'Your password has been changed successfully.'
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed'
        ]);
        $user = $request->user();

        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json([
            'message' => 'Password updates successfully.'
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
        ]);
        $user = $request->user();

        if($user->email != $request->email)
        {
            $request->validate([
                'email' => 'required|unique:users,email'
            ]);
            $user->email = $request->email;
        }
        $user->first_name = $request->first_name ?? $user->first_name;
        $user->last_name = $request->last_name ?? $user->last_name;

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully.',
            'data' => $user
        ]);
    }
}

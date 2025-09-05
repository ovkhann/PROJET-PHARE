<?php

namespace App\Http\Controllers;

use App\Mail\RegisterEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class Register2Controller extends Controller
{
    public function register(Request $request)
    {
        $formFields = $request->validate([
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = new User();
        $user->fill($formFields);
        $user->password = bcrypt($formFields['password']);
        $user->token = Str::random(40);
        $user->save();

        Mail::to($user->email)->send(new RegisterEmail($user));

        return response()->json(['success' => 'success']);
    }

    public function verification(Request $request)
    {
        $formFields = $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string|exists:users,token',
        ]);

        $user = User::where('email', $formFields['email'])
            ->where('token', $formFields['token'])
            ->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid email or token'], 400);
        }

        $user->email_verified_at = now();
        $user->token = null;
        $user->save();

        return response()->json(['success' => 'Email verified successfully !']);
    }
}

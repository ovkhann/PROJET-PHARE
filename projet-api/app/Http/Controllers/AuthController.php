<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // récupère l'utilisateur par email
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Identifiants invalides'
            ], 401);
        }

        // génère le token
        $token = $user->createToken('auth-token')->plainTextToken;

        // retourne l'utilisateur avec le token
        return response()->json([
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'role' => $user->role,
                'street_name' => $user->street_name,
                'billing_address' => $user->billing_address,
                'delivery_address' => $user->delivery_address,
                'token' => $token,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout(); // déconnexion Laravel
        $request->session()->invalidate(); // invalide la session
        $request->session()->regenerateToken(); // régénère CSRF
        return response()->json(['message' => 'Déconnecté']);
    }
}

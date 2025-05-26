<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'role' => 'in:user,admin'
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'user',
        ]);

        // Envoi de l'email de vérification
        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Utilisateur créé avec succès. Veuillez vérifier votre e-mail.',
            'user' => $user
        ], 201);
    }
    public function login(Request $req)
{
    $credentials = $req->validate(['email'=>'required|email','password'=>'required']);
    if (!Auth::attempt($credentials)) {
        return response()->json(['message'=>'Identifiants invalides'], 401);
    }
    /** @var User $user **/
    $user = Auth::user();
    $token = $user->createToken('api-token')->plainTextToken;
    return response()->json(['token'=>$token, 'user'=>$user]);
}

public function logout(Request $req)
{
    $req->user()->currentAccessToken()->delete();
    return response()->json(['message'=>'Déconnecté']);
}

}

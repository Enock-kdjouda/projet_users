<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\UseCases\Auth\RegisterUserUseCase;
use App\Http\Requests\RegisterRequest;
use App\UseCases\Auth\LoginUserUseCase;
use App\Http\Requests\LoginRequest;


class AuthController extends Controller
{
    public function register(RegisterRequest $request, RegisterUserUseCase $useCase)
    {
        $user = $useCase->execute($request->validated());

        return response()->json([
            'message' => 'Utilisateur créé avec succès. Veuillez vérifier votre e-mail.',
            'user' => $user
        ], 201);
    }
    public function login(LoginRequest $request, LoginUserUseCase $useCase)
{
    $result = $useCase->execute($request->validated());
    return response()->json($result);
}

public function logout(Request $req)
{
    $req->user()->currentAccessToken()->delete();
    return response()->json(['message'=>'Déconnecté']);
}
public function me(Request $request)
{
    return response()->json($request->user());
}

}

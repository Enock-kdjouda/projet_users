<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    public function sendResetLinkEmail(Request $req) {
  $req->validate(['email'=>'required|email']);
  Password::sendResetLink($req->only('email'));
  return response()->json(['message'=>'Lien envoyé']);
}
public function reset(Request $req) {
  $req->validate([
    'token'=>'required','email'=>'required|email',
    'password'=>'required|confirmed'
  ]);
  $status = Password::reset(
    $req->only('email','password','password_confirmation','token'),
    function(User $user, string $password){
      $user->forceFill(['password'=>bcrypt($password)])->save();
    }
  );
  return $status === Password::PASSWORD_RESET
    ? response()->json(['message'=>'Mot de passe modifié'])
    : response()->json(['message'=>'Erreur de reset'], 500);
}

}

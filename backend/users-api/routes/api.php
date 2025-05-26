<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;


// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('login',          [AuthController::class,          'login']);
// Email verification (via lien dans l'email)
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return response()->json(['message' => 'Adresse e-mail vérifiée avec succès.']);
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

// Renvoi du lien de vérification
Route::post('/email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json(['message' => 'Lien renvoyé.']);
})->middleware(['auth:sanctum']);
// Mot de passe oublié
Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
                ? response()->json(['message' => __($status)])
                : response()->json(['message' => __($status)], 400);
});

// Réinitialisation du mot de passe
Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:6',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => bcrypt($password)
            ])->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
                ? response()->json(['message' => __($status)])
                : response()->json(['message' => __($status)], 400);
});
// Routes protégées – besoin d’un token valide
Route::middleware('auth:sanctum')->group(function(){

    // Déconnexion
    Route::post('logout', [AuthController::class, 'logout']);

    // Liste et lecture d’un user (tous les rôles)
    Route::get('users',       [UserController::class, 'index']);
    Route::get('users/{id}',  [UserController::class, 'show']);

    // Tout ce qui vient ici‑dessous n’est accessible qu’aux admins !
    Route::middleware('admin')->group(function(){
        Route::post('users',       [UserController::class, 'store']);
        Route::put('users/{id}',   [UserController::class, 'update']);
        Route::delete('users/{id}',[UserController::class, 'destroy']);
    });

});

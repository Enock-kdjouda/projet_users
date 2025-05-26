<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
  public function handle(Request $req, Closure $next)
{
    if (!$req->user()?->isAdmin()) {
        return response()->json(['message'=>'Accès refusé'], 403);
    }
    return $next($req);
}

}

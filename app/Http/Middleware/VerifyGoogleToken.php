<?php

namespace App\Http\Middleware;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Illuminate\Http\Request;


class VerifyGoogleToken
{
    protected $auth;

    public function __construct()
    {
        // Cargar manualmente las credenciales
        $this->auth = (new Factory)
            ->withServiceAccount(storage_path('app/firebase_credentials.json'))
            ->createAuth();
    }

    public function handle(Request $request, \Closure $next)
    {
        $idToken = $request->bearerToken();

        if (!$idToken) {
            return response()->json(['error' => 'No token provided'], 401);
        }
        try {
            // Verificar el ID token
            $verifiedIdToken = $this->auth->verifyIdToken($idToken);
            // Obtener el payload (claims) del token verificado
            $payload = $verifiedIdToken->claims()->all();
            $request->merge(['userToken' => [
                'uid' => $payload['sub'],
                'email' => $payload['email'],
                'name' => $payload['name'],
            ]]);            
            return $next($request);
        } catch (FailedToVerifyToken $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
    }
}

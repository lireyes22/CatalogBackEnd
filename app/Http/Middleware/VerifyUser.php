<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class VerifyUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role = null): Response
    {
        $user = $this->getUser($request['userToken']['email']);
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }
        $supplier = $user->isSupplier();
        if ($role === 'supplier' && !$supplier) {
            return response()->json(['error' => 'Supplier Unauthorized.'], 401);
        }
        $client = $user->isClient();
        if($role === 'client' && !$client) {
            return response()->json(['error' => 'Client Unauthorized.'], 401);
        }
        $request->merge(['user' => $user,
            'supplier' => $supplier,
            'client' => $client]);
        return $next($request);
    }
    private function getUser($email)
    {
        return User::where('User_Email', $email)->first();
    }
}

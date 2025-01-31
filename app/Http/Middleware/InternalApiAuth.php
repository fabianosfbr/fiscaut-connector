<?php

namespace App\Http\Middleware;

use App\Models\Configuracao;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InternalApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        $api_key = Configuracao::first()?->api_key;

        if (! $token || $token !== $api_key) {
            $response = [
                'success' => false,
            ];

            return response()->json($response, 401);
        }

        return $next($request);
    }
}

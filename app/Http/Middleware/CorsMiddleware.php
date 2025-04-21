<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Define the allowed origins, methods, and headers
        $allowedOrigins = ['*']; // Replace with your frontend domains
        $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'];
        $allowedHeaders = ['Content-Type', 'X-Requested-With', 'Authorization', 'Accept'];

        $response = $next($request);

        // Handle the CORS headers
        $response->headers->set('Access-Control-Allow-Origin', implode(', ', $allowedOrigins));
        $response->headers->set('Access-Control-Allow-Methods', implode(', ', $allowedMethods));
        $response->headers->set('Access-Control-Allow-Headers', implode(', ', $allowedHeaders));
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Max-Age', '86400');

        // Handle pre-flight request
        if ($request->getMethod() === 'OPTIONS') {
            return response()->json([], 200);
        }

        return $response;
    }
}

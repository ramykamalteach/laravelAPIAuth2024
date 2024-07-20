<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Services\JwtService;

class JwtMiddleware
{
    private $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //return $next($request);

        $token = $request->bearerToken();

        /* \Log::info('Received token: ' . $token); */
        

        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        try {
            $token = $this->jwtService->parseToken($token);
            /* \Log::info('Parsed token : ' . json_encode($token->claims()->all())); */

            if (!$this->jwtService->validateToken($token)) {
                return response()->json(['error' => 'Invalid token01'], 401);
            }

            // Add user info to the request
            $request->merge([
                'user_id' => $token->claims()->get('user_id'),
                'fullName' => $token->claims()->get('fullName'),
            ]);

            return $next($request);

        } catch (RequiredConstraintsViolated $e) {
            return response()->json(['error' => 'Token constraints violated: ' . $e->getMessage()], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token processing failed: ' . $e->getMessage()], 401);
        }
    }
}

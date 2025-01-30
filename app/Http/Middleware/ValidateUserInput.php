<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ValidateUserInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'age' => 'required|integer|min:0'
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()
                ], 400);
            }
    
            return $next($request);
    }
}

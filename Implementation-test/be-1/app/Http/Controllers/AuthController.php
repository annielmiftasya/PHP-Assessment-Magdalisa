<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
   protected AuthService $authService;

   public function __construct(AuthService $authService)
   {
      $this->authService = $authService;
   }

   public function register(Request $request): JsonResponse
   {
      try {
         $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'c_password' => 'required|same:password',
         ]);

         $token = $this->authService->register($validatedData);

         return response()->json([
            'success' => true,
            'data' => [
               'token' => $token,
               'name' => $request->input('name'),
            ],
            'message' => 'User registered successfully.'
         ], 201);
      } catch (ValidationException $e) {
         return response()->json([
            'success' => false,
            'message' => 'Validation Error.',
            'errors' => $e->errors(),
         ], 422);
      }
   }

   public function login(Request $request): JsonResponse
   {
      $credentials = $request->only('email', 'password');

      try {
         $token = $this->authService->login($credentials);

         return response()->json([
            'success' => true,
            'data' => [
               'token' => $token,
               'name' => Auth::user()->name,
            ],
            'message' => 'User logged in successfully.'
         ], 200);
      } catch (ValidationException $e) {
         return response()->json([
            'success' => false,
            'message' => 'Validation Error.',
            'errors' => $e->errors(),
         ], 422);
      } catch (\Exception $e) {
         return response()->json([
            'success' => false,
            'message' => 'Unauthorized.',
            'errors' => $e->getMessage(),
         ], 401);
      }
   }
}

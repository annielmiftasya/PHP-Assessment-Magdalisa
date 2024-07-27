<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\PersonalAccessTokenResult;

class AuthService
{
   protected UserRepositoryInterface $userRepository;

   public function __construct(UserRepositoryInterface $userRepository)
   {
      $this->userRepository = $userRepository;
   }


   public function register(array $data): string
   {
      $data['password'] = Hash::make($data['password']);
      $user = $this->userRepository->create($data);


      $tokenResult = $user->createToken('MyApp');
      return $tokenResult->accessToken;
   }


   public function login(array $credentials): string
   {
      if (Auth::attempt($credentials)) {
         $user = Auth::user();

         $tokenResult = $user->createToken('MyApp');
         return $tokenResult->accessToken;
      }

      $user = $this->userRepository->findByEmail($credentials['email']);

      if (!$user) {
         throw ValidationException::withMessages(['email' => 'The provided email address is incorrect.']);
      }

      throw ValidationException::withMessages(['password' => 'The provided password is incorrect.']);
   }
}

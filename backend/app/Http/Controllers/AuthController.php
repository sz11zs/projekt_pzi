<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Mix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    public function register(Request $request):JsonResponse
    {
       $user= User::create([
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=> Hash::make($request->password),
        'role_id' => $request->role_id,
       ]);

       $token = $user->createToken('token')->plainTextToken;

       return response()->json([
        'user'=> $user,
        'token'=> $token,
       ]);
    }

     public function login(Request $request): JsonResponse
    {
       $request->validate([
        'name' => 'required|string',
        'password' => 'required|string',
    ]);

    $user = User::where('name', $request->name)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials.'], 401);
    }

    $token = $user->createToken('token')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token,
    ]);
    }

     public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message'=>'Logged out.']);
    }

     public function getUser(Request $request): mixed
    {
      return $request->user();
    }
}

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
       $data = $request->validate([
        'name' => 'required|string|max:100|unique:users,name',
        'email' => 'required|email|max:100|unique:users,email',
        'password' => 'required|string|min:8',
       ], [
        'name.required' => 'Upišite ime.',
        'name.unique' => 'Korisnik s tim imenom već postoji.',
        'email.required' => 'Upišite e-mail adresu.',
        'email.email' => 'E-mail adresa nije ispravna.',
        'email.unique' => 'Korisnik s tom e-mail adresom već postoji.',
        'password.required' => 'Upišite lozinku.',
        'password.min' => 'Lozinka mora imati najmanje 8 znakova.',
       ]);

       // uloga se postavlja isključivo na serveru: novi korisnik je uvijek kupac
       $user= User::create([
        'name'=>$data['name'],
        'email'=>$data['email'],
        'password'=> Hash::make($data['password']),
        'role_id' => 2,
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

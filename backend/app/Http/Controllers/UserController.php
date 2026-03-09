<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{
    public function index(): Collection
    {
       return User::with('role')->get();
    }

    public function store(Request $request): User
    {
        $data = $request->validate ([
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required',
            'role_id'=>'required|exists:roles,id',
        ]);

        $data['password']=bcrypt($data['password']);

        return User::create($data);
    }
    public function show(string $id): User
    {
        return User::findOrFail($id);
    }
    public function update(Request $request, string $id): User
    {
        $user= User::findOrFail($id);

        $data=$request->validate([
        'name'=>'required',
        'email'=>'required|email|unique:users,email,'.$user->id,
        'role_id'=>'required|exists:roles,id',
        ]);

        $user->update($data);

        return $user;
    }

    public function customers() //dashboard
     {
         return \App\Models\User::where('role_id', 2)
          ->orderBy('name')
          ->get(['id','name','email','role_id']);
     }

     public function destroy(string $id): void
    {
        $user = User::findOrFail($id);
        $user->delete();
    }

    public function createAdmin(Request $request)
    {
      // samo SUPER ADMIN
     $user = request()->user();


    if (!$user || (int) $user->role_id !== 4) {
    return response()->json(['message' => 'Forbidden'], 403);
      }


      $data = $request->validate([
        'name' => 'required|string|max:100|unique:users,name',
        'email' => 'required|email|max:100|unique:users,email',
        'password' => 'required|string|min:6',
      ]);

      $admin = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'role_id' => 3, // ADMIN
      ]);

      return response()->json($admin, 201);
    }

}

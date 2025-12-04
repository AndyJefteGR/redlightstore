<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use App\Models\User; 

class AuthController extends Controller

{
   public function signup(Request $request)
    {
      
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', 
            'password' => 'required|string|min:6', 
        ]);

       
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            
            'password' => Hash::make($validatedData['password']),
        ]);
        
        return response()->json([
            'message' => 'User registered successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ], 201); 
    }
}

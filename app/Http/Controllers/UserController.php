<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
     public function register(Request $request)
    {
        $request->validate([
            "name"=> "required|string|max:255",
            "email"=> "required|string|email|max:100|unique:users,email",
            "password"=> "required|string|min:8|confirmed",
            ]);
            $user = User::create([
                "name"=> $request->name,
                "email"=> $request->email,
                "password"=> Hash::make($request->password)
                ]);
                return response()->json([
                    'message' => 'User Registerd Successfully',
                    'User' => $user
                ],201);
    }
}

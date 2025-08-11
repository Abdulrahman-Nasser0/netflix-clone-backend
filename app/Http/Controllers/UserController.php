<?php
namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $valData = $request->validated();
        $user = User::create($valData);
        return response()->json([
            'message'               => 'User Registerd Successfully',
            'name'                  => $user->name,
            'email'                 => $user->email,
            'password'              => $user->password,
            'password_confirmation' => $user->password,
        ], 201);
    }

    public function login(Request $request)
    {

        $request->validate([
            "email"    => "required|string|email",
            "password" => "required|string",
        ]);

        if (! Auth::attempt($request->only("email", "password"))) {
            return response()->json([
                "message" => "Invalid Email or Password",
            ], 401);
        }

        $user  = User::where("email", $request->email)->FirstOrFail();
        $token = $user->createToken('auth_Token')->plainTextToken;

        return response()->json([
            'message' => 'Login Successfully',
            'User'    => $user,
            'Token'   => $token,
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout Successfully']);
    }

    public function user(ProfileRequest $request)
    {
        $userId  = Auth::user()->id;
        $user    = User::findOrFail($userId);
        $valdata = $request->validated();
        $user->update($valdata);
        return response()->json([
            'message' => 'Profile Updated Successfully',
            'user'    => $user,
        ], 200);
    }
}

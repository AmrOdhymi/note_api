<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8', 
            'profile_image' => 'nullable|image|max:2048'
        ]);

        $userData = [
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ];
        
        if ($request->hasFile('profile_image')) {
            $userData['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }
        
        $user = User::create($userData);
        $token = $user->createToken('registration_token')->plainTextToken;

        return response()->json([
            'message' => 'user_created',
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'incorrect password or email'], 401);
        }
        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json(['token' => $token,'username'=>$user->username,'profile_image'=>$user->profile_image]);
    }

    public function logout(Request $request){
        return $request->user();

    }

}

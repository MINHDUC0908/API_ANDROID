<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            $email = $request->input('email');
            $password = $request->input('password');
            $user = User::where('email', $email)->first();
            if (!$user) {
                return response()->json([
                    'message' => 'Email hoặc mật khẩu không đúng',
                ], 404);
            }
            if (!Hash::check($password, $user->password)) {
                return response()->json([
                    'message' => 'Email hoặc mật khẩu không đúng',
                ], 404);
            }
    
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        "phone" => $user->phone,
                        "gender" => $user->gender,
                        "birth_date" => $user->birth_date,
                        "image" => $user->image,
                        "role" => $user->role
                    ],
                    'token' => $token,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Login failed',
                'error' => $e->getMessage(),
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Logout successful',
        ], 200);
    }
    public function user(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'message' => 'User retrieved successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                "phone" => $user->phone,
                "gender" => $user->gender,
                "birth_date" => $user->birth_date,
                "image" => $user->image,
                "role" => $user->role
            ],
        ], 200);
    }
}

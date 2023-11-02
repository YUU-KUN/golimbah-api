<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $input = $request->all();

        $validator = \Validator::make($input, [
            'fullname' => 'required|string|max:255',
            'profession' => 'required|string|in:student,college_student,teacher,college_teacher,employee',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|confirmed',
            
            // 'gender' => 'nullable|string|in:male,female',
            // 'photo' => 'nullable|string|unique:users,phone',
            // 'email' => 'nullable|string',
            // 'phone' => 'nullable|string',
            // 'dob' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 401);
        }

        $input['password'] = Hash::make($request->password);
        $input['role'] = 'user';

        $user = User::create($input);

        $token = $user->createToken('User Token')->accessToken;

        return response()->json(
            [ 
                'success' => true,
                'message' => 'Berhasil mendaftarkan pengguna',
                'data' => [
                    'user' => $user, 
                    'token' => $token
                ]
            ]
        );
    }

    public function login(Request $request)
    {
        $input = $request->all();
        $validator = \Validator::make($input, [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 401);
        }
        
        $user = User::where('username', $input['username'])->first();
        if (!$user || !Hash::check($input['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah'
            ], 401);
        }

        $token = $user->createToken('User Token')->accessToken;

        return response()->json([
            'success' => true,
            'message' => 'Berhasil login',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ]);
    }

    public function getProfile() {
        return response()->json([
            'success' => true,
            'message' => "Berhasil mendapatkan data user",
            "data" => Auth::user()
        ], 200);
    }

    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

}

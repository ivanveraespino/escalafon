<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;
use App\Models\Archivo;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use DB;

class AuthController extends Controller
{
  
        public function register(Request $request)
        {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
            ]);
    
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);
    
            return response()->json(['user' => $user], 201);
        }
    
        public function login(Request $request)
        {
            $credentials = $request->only('email', 'password');
    
            if (!Auth::attempt($credentials)) {
                return response()->json(['message' => 'Credenciales inválidas'], 401);
            }
    
            $user = $request->user();
            $token = $user->createToken('auth-token')->plainTextToken;
    
            return response()->json(['token' => $token, 'user' => $user]);
        }
    
        public function logout(Request $request)
        {
            $request->user()->tokens()->delete();
    
            return response()->json(['message' => 'Sesión cerrada']);
        }
    
        public function user(Request $request)
        {
            return response()->json(['user' => $request->user()]);
        }
    
}
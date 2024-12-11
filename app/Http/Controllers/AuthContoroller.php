<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthContoroller extends Controller
{
    public function username()
    {
        return 'username';
    }

    public function index() {
        return view('auth.login');
    }

    public function login(Request $request) {
        // return $request;
        try {
            $rules = [
                'username' => 'required',
                'password' => 'required',
            ];
            $messages = [
                'required' => 'Kolom :attribute tidak boleh kosong.',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()->route('login')->withErrors(['warning' => 'Username atau Password tidak boleh kosong!']);
            }

            $user = User::where('username',$request->username)->first();
            if(!$user){
                return redirect()->route('login')->withErrors(['warning' => 'User tidak ditemukan.']);
            }

            if (Auth::attempt(['username' => $request->username, 'password' => $request->password], $request->filled('remember'))) {
                return redirect()->route('dashboard.main')->with(['success' => 'Login Berhasil']);
            }

            return redirect()->route('login')->withErrors(['warning' => 'Login gagal, periksa kembali username dan password.']);
        } catch (\Throwable $th) {
            Log::error(json_encode([
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ],JSON_PRETTY_PRINT));
            return redirect()->route('login')->withErrors(['failed' => 'Internal Server Error!']);
        }
    }

    public function logout(Request $request, $id) {
        try{
            $request->session()->flush();
            Auth::logout();
            return redirect()->route('login')->with(['success' => 'anda telah logout']);
        } catch (\Throwable $th) {
            Log::error(json_encode([
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ],JSON_PRETTY_PRINT));
            return back()->withErrors(['failed' => 'Internal Server Error!']);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $akun = Akun::where('email', $request->email)->first();

        if ($akun && Hash::check($request->password, $akun->password)) {
            Session::put('login', true);
            Session::put('akun_id', $akun->id);
            Session::put('akun_name', $akun->name);
            Session::put('akun_email', $akun->email);
            Session::put('akun_role', $akun->role);

            return redirect()->route('home')->with('success', 'Login Berhasil!');
        } else {
            return redirect()->back()
                ->withErrors(['login' => 'Email atau password salah'])
                ->withInput();
        }
    }

    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:akuns',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,customer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $akun = Akun::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect('/')->with('success', 'Registration Berhasil Silakan Login!');
    }

    public function home()
    {
        if (!Session::get('login')) {
            return redirect('/')->with('error', 'Silakan login terlebih dahulu.');
        }

        $role = Session::get('akun_role');
        $totalProducts = Product::count();
        
        return view('home', compact('role', 'totalProducts'));
    }

    public function logout()
    {
        Session::flush();
        return redirect('/')->with('success', 'Logout Berhasil!');
    }
}
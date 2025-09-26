<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CustomAuthController extends Controller
{

    public function index()
    {
        return view('signin');
    }


    public function customSignin(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required',
            ],
            [
                'email.required' => 'Email is required',
                'password.required' => 'Password is required',

            ]

        );

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // redirect sesuai role
            switch ($user->role) {
                case 'admin_desa':
                    return redirect()->route('dashboard.admin-desa')->withSuccess('Signed in');
                case 'admin_koperasi':
                    return redirect()->route('dashboard.admin-koperasi')->withSuccess('Signed in');
                case 'kasir_koperasi':
                    return redirect()->route('dashboard.kasir-koperasi')->withSuccess('Signed in');
                case 'kepala_desa':
                    return redirect()->route('dashboard.kepala-desa')->withSuccess('Signed in');
            }
        }


        return redirect()->route('signin')
            ->withErrors(['login_error' => 'Email atau password salah.']);
    }
    public function registration()
    {
        return view('register');
    }


    public function customRegister(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|min:5',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'confirmpassword' => 'required|min:6',
            ],
            [
                'name.required' => 'Userame is required',
                'email.required' => 'Email is required',
                'password.required' => 'Password is required',
                'confirmpassword.required' => 'Confirm Password is required',

            ]
        );

        $data = $request->all();
        $check = $this->create($data);

        return redirect("signin")->withSuccess('You have signed-in');
    }


    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'confirmpassword' => Hash::make($data['confirmpassword'])
        ]);
    }


    public function dashboard()
    {
        if (Auth::check()) {
            return view('index');
        }

        return redirect("signin")->withSuccess('You are not allowed to access');
    }


    public function signOut(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('signin')->with('success', 'Berhasil logout');
    }
}

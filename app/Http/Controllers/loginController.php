<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class loginController extends Controller
{
    public function indexLogin()
    {
        return view('auth.login');
    }
    public function indexForgotPass()
    {
        return view('auth.forgot-pass');
    }
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('email', $googleUser->getEmail())->first();

        if($user){
            Auth::login($user);
        }else{
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => Hash::make('password')
            ]); 
            Auth::login($user);
        }

        if($user == 'admin'){
            return redirect()->route('admin.dashboard');
        }else{
            return redirect()->route('user.dashboard');
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            if($user == 'admin'){
                return redirect()->route('admin.dashboard');
            }else{
                return redirect()->route('user.dashboard');
            }
        }

        return back()->withErrors('email', 'Email or password is incorrect');
    }
}
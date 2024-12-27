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
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if($user){
                Auth::login($user);
            }else{
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make('password'),
                    'role' => 'user',
                ]); 
                Auth::login($user);
            }

            if($user->role == 'admin'){
                return redirect()->route('admin.dashboard');
            }elseif($user->role == 'user'){
                return redirect()->route('user.dashboard');
            }
        } catch (\Exception $e) {
            \Log::error('Google login error: ' . $e->getMessage());
            return redirect()->route('login')
                ->withErrors(['error' => 'Unable to login with Google. Please try again.']);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            if($user->role === 'admin'){
                return redirect()->route('admin.dashboard');
            }elseif ($user->role === 'user') {
                return redirect()->route('user.dashboard');
            }
        }

        return back()->withErrors('email', 'Email or password is incorrect');
    }
}

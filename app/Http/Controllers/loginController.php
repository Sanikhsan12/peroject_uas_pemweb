<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\passwordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

class loginController extends Controller
{
    // INDEXING Halaman
    public function indexLogin()
    {
        return view('auth.login');
    }
    public function indexForgotPass()
    {
        return view('auth.forgot-pass');
    }

    public function indexResetPass(Request $request)
    {
        return view('auth.reset-pass',[
            'token' => $request->token,
            'email' => $request->email
        ]);
    }

    public function indexKonfirmasiReset()
    {
        return view('auth.konfirmasi-reset');
    }

    // LOGIN DENGAN GOOGLE
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


    // LOGIN DENGAN EMAIL DAN PASSWORD
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

        return back()->withErrors(['email' => 'Email atau Password salah']);
    }

    // forgot password
    public function forgotPassword(Request $request)
    {
        // validasi dari tabel users
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ],
        [
            'email.exists' => 'Email not found'
        ]);

        $email = $request->email;
        $token = str::random(64);

        passwordReset::where('email', $email)->delete();
        
        // simpan token ke database
        passwordReset::create([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        // kirim email
        try{
            Mail::to($email)->send(new ResetPasswordMail($token, $email));

            return back()->with('message', 'Check your email to reset password');
        } catch (\Exception $e) {
            \Log::error('Mail error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Unable to send email. Please try again.']);
        }
    }

    // reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
    
        $passwordReset = passwordReset::where([
            ['token', $request->token],
            ['email', $request->email],
        ])->first();
    
        if (!$passwordReset) {
            return back()->withErrors(['error' => 'Invalid token!']);
        }
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return back()->withErrors(['error' => 'User not found']);
        }
    
        // Update password
        $user->password = Hash::make($request->password);
        $user->save();
    
        // Delete the token
        passwordReset::where('email', $request->email)->delete();
    
        return redirect()->route('login')->with('status', 'Password has been reset!');
    }
}

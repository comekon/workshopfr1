<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class GoogleLoginController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->email],
            [
                'nama' => $googleUser->name,
                'id_google' => $googleUser->id,
                'password' => bcrypt('google_login')
            ]
        );

        $otp = rand(100000, 999999);

        $user->update([
            'otp' => $otp,
            'otp_expired_at' => now()->addMinutes(5)
        ]);

        Mail::raw("Kode OTP Login Kamu: $otp", function($message) use ($user) {
            $message->to($user->email)
                    ->subject('Kode OTP Login');
        });

        session(['otp_user_id' => $user->id]);

        return redirect('/otp');
    }

    public function otpIndex()
    {
        return view('auth.otp');
    }

    public function otpVerify(Request $request)
    {
        $user = User::find(session('otp_user_id'));

        if (!$user) {
            return redirect('/login');
        }

        if ($request->otp == $user->otp) {
            Auth::login($user);

            $user->update([
                'otp' => null
            ]);

            // Cek apakah user ini adalah vendor, jika ya arahkan ke vendor dashboard
            $isVendor = Vendor::where('user_id', $user->id)->exists();

            if ($isVendor) {
                return redirect()->route('vendor.dashboard');
            }

            return redirect('/');
        }

        return back()->withErrors(['otp' => 'OTP salah']);
    }
}

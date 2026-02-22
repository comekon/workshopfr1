<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/otp';

    protected function redirectTo()
    {
        return '/otp';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        $otp = rand(100000, 999999);

        $user->update([
            'otp' => $otp,
            'otp_expired_at' => Carbon::now()->addMinutes(5)
        ]);

        Mail::raw("Kode OTP Login Kamu: $otp", function($message) use ($user){
            $message->to($user->email)
                    ->subject('Kode OTP Login');
        });

        Auth::logout();

        session(['otp_user_id' => $user->id]);
    }

    public function login(Request $request)
    {

        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {

            $user = Auth::user();

            // Generate OTP
            $otp = rand(100000, 999999);

            $user->update([
                'otp' => $otp,
                'otp_expired_at' => Carbon::now()->addMinutes(5)
            ]);

            Mail::raw("Kode OTP Login Kamu: $otp", function($message) use ($user){
                $message->to($user->email)
                        ->subject('Kode OTP Login');
            });

            Auth::logout();
            session(['otp_user_id' => $user->id]);

            return redirect('/otp');
        }

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $user = Auth::user();

        $otp = rand(100000, 999999);

        $user->update([
            'otp' => $otp,
            'otp_expired_at' => Carbon::now()->addMinutes(5)
        ]);

        Mail::raw("Kode OTP Login Kamu: $otp", function($message) use ($user){
            $message->to($user->email)
                    ->subject('Kode OTP Login');
        });

        Auth::logout();

        session(['otp_user_id' => $user->id]);

        return redirect('/otp'); // override intended()
    }



}

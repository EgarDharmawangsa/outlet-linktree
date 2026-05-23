<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function redirect()
    {
        return redirect()->route(Auth::guard('web')->check() ? 'beranda' : 'masuk');
    }

    public function index()
    {
        return view('pages.login');
    }

    public function authenticate(Request $request)
    {
        $validated_credentials = $request->validate([
            'email'     => 'required|string|email|min:5|max:255',
            'password'  => 'required|string|min:8|max:255',
            'g-recaptcha-response' => 'required|string'
        ], [
            'g-recaptcha-response.required' => 'Verifikasi reCAPTCHA wajib centang!',
            'g-recaptcha-response.string' => 'Verifikasi reCAPTCHA tidak valid!'
        ]);

        $response = Http::post("https://recaptchaenterprise.googleapis.com/v1/projects/outlet-linktree/assessments?key=" . env('RECAPTCHA_API_KEY'), [
            'event' => [
                'token' => $validated_credentials['g-recaptcha-response'],
                'expectedAction' => 'LOGIN',
                'siteKey' => env('RECAPTCHA_SITE_KEY'),
            ]
        ]);

        $result = $response->json();

        if (!$response->successful() || data_get($result, 'tokenProperties.valid') !== true) {
            return back()->with('error', 'Verifikasi reCAPTCHA gagal!')->withInput();
        }

        $credentials = [
            'email' => $validated_credentials['email'],
            'password' => $validated_credentials['password'],
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('beranda')->with('success', 'Masuk berhasil.');
        }

        return back()->with('error', 'Email atau kata sandi salah!')->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('masuk')->with('success', 'Keluar berhasil.');
    }
}

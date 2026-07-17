<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function __construct(
        protected \App\Services\ActivityLogService $activityLog
    ) {}

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $this->activityLog->log('login', 'Auth', 'Login ke sistem.', $request->user());
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:50', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ], [
            'name.max' => 'Nama maksimal 100 karakter.',
            'email.max' => 'Email maksimal 50 karakter.',
        ]);

        // Role SENGAJA di-hardcode, tidak diambil dari input user.
        // Siapa pun yang daftar sendiri lewat form publik ini otomatis jadi Staff Gudang
        // (role dengan akses paling minim). Kalau butuh Admin/Manajer Gudang, harus
        // dinaikkan manual oleh Admin lewat halaman Kelola Pengguna — bukan dipilih sendiri.
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Staff Gudang',
        ]);

        Auth::login($user);
        $this->activityLog->log('register', 'Auth', 'Mendaftar akun baru (Staff Gudang).', $user);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        $this->activityLog->log('logout', 'Auth', 'Logout dari sistem.', $request->user());
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Verifikasi password buat buka lockscreen (bukan login ulang — sesi tetap sama).
     */
    public function unlock(Request $request)
    {
        $request->validate(['password' => ['required']]);

        if (Hash::check($request->password, $request->user()->password)) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Password salah.'], 422);
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Link reset password sudah dikirim ke email kamu.')
            : back()->withErrors(['email' => __($status)])->onlyInput('email');
    }

    public function showResetPasswordForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Password berhasil diganti. Silakan masuk.')
            : back()->withErrors(['email' => __($status)]);
    }
}

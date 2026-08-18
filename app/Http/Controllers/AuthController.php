<?php

namespace App\Http\Controllers;

use App\Mail\RegistrationOtpMail;
use App\Models\CandidateProfile;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\NumberGenerator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function loginForm(): View
    {
        return view('auth.login');
    }

    public function registerForm(): View
    {
        return view('auth.register');
    }

    public function login(Request $request, AuditLogger $audit): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau password tidak valid.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        $audit->log('LOGIN', 'AUTH', request: $request);

        return redirect()->intended(route('dashboard'));
    }

    public function register(Request $request, NumberGenerator $numbers): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $plainOtp = $this->generateRegistrationOtp();

        $user = DB::transaction(function () use ($data, $numbers, $plainOtp) {
            $user = User::query()->create([
                ...$data,
                'registration_otp' => Hash::make($plainOtp),
                'registration_otp_expires_at' => now()->addMinutes(10),
            ]);

            $role = Role::query()->where('name', 'USER')->firstOrFail();
            $user->roles()->attach($role->id);
            CandidateProfile::query()->create([
                'user_id' => $user->id,
                'candidate_number' => $numbers->candidateNumber(),
                'full_name' => $user->name,
                'email' => $user->email,
            ]);

            return $user;
        });

        Mail::to($user->email)->send(new RegistrationOtpMail($user, $plainOtp));

        $request->session()->put('pending_otp_user_id', $user->id);

        return redirect()
            ->route('register.otp.form')
            ->with('success', 'Registrasi berhasil. Kode OTP sudah dikirim ke email Anda.');
    }

    public function otpForm(Request $request): View|RedirectResponse
    {
        $user = $this->pendingOtpUser($request);

        if (! $user) {
            return redirect()->route('register')->withErrors(['email' => 'Sesi verifikasi OTP tidak ditemukan. Silakan daftar kembali.']);
        }

        return view('auth.verify-otp', ['user' => $user]);
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $user = $this->pendingOtpUser($request);

        if (! $user) {
            return redirect()->route('register')->withErrors(['email' => 'Sesi verifikasi OTP tidak ditemukan. Silakan daftar kembali.']);
        }

        if (! $user->registration_otp || ! Hash::check($data['otp'], $user->registration_otp)) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.'])->onlyInput('otp');
        }

        if (! $user->registration_otp_expires_at || Carbon::parse($user->registration_otp_expires_at)->isPast()) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kedaluwarsa. Silakan kirim ulang kode OTP.']);
        }

        $user->forceFill([
            'email_verified_at' => now(),
            'registration_otp' => null,
            'registration_otp_expires_at' => null,
        ])->save();

        $request->session()->forget('pending_otp_user_id');
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Email berhasil diverifikasi. Silakan lengkapi profil kandidat.');
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $user = $this->pendingOtpUser($request);

        if (! $user) {
            return redirect()->route('register')->withErrors(['email' => 'Sesi verifikasi OTP tidak ditemukan. Silakan daftar kembali.']);
        }

        $plainOtp = $this->generateRegistrationOtp();
        $user->forceFill([
            'registration_otp' => Hash::make($plainOtp),
            'registration_otp_expires_at' => now()->addMinutes(10),
        ])->save();

        Mail::to($user->email)->send(new RegistrationOtpMail($user, $plainOtp));

        return back()->with('success', 'Kode OTP baru sudah dikirim ke email Anda.');
    }

    private function pendingOtpUser(Request $request): ?User
    {
        $userId = $request->session()->get('pending_otp_user_id');

        if (! $userId) {
            return null;
        }

        return User::query()
            ->whereKey($userId)
            ->whereNull('email_verified_at')
            ->first();
    }

    private function generateRegistrationOtp(): string
    {
        return (string) random_int(100000, 999999);
    }

    public function logout(Request $request, AuditLogger $audit): RedirectResponse
    {
        $audit->log('LOGOUT', 'AUTH', request: $request);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('career.home');
    }
}

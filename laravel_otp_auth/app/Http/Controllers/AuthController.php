<?php

namespace App\Http\Controllers;

use App\Mail\SendOTP;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Attempt to authenticate the student
        if (Auth::guard('student')->attempt($credentials)) {
            $student = Auth::guard('student')->user();
            if ($student->status === 'active') {
                return redirect()->intended('/dashboard');
            } else {
                Auth::guard('student')->logout();

                return back()->withErrors(['email' => 'Your account is not active.']);
            }
        }

        // If authentication fails
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students',
            'password' => 'required|string|min:8|confirmed',

        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        // dd($data);
        Student::create($data);

        // Auth::guard('student')->login($data);

        return redirect('/login');
    }

    public function logout()
    {
        Auth::guard('student')->logout();

        return redirect('/login');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOTP(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $student = Student::where('email', $request->email)->first();

        if ($student) {
            $otp = rand(100000, 999999);
            $student->update([
                'otp' => $otp,
                'otp_expires_at' => Carbon::now()->addMinute(),
            ]);

            Mail::to($student->email)->send(new SendOTP($student, $otp));

            return redirect('/reset-password')->with('email', $student->email);
        }

        return back()->withErrors(['email' => 'Email not found']);
    }

    public function showResetPasswordForm()
    {
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $student = Student::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('otp_expires_at', '>', Carbon::now())
            ->first();

        if ($student) {
            $student->update([
                'password' => Hash::make($request->password),
                'otp' => null,
                'otp_expires_at' => null,
            ]);

            return redirect('/login')->with('status', 'Password reset successfully');
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP']);
    }
}

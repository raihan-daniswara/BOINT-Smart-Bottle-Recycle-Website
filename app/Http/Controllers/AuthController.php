<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Http\Request;
use App\Models\UserOtpAttempt;
use Illuminate\Support\Carbon;
use Brevo\Client\Configuration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Brevo\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Validator;
use Brevo\Client\Api\TransactionalEmailsApi;

class AuthController extends Controller
{
    function register(){
        return view('auth.register');
    }

    public function sendOtp(Request $request){

        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $email = $request->email;

        // Check if email is already used
        if (User::where('email', $email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah digunakan.',
            ], 422);
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(2);

        // Store OTP in the database
        UserOtp::updateOrCreate(
            ['email' => $email],
            ['otp' => $otp, 'expires_at' => $expiresAt]
        );

        // Send OTP using Brevo SDK
        try {
            $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', config('services.brevo.api_key'));
            $apiInstance = new TransactionalEmailsApi(new GuzzleClient(), $config);
            $html = view('auth.otp', compact('otp'))->render();
            $sendSmtpEmail = new SendSmtpEmail([
                'to' => [['email' => $email]],
                'sender' => [
                    'name' => 'Boint',
                    'email' => 'no-reply@rimou.site'
                ],
                'subject' => 'Kode OTP Kamu',
                'htmlContent' => $html
            ]);

            $apiInstance->sendTransacEmail($sendSmtpEmail);
            Log::info('OTP sent successfully', ['email' => $email]);

            return response()->json([
                'success' => true,
                'message' => 'Kode OTP telah dikirim ke email Anda.',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim OTP: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function verifyOtp(Request $request){
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $email = $request->email;
        $otp = $request->otp;

        // Store the OTP attempt
        UserOtpAttempt::updateOrCreate(
            ['email' => $email],
            [
                'otp' => $otp,
                'submitted_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'OTP telah disimpan.',
        ]);
    }

    public function register_post(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:20|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $email = $request->email;
        $otp = $request->otp;

        // Check if OTP matches and is valid
        $otpRecord = UserOtp::where('email', $email)
            ->where('otp', $otp)
            ->where('expires_at', '>=', now())
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'OTP salah atau telah kedaluwarsa.'])->withInput();
        }

        // Create user
        $user = User::create([
            'username' => $request->username,
            'email' => $email,
            'password' => Hash::make($request->password),
        ]);

        // Clean up OTP and attempt records
        UserOtp::where('email', $email)->delete();
        UserOtpAttempt::where('email', $email)->delete();

        return redirect()->route('login')->with('success', 'Registrasi berhasil.');
    }

    function login(){
        return view('auth.login');
    }

    function login_post(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|max:255',
        ]);

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            $user = Auth::user();
            if($user->role === "admin"){
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Login Success');
            }else{
                return redirect()->intended(route('dashboard'))->with('success', 'Login Success');
            }
        }

        return redirect()->back()->withInput()->withErrors(['Email or Password is Incorrect']);
    }

    function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with("success", "Sign Out Success");
    }
}

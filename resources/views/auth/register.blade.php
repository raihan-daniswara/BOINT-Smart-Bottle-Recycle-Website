<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Boint - Plastic Bottle Point</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="{{ asset('src/styles.css') }}" />
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body>
<div class="flex items-center justify-center min-h-screen p-4 bg-gradient-to-br from-blue-50 via-blue-300 to-sky-300">
    <div class="w-full max-w-md">
        <div class="mb-8 text-center">
            <img src="{{ asset('asset/logoBoint.png') }}" alt="Logo Boint" class="object-contain w-20 h-20 mx-auto" loading="lazy" />
            <h1 class="mb-2 text-3xl font-bold text-gray-900">Boint</h1>
            <p class="text-gray-700">Plastic Bottle Point</p>
        </div>
        <div class="p-8 border shadow-xl bg-white/70 backdrop-blur-md rounded-2xl border-white/20">
            <div class="mb-6">
                <h2 class="mb-2 text-2xl font-bold text-gray-900">Get Started</h2>
                <p class="text-gray-600">Sign up to create new account</p>
            </div>
            @if ($errors->any())
                <div class="flex items-center gap-3 p-4 mb-6 border rounded-lg bg-red-200/70 border-red-300/50" role="alert">
                    <i data-lucide="alert-circle" class="flex-shrink-0 w-5 h-5 mt-1 text-red-600"></i>
                    <ul class="pl-5 space-y-1 text-sm font-semibold text-red-700 list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('register.post') }}" method="post" class="mb-6 space-y-4" x-data="otpForm">
                @csrf
                <input type="hidden" name="otp" x-ref="otpInput" x-model="otp">
                <div>
                    <label for="username" class="block mb-2 text-sm font-medium text-gray-700">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="user" class="w-5 h-5 text-gray-700"></i>
                        </div>
                        <input id="username" name="username" type="text" placeholder="Enter your username" value="{{ old('username') }}"
                            class="box-border block w-full h-12 py-3 pl-10 pr-3 transition-colors border-2 rounded-lg border-gray-400/80 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            aria-label="Username" minlength="3" maxlength="20" required />
                    </div>
                </div>
                <div class="w-full space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                    <div class="relative flex items-center w-full max-w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="mail" class="w-5 h-5 text-gray-700"></i>
                        </div>
                        <input x-model="email" id="email" name="email" type="email" placeholder="Enter your email"
                            class="flex-1 h-12 py-3 pl-10 pr-3 transition-colors border-2 rounded-l-lg border-gray-400/80 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent max-w-[70%] box-border"
                            aria-label="Email address" required />
                        <button type="button" @click="sendOtp" :disabled="!canSendOtp || !showSendOtpButton"
                                class="w-[30%] h-12 px-2 py-3 text-sm font-semibold text-white transition-all bg-blue-600 rounded-r-lg hover:bg-blue-700 flex-shrink-0 box-border"
                                :class="{ 'opacity-50 cursor-not-allowed': !canSendOtp || !showSendOtpButton }">
                            <span x-text="showSendOtpButton ? (canSendOtp ? 'Send OTP' : `Wait ${countdown}s`) : 'Done'"></span>
                        </button>
                    </div>
                    <template x-if="showOtpInput">
                        <div class="flex mt-2 space-x-2">
                            <input x-model="otp" type="text" maxlength="6" placeholder="Enter OTP"
                                class="flex-1 h-12 px-3 py-2 text-sm border-2 max-w-[70%] border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 box-border"
                                aria-label="OTP code" />
                            <button @click="verifyOtp" type="button"
                                    class="flex-1 h-12 px-3 py-2 text-sm font-semibold max-w-[30%] text-white bg-green-600 rounded-lg hover:bg-green-700 flex-shrink-0 box-border">
                                Verify
                            </button>
                        </div>
                    </template>
                    <div class="mt-1 text-sm">
                        <span x-text="statusMessage" :class="{ 'text-green-600': !error, 'text-red-500': error }"></span>
                    </div>
                </div>
                <div x-data="{ show: false }">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="lock" class="w-5 h-5 text-gray-700"></i>
                        </div>
                        <input :type="show ? 'text' : 'password'" name="password" placeholder="Enter your password"
                            class="box-border block w-full h-12 py-3 pl-10 pr-12 transition-colors border-2 rounded-lg border-gray-400/80 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            aria-label="Password" minlength="8" required />
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 hover:cursor-pointer">
                            <i :data-lucide="show ? 'eye-off' : 'eye'" class="w-5 h-5 text-gray-700 hover:text-gray-600"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" id="registerButton"
                        :disabled="!otpVerified"
                        :class="{ 'opacity-50 cursor-not-allowed': !otpVerified }"
                        class="box-border flex items-center justify-center w-full h-12 px-4 py-3 mt-6 text-sm font-semibold text-white transition-all bg-blue-600 rounded-lg hover:bg-blue-700 hover:border-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Sign Up to Create Account
                </button>
            </form>
            <div class="flex items-center gap-4 mb-6">
                <div class="flex-grow border-t border-gray-400"></div>
                <span class="text-sm text-gray-500">Or sign in to continue</span>
                <div class="flex-grow border-t border-gray-400"></div>
            </div>
            <a href="{{ route('login') }}"
               class="box-border flex items-center justify-center w-full h-12 px-4 py-2 text-sm font-semibold text-white transition-all rounded-lg bg-cyan-500 hover:bg-cyan-600 focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2">
                Sign In with Email
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('otpForm', () => ({
            email: '',
            otp: '',
            statusMessage: '',
            canSendOtp: true,
            otpVerified: false,
            countdown: 60,
            showSendOtpButton: true,
            showOtpInput: false,
            loading: false,
            error: false,
            sendOtp() {
                if (!this.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)) {
                    this.statusMessage = 'Masukkan email yang valid.';
                    this.error = true;
                    return;
                }
                if (!this.canSendOtp || !this.showSendOtpButton) {
                    this.statusMessage = `Tunggu ${this.countdown} detik sebelum mengirim ulang.`;
                    this.error = true;
                    return;
                }
                this.loading = true;
                this.statusMessage = 'Mengirim OTP...';
                fetch('{{ route('send.otp') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ email: this.email })
                })
                .then(res => res.json())
                .then(data => {
                    this.loading = false;
                    if (data.success) {
                        this.statusMessage = 'OTP telah dikirim ke email Anda.';
                        this.showOtpInput = true;
                        this.error = false;
                        this.canSendOtp = false;
                        this.countdown = 60;
                        this.startCountdown();
                    } else {
                        this.statusMessage = data.message || 'Gagal mengirim OTP.';
                        this.error = true;
                    }
                })
                .catch(() => {
                    this.loading = false;
                    this.statusMessage = 'Kesalahan jaringan. Coba lagi.';
                    this.error = true;
                });
            },
            verifyOtp() {
                if (!this.otp || this.otp.length !== 6) {
                    this.statusMessage = 'OTP harus 6 digit.';
                    this.error = true;
                    return;
                }
                this.loading = true;
                this.statusMessage = 'Menyimpan OTP...';
                fetch('{{ route('verify.otp') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ email: this.email, otp: this.otp })
                })
                .then(res => res.json())
                .then(data => {
                    this.loading = false;
                    if (data.success) {
                        this.statusMessage = 'OTP telah disimpan.';
                        this.showOtpInput = false;
                        this.showSendOtpButton = false;
                        this.error = false;
                        this.otpVerified = true;
                        this.$refs.otpInput.value = this.otp; // Persist OTP in hidden input
                    } else {
                        this.statusMessage = data.message || 'Gagal menyimpan OTP.';
                        this.error = true;
                    }
                })
                .catch(err => {
                    this.loading = false;
                    this.statusMessage = 'Kesalahan jaringan: ' + err.message;
                    this.error = true;
                });
            },
            startCountdown() {
                let interval = setInterval(() => {
                    this.countdown--;
                    if (this.countdown <= 0) {
                        this.canSendOtp = true;
                        clearInterval(interval);
                    }
                }, 1000);
            }
        }));
    });
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
</body>
</html>
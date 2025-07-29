<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Boint - Plastic Bottle Point</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="./src/styles.css" />
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body>
    <div class="flex items-center justify-center min-h-screen p-4 bg-gradient-to-br from-blue-50 via-blue-300 to-sky-300">
        <div class="w-full max-w-md">
            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center">
                    <img src="{{ asset('asset/logoBoint.png') }}" alt="Logo Boint" class="object-contain w-20 h-20" />
                </div>
                <h1 class="mb-2 text-3xl font-bold text-gray-900">Boint</h1>
                <p class="text-gray-700">Plastic Bottle Point</p>
            </div>
            <div class="p-8 border shadow-xl bg-white/70 backdrop-blur-md rounded-2xl border-white/20">
                <div class="mb-6">
                    <h2 class="mb-2 text-2xl font-bold text-gray-900">Welcome Back</h2>
                    <p class="text-gray-600">Sign in to continue</p>
                </div>
                @if ($errors->any())
                <div class="flex items-center gap-3 p-4 mb-6 border rounded-lg bg-red-200/70 border-red-300/50">
                    <i data-lucide="alert-circle" class="flex-shrink-0 w-5 h-5 mt-1 text-red-600"></i>
                    <ul class="pl-5 space-y-1 text-sm font-semibold text-red-700 list-disc" id="errorText">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if (session('success'))
                <div class="flex items-center gap-3 p-4 mb-6 border rounded-lg bg-green-200/70 border-green-300/70">
                    <i data-lucide="check-circle" class="flex-shrink-0 w-5 h-5 mt-1 text-green-600"></i>
                    <ul class="pl-5 space-y-1 text-sm font-semibold text-green-700 list-disc" id="errorText">
                        <li>{{ session('success') }}</li>
                    </ul>
                </div>
                @endif
                <form action="{{ route('login.post') }}" method="post" class="mb-6 space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="mail" class="w-5 h-5 text-gray-700"></i>
                            </div>
                            <input id="email" name="email" type="email" placeholder="Enter your email" value="{{ old('email') }}"
                                class="block w-full h-12 py-3 pl-10 pr-3 transition-colors border-2 rounded-lg border-gray-400/80 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent box-border"
                                aria-label="Email address" required />
                        </div>
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="lock" class="w-5 h-5 text-gray-700"></i>
                            </div>
                            <input id="password" name="password" type="password" placeholder="Enter your password"
                                class="block w-full h-12 py-3 pl-10 pr-12 transition-colors border-2 rounded-lg border-gray-400/80 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent box-border"
                                aria-label="Password" minlength="8" required />
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i data-lucide="eye" class="w-5 h-5 text-gray-700 hover:text-gray-600"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" id="loginButton"
                        class="flex items-center justify-center w-full h-12 px-4 py-3 mt-6 text-sm font-medium text-white transition-colors bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 box-border">
                        <span id="loginButtonText">Sign In with Email</span>
                    </button>
                </form>
                <div class="flex items-center gap-4 mb-6">
                    <div class="flex-grow border-t border-gray-400"></div>
                    <span class="text-sm text-gray-500">Or sign up to create an account</span>
                    <div class="flex-grow border-t border-gray-400"></div>
                </div>
                <a href="{{ url('/register') }}"
                    class="flex items-center justify-center w-full h-12 px-4 py-3 text-sm font-medium text-white transition-colors border border-transparent rounded-lg shadow-sm bg-cyan-500 hover:bg-cyan-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 box-border">
                    <span>Sign Up to Create New Account</span>
                </a>
            </div>
        </div>
    </div>
    <script>
        lucide.createIcons();
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = togglePassword.querySelector('i');
        togglePassword.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            eyeIcon.setAttribute('data-lucide', isHidden ? 'eye-off' : 'eye');
            lucide.createIcons();
        });
    </script>
</body>
</html>
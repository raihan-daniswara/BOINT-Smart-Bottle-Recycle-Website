<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Boint - Welcome to Plastic Bottle Point</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>

<body>
    <!-- Main Container -->
    <div class="flex items-center justify-center min-h-screen p-4 bg-gradient-to-br from-blue-50 via-blue-300 to-sky-300">
        <div class="w-full max-w-md">
            <!-- Logo and Title -->
            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center">
                    <img src="{{ asset('asset/logoBoint.png') }}" alt="Logo Boint" class="object-contain w-20 h-20" />
                </div>
                <h1 class="mb-2 text-3xl font-bold text-gray-900">Selamat Datang di Boint</h1>
                <p class="text-gray-700">Plastic Bottle Point - Tukar Botol Plastikmu Jadi Poin!</p>
            </div>

            <!-- Card Container -->
            <div class="p-8 shadow-2xl bg-white/70 backdrop-blur-md rounded-2xl">
                <!-- Heading -->
                <div class="mb-6">
                    <h2 class="mb-2 text-2xl font-bold text-gray-900">Selamat Datang!</h2>
                    <p class="text-gray-600">Bergabunglah dengan kami untuk membantu lingkungan dengan menukar botol plastik menjadi poin yang bisa ditukar dengan hadiah menarik.</p>
                </div>

                <!-- Features Section -->
                <div class="mb-6 space-y-4">
                    <div class="flex items-start gap-3">
                        <i data-lucide="recycle" class="w-6 h-6 mt-1 text-blue-600"></i>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Ramah Lingkungan</h3>
                            <p class="text-sm text-gray-600">Kumpulkan botol plastik dan bantu kurangi sampah.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i data-lucide="gift" class="w-6 h-6 mt-1 text-blue-600"></i>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Hadiah Menarik</h3>
                            <p class="text-sm text-gray-600">Tukar poinmu dengan hadiah yang bermanfaat.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i data-lucide="users" class="w-6 h-6 mt-1 text-blue-600"></i>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Komunitas Hijau</h3>
                            <p class="text-sm text-gray-600">Jadilah bagian dari komunitas yang peduli lingkungan.</p>
                        </div>
                    </div>
                </div>

                <!-- Call to Action Buttons -->
                <div class="space-y-4">
                    <a href="{{ route('login') }}"
                        class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition-colors bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span>Masuk ke Akun Anda</span>
                    </a>
                    <a href="{{ route('register') }}"
                        class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-gray-700 transition-colors border border-transparent rounded-lg shadow-sm bg-cyan-500 hover:bg-cyan-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                        <span>Daftar Sekarang</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Script -->
    <script>
        lucide.createIcons();
    </script>
</body>

</html>
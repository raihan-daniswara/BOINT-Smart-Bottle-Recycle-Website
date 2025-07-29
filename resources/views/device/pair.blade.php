<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Pairing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-xl shadow-lg max-w-md w-full">
        <h1 class="text-2xl font-bold mb-4 text-gray-800">Konfirmasi Pairing</h1>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="/pair/confirm" class="space-y-4">
            @csrf
            <input type="hidden" name="device_id" value="{{ $device->id }}">
            <input type="hidden" name="token" value="{{ $token }}">

            <p class="text-gray-700">
                Apakah Anda ingin pairing dengan device <strong class="text-indigo-600">{{ $device->name }}</strong>?
            </p>

            <button type="submit"
                class="w-full bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-indigo-700 transition duration-150">
                Ya, Pasangkan
            </button>
        </form>
    </div>

</body>
</html>

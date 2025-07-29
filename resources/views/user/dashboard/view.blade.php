@extends('layouts.user')
@section('title', 'User Dashboard')
@section('content')

<!-- Swiper CSS -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"
/>

<style>
  @keyframes scan {
    0% {
      top: 0%;
    }
    50% {
      top: 98%;
    }
    100% {
      top: 0%;
    }
  }

  .animate-scan-line {
    animation: scan 2s infinite ease-in-out;
  }
</style>

<!-- Modal Scan QR dengan Alpine.js -->
<div 
  x-data="{ open: false }"
  x-show="open"
  x-cloak
  @open-scanner.window="open = true"
  @keydown.escape.window="open = false"
  x-effect="open ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden')"
  x-transition:enter="transition ease-out duration-200"
  x-transition:enter-start="opacity-0 scale-90"
  x-transition:enter-end="opacity-100 scale-100"
  x-transition:leave="transition ease-in duration-150"
  x-transition:leave-start="opacity-100 scale-100"
  x-transition:leave-end="opacity-0 scale-90"
  class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm"
  style="display: none;"
>
  <div class="bg-white rounded-xl p-5 w-[90%] max-w-md relative shadow-2xl">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-lg font-bold text-gray-800">Scan QR Code</h2>
      <button @click="open = false; closeScanner()" class="text-gray-400 hover:text-red-500">
        <i data-lucide="x" class="w-6 h-6"></i>
      </button>
    </div>

    <!-- QR Reader Box -->
    <div class="relative border-4 border-white shadow-xl rounded-lg overflow-hidden w-full h-64">
      <!-- Reader -->
      <div id="reader" class="absolute inset-0 w-full h-full z-10 bg-white/90"></div>

      <!-- Shadow di luar kotak scan -->
      <div class="absolute inset-0 z-20 pointer-events-none">
        <!-- Atas -->
        <div class="absolute top-0 left-0 w-full h-[calc(50%-80px)] bg-black/50"></div>
        <!-- Bawah -->
        <div class="absolute bottom-0 left-0 w-full h-[calc(50%-80px)] bg-black/50"></div>
        <!-- Kiri -->
        <div class="absolute top-[calc(50%-80px)] left-0 w-[calc(50%-80px)] h-40 bg-black/50"></div>
        <!-- Kanan -->
        <div class="absolute top-[calc(50%-80px)] right-0 w-[calc(50%-80px)] h-40 bg-black/50"></div>
      </div>

      <!-- Scanner -->
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-30 w-40 h-40">
        <!-- Garis scan -->
        <div class="absolute top-0 left-0 w-full h-1 bg-white/50 animate-scan-line"></div>

        <!-- Corner Borders -->
        <!-- Top Left -->
        <div class="absolute top-0 left-0 w-6 h-1 bg-white"></div>
        <div class="absolute top-0 left-0 w-1 h-6 bg-white"></div>

        <!-- Top Right -->
        <div class="absolute top-0 right-0 w-6 h-1 bg-white"></div>
        <div class="absolute top-0 right-0 w-1 h-6 bg-white"></div>

        <!-- Bottom Left -->
        <div class="absolute bottom-0 left-0 w-6 h-1 bg-white"></div>
        <div class="absolute bottom-0 left-0 w-1 h-6 bg-white"></div>

        <!-- Bottom Right -->
        <div class="absolute bottom-0 right-0 w-6 h-1 bg-white"></div>
        <div class="absolute bottom-0 right-0 w-1 h-6 bg-white"></div>
      </div>

    </div>
    <p class="text-center text-gray-600 mt-4 text-sm">Arahkan kamera ke QR Code</p>
  </div>
</div>

<div class="block min-[1600px]:mx-[20%] min-[1100px]:mx-[10%]  md:my-30">
  <div>
    <div class="mx-4 mb-5">
      {{-- Profile Card Mobile --}}
      <div class="relative mx-4 my-6">
        <div class="rounded-3xl bg-white/70 shadow-lg p-6 backdrop-blur-md">
          <div class="flex flex-col items-center justify-center">
            <div class="relative -mt-16">
              @if (Auth::user()->profile_photo)
                <img 
                  src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                  alt="Profile Photo" 
                  class="rounded-full border-4 border-white shadow-xl w-28 h-28 object-cover"
                />
              @else
                <div class="w-28 h-28 rounded-full border-4 border-white shadow-xl bg-blue-600 flex items-center justify-center text-white text-3xl font-bold">
                  {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
                </div>
              @endif
            </div>
            <div class="mt-4 text-center">
              <h2 class="text-lg font-semibold text-gray-900">Welcome Back,</h2>
              <p class="text-xl font-bold text-blue-700">{{ Auth::user()->username }}</p>
              <p class="text-sm text-gray-500 mt-3">Let’s recycle more bottles today!</p>
            </div>

            {{-- Stats Section --}}
            <div class="grid grid-cols-2 gap-4 mt-6 w-full">
              {{-- Bottle Stored --}}
              <div class="flex flex-col items-center justify-center px-4 py-5 bg-gradient-to-br from-green-200 to-green-400 rounded-xl shadow-md min-w-0">
                <i data-lucide="bottle-wine" class="w-8 h-8 text-green-600 mb-2"></i>
                <p class="text-sm text-gray-600 font-semibold whitespace-nowrap">Bottle Stored</p>
                <p class="text-lg font-bold text-gray-900">{{ Auth::user()->bottle_stored ?? 0 }}</p>
              </div>

              {{-- Points Earned --}}
              <div class="flex flex-col items-center justify-center px-4 py-5 bg-gradient-to-br from-purple-200 to-purple-400 rounded-xl shadow-md min-w-0">
                <i data-lucide="coins" class="w-8 h-8 text-purple-600 mb-2"></i>
                <p class="text-sm text-gray-600 font-semibold whitespace-nowrap">Points Earned</p>
                <p class="text-lg font-bold text-gray-900">{{ Auth::user()->total_points ?? 0 }}</p>
              </div>
            </div>
            {{-- End Stats --}}
          </div>
        </div>
      </div>
      <div class="mx-4 my-6">
        <h3 class="text-lg font-semibold mb-3 text-gray-900">What would you like to do?</h3>

        <div class="grid grid-cols-2 gap-4">
          <!-- Deposit Bottles -->
          <a href="{{ route('submissions') }}" class="flex flex-col items-center justify-center bg-gradient-to-br from-orange-200 to-orange-400 hover:bg-orange-200 rounded-xl p-4 shadow-md transition-all">
            <i data-lucide="receipt-text" class="w-8 h-8 text-orange-800 m-2"></i>
            <p class="text-sm font-medium text-center text-orange-800">My Submission</p>
          </a>
          <!-- Tombol Scan QR -->
          <button class="flex flex-col items-center justify-center bg-gradient-to-br from-blue-200 to-blue-400 hover:bg-blue-200 rounded-xl p-4 shadow-md transition-all" @click="$dispatch('open-scanner')" >
              <i data-lucide="scan-line" class="w-8 h-8 text-blue-800 m-2"></i>
              <p class="text-sm font-medium text-center text-blue-800">Scan QR Code</p>
          </button>
          <!-- Redeem Points -->
          <a href="{{ route('rewards') }}" class="flex flex-col items-center justify-center bg-gradient-to-br from-indigo-200 to-indigo-400 hover:bg-indigo-200 rounded-xl p-4 shadow-md transition-all col-span-2">
              <i data-lucide="gift" class="w-8 h-8 text-indigo-800 m-2"></i>
            <p class="text-sm font-medium text-center text-indigo-800">Redeem Points</p>
          </a>
        </div>
      </div>
      <div class="mx-4 my-6  overflow-x-clip">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">My Recent Submissions</h3>
        <div class="swiper recentSubmissionSwiper !overflow-visible">
          <div class="swiper-wrapper">  
            @forelse ($recent_submissions as $submission)
              @php
                $statusColors = [
                  'pending' => ['bg' => 'yellow-50', 'text' => 'yellow-700', 'icon' => 'clock', 'label' => 'Pending'],
                  'failed' => ['bg' => 'red-50', 'text' => 'red-700', 'icon' => 'ban', 'label' => 'Failed'],
                  'completed' => ['bg' => 'green-50', 'text' => 'green-700', 'icon' => 'check-circle', 'label' => 'Completed'],
                ];
                $color = $statusColors[$submission->status] ?? ['bg' => 'gray-50', 'text' => 'gray-700', 'icon' => 'help-circle', 'label' => ucfirst($submission->status)];
              @endphp

              <div class="swiper-slide">
                <div class="p-5 bg-{{ $color['bg'] }} rounded-2xl shadow-md hover:shadow-lg transition">
                  <div class="flex justify-between items-start gap-4">
                    <div class="flex-1">
                      <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($submission->created_at)->format('d M Y, H:i:s') }}</p>
                      <p class="mt-1 text-lg font-semibold text-gray-800">{{ $submission->bottle_count }} Bottle</p>
                      <p class="text-sm text-green-600 font-medium">+{{ $submission->points_earned }} points</p>
                    </div>
                    <div class="flex flex-col items-end">
                      <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold text-{{ $color['text'] }}">
                        <i data-lucide="{{ $color['icon'] }}" class="w-4 h-4"></i>
                        {{ $color['label'] }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            @empty
                <div class="bg-white p-4 rounded-xl shadow-sm text-center text-gray-500 w-full">
                  No Submission yet.
              </div>
            @endforelse
          <!-- Dots -->
          {{-- Swiper Pagination di bawah semua card --}}
        </div>
        <div class="">
            <div class="swiper-pagination"></div>
        </div>
        </div>       
      </div>
    </div>
  </div>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/html5-qrcode@2.3.7/html5-qrcode.min.js"></script>
<script>
  let html5QrCode = null;
  let scannerRunning = false;

  function closeScanner() {
    if (html5QrCode && scannerRunning) {
      html5QrCode.stop()
        .then(() => {
          html5QrCode.clear();
          scannerRunning = false;
        })
        .catch(err => {
          console.warn("Scanner was not running:", err);
          scannerRunning = false;
        });
    }
  }

  async function requestCameraPermissionAndStart() {
    try {
      if (!navigator.permissions) {
        // Fallback for browsers that don't support permissions API
        return startScanner();
      }

      const permission = await navigator.permissions.query({ name: 'camera' });

      if (permission.state === 'denied') {
        alert('Camera access has been denied. Please enable camera permissions in your browser settings.');
        return;
      }

      if (permission.state === 'prompt') {
        // Trigger permission prompt
        await navigator.mediaDevices.getUserMedia({ video: true });
      }

      startScanner(); // Permission granted, proceed
    } catch (error) {
      alert('Unable to access camera. Please allow camera permission to proceed.');
      console.error("Camera access error:", error);
    }
  }

  function startScanner() {
    setTimeout(() => {
      const readerElement = document.getElementById("reader");
      if (!readerElement) {
        console.error("Reader element not found.");
        return;
      }

      if (!html5QrCode) {
        html5QrCode = new Html5Qrcode("reader");
      }

      if (!scannerRunning) {
        Html5Qrcode.getCameras().then(devices => {
          if (devices.length) {
            html5QrCode.start(
              { facingMode: "environment" },
              { fps: 10, 
                aspectRatio: 1.0 
              },
              qrCodeMessage => {
                sendQRToServer(qrCodeMessage);
                closeScanner(); // Close on successful scan
                document.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape' }));
              }
            ).then(() => {
              scannerRunning = true;
            }).catch(err => {
              alert("Failed to start the camera.");
              console.error(err);
              scannerRunning = false;
            });
          } else {
            alert("No cameras found on this device.");
          }
        }).catch(err => {
          alert("Unable to access the camera.");
          console.error(err);
        });
      }

      lucide.createIcons();
    }, 300);
  }

  window.addEventListener('open-scanner', () => {
    requestCameraPermissionAndStart();
  });

  function sendQRToServer(content) {
  fetch("{{ url('/dashboard/scan-result') }}", {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ qr_content: content })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert("Pairing successful for user: " + data.username);
    } else {
      alert((data.message || "Invalid QR Code."));
    }

    // Tutup modal setelah proses berhasil atau gagal
    closeScanner();
    document.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape' }));
    location.reload(); // Pindahkan reload ke luar if agar tetap refresh
  })
  .catch(err => {
    alert("An error occurred while sending QR data.");
    console.error(err);
    
    // Tutup modal jika terjadi error
    closeScanner();
    document.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape' }));
  });
}


  document.addEventListener("DOMContentLoaded", function () {
    const swiper = new Swiper(".recentSubmissionSwiper", {
      slidesPerView: 1,
      spaceBetween: 16,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      breakpoints: {
        780: {
          slidesPerView: 1.5,
        },
        1100: {
          slidesPerView: 2,
        },
        1400: {
          slidesPerView: 2.5,
        },
        1852: {
          slidesPerView: 3,
        },
      },
    });
  });
</script>
@endsection

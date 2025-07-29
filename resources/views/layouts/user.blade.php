<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Boint - @yield('title', 'User Panel')</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  @vite('resources/css/app.css')
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
  <!-- Alpine.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <style>[x-cloak] { display: none !important; }</style>
</head>
<body x-data="{ isSidebarOpen: false, isMobile: window.innerWidth < 768 }" x-init="lucide.createIcons(); $watch('isMobile', () => isMobile = window.innerWidth < 768)" @resize.window="isMobile = window.innerWidth < 768">
  <!-- User Dashboard -->
  <div class="flex min-h-screen md:h-screen bg-gradient-to-br from-blue-50 to-blue-100">
    <!-- Mobile Hamburger Button -->
    <button x-cloak x-show="isMobile" @click="isSidebarOpen = true" class="fixed z-50 p-2 rounded-md shadow-lg top-4 left-4 bg-white/40 backdrop-blur-md border-xl border-white/20">
      <i data-lucide="menu" class="w-6 h-6 text-gray-700"></i>
    </button>

    <!-- Sidebar -->
    <div x-cloak :class="{ '-translate-x-full': !isSidebarOpen && isMobile }" class="fixed inset-y-0 left-0 flex flex-col w-64 h-full transition-transform duration-300 ease-in-out transform shadow-lg z-100 bg-white/40 backdrop-blur-md border-xl border-white/20 md:transform-none md:static md:w-64">
      <div class="flex items-center justify-between p-6 border-b-2 border-indigo-200">
        <h1 class="flex items-center text-xl font-bold text-gray-900">
          <div class="flex items-center justify-center w-8 h-8 mr-3 bg-blue-600 rounded-lg">
            <i data-lucide="monitor" class="w-5 h-5 text-white"></i>
          </div>
          <p>Bottle Point</p>
        </h1>
        <button x-show="isMobile" @click="isSidebarOpen = false" class="p-2 md:hidden">
          <i data-lucide="x" class="w-6 h-6 text-gray-700"></i>
        </button>
      </div>

      @php
        $current = request()->segment(1);
      @endphp

      <nav class="flex-1 p-4 overflow-y-auto">
        <ul class="space-y-2">
          <li>
            <a href="{{ route('dashboard') }}"
               class="nav-item w-full flex items-center px-3 py-2 text-left rounded-lg transition-colors 
                      {{ $current == 'dashboard' ? 'bg-blue-50/20 text-blue-700 border-r-3 backdrop-blur-md border-blue-600' : 'text-gray-700 hover:bg-gray-50/20 hover:backdrop-blur-md' }}">
              <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3 {{ $current == 'dashboard' ? 'text-blue-600' : 'text-gray-500' }}"></i>
              Dashboard
            </a>
          </li>
          <li>
            <a href="{{ route('submissions') }}"
               class="nav-item w-full flex items-center px-3 py-2 text-left rounded-lg transition-colors 
                      {{ $current == 'submissions' ? 'bg-blue-50/20 text-blue-700 border-r-3 backdrop-blur-md border-blue-600' : 'text-gray-700 hover:bg-gray-50/20 hover:backdrop-blur-md' }}">
              <i data-lucide="bottle-wine" class="w-5 h-5 mr-3 {{ $current == 'submissions' ? 'text-blue-600' : 'text-gray-500' }}"></i>
              Submissions
            </a>
          </li>
          <li>
            <a href="{{ route('devices') }}"
               class="nav-item w-full flex items-center px-3 py-2 text-left rounded-lg transition-colors 
                      {{ $current == 'devices' ? 'bg-blue-50/20 text-blue-700 border-r-3 backdrop-blur-md border-blue-600' : 'text-gray-700 hover:bg-gray-50/20 hover:backdrop-blur-md' }}">
              <i data-lucide="smartphone" class="w-5 h-5 mr-3 {{ $current == 'devices' ? 'text-blue-600' : 'text-gray-500' }}"></i>
              Devices
            </a>
          </li>
          <li>
            <a href="{{ route('profile') }}"
               class="nav-item w-full flex items-center px-3 py-2 text-left rounded-lg transition-colors 
                      {{ $current == 'profile' ? 'bg-blue-50/20 text-blue-700 border-r-3 backdrop-blur-md border-blue-600' : 'text-gray-700 hover:bg-gray-50/20 hover:backdrop-blur-md' }}">
              <i data-lucide="user" class="w-5 h-5 mr-3 {{ $current == 'profile' ? 'text-blue-600' : 'text-gray-500' }}"></i>
              My Profile
            </a>
          </li>
          <li>
            <a href="{{ route('redeems') }}"
               class="nav-item w-full flex items-center px-3 py-2 text-left rounded-lg transition-colors 
                      {{ $current == 'redeem' ? 'bg-blue-50/20 text-blue-700 border-r-3 backdrop-blur-md border-blue-600' : 'text-gray-700 hover:bg-gray-50/20 hover:backdrop-blur-md' }}">
              <i data-lucide="ticket" class="w-5 h-5 mr-3 {{ $current == 'redeem' ? 'text-blue-600' : 'text-gray-500' }}"></i>
              Redeemed Rewards
            </a>
          </li>
          <li>
            <a href="{{ route('rewards') }}"
               class="nav-item w-full flex items-center px-3 py-2 text-left rounded-lg transition-colors 
                      {{ $current == 'rewards' ? 'bg-blue-50/20 text-blue-700 border-r-3 backdrop-blur-md border-blue-600' : 'text-gray-700 hover:bg-gray-50/20 hover:backdrop-blur-md' }}">
              <i data-lucide="gift" class="w-5 h-5 mr-3 {{ $current == 'rewards' ? 'text-blue-600' : 'text-gray-500' }}"></i>
              Redeem Points
            </a>
          </li>
          <li>
            <a href="{{ route('leaderboard') }}"
               class="nav-item w-full flex items-center px-3 py-2 text-left rounded-lg transition-colors 
                      {{ $current == 'leaderboard' ? 'bg-blue-50/20 text-blue-700 border-r-3 backdrop-blur-md border-blue-600' : 'text-gray-700 hover:bg-gray-50/20 hover:backdrop-blur-md' }}">
              <i data-lucide="trophy" class="w-5 h-5 mr-3 {{ $current == 'leaderboard' ? 'text-blue-600' : 'text-gray-500' }}"></i>
              Leaderboard
            </a>
          </li>
        </ul>
      </nav>

      <div class="p-4 space-y-2 border-t-2 border-indigo-200">
        @if (Auth::user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}" class="flex items-center w-full px-3 py-2 text-gray-700 transition-colors rounded-lg hover:bg-gray-50">
          <i data-lucide="wrench" class="w-5 h-5 mr-3 text-gray-500"></i>
          Switch to Admin View
        </a>
        @endif
        <form action="{{ route('auth.logout') }}" method="POST">
          @csrf
          <button type="submit" class="flex items-center w-full px-3 py-2 text-red-600 transition-colors rounded-lg hover:bg-red-100/30 hover:backdrop-blur-md">
            <i data-lucide="log-out" class="w-5 h-5 mr-3 text-red-500"></i>
            Sign Out
          </button>
        </form>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto" :class="{ 'pt-18': isMobile }">
      <div id="adminContent">
        @yield('content')
      </div>
    </div>
  </div>
</body>
</html>
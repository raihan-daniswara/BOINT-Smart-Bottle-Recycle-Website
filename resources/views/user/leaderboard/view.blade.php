@extends('layouts.user')
@section('title','Boint Leaderboard')
@section('content')
<div x-data="{ tab: 'bottle' }" class="space-y-6 mx-4 md:my-8 md:mx-8">
        <div class="mb-4">
            <h1 class="text-3xl font-bold text-blue-700 mb-2">Boint Leaderboard</h1>
            <p class="text-gray-600 mt-2">Top 5 user who submitting bottles to our vending machines.</p>
        </div>
    
        <div class="grid grid-cols-1 min-[1175px]:grid-cols-2 gap-6">
            <!-- Top Bottle Card -->
            <div @click="tab = 'bottle'"
                class="p-6 rounded-2xl shadow-md border-l-4 transition-all duration-200 flex items-center space-x-4 hover:bg-green-50 hover:cursor-pointer"
                :class="tab === 'bottle' ? 'border-green-500 bg-green-50' : 'border-gray-200 bg-white'">
                <div class="p-3 rounded-full bg-green-100">
                    <i data-lucide="bottle-wine" class="w-6 h-6 text-green-700"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold">Top Bottle Submission</h3>
                    <p class="text-sm text-gray-500">Most bottles submitted to Boint machines</p>
                </div>
            </div>
        
            <!-- Top Point Card -->
            <div @click="tab = 'point'"
                class="p-6 rounded-2xl shadow-md border-l-4 transition-all duration-200 flex items-center space-x-4 hover:bg-purple-50 hover:cursor-pointer"
                :class="tab === 'point' ? 'border-purple-500 bg-purple-50' : 'border-gray-200 bg-white'">
                <div class="p-3 rounded-full bg-purple-100">
                    <i data-lucide="coins" class="w-6 h-6 text-purple-700"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold">Top Point Holder</h3>
                    <p class="text-sm text-gray-500">Most total points holders</p>
                </div>
            </div>
        </div>
    
    
        <!-- Leaderboard Panel -->
        <div class="bg-white shadow-md rounded-2xl overflow-hidden border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-300">
            <h2 class="text-xl font-semibold text-gray-800" x-text="tab === 'bottle' ? 'Top 5 Bottle Submission' : 'Top 5 Point Holder'"></h2>
            </div>
    
            <!-- Top Bottle List -->
            <div class="divide-y divide-gray-200" x-show="tab === 'bottle'">
            @foreach ($topBottles as $index => $user)
                <div class="px-6 py-5 flex items-center gap-4 hover:bg-gray-50 transition">
                <div class="w-6 text-center">
                    @if($index === 0)
                    <i data-lucide="trophy" class="w-5 h-5 text-yellow-500"></i>
                    @elseif($index === 1)
                    <i data-lucide="medal" class="w-5 h-5 text-gray-400"></i>
                    @elseif($index === 2)
                    <i data-lucide="award" class="w-5 h-5 text-orange-500"></i>
                    @else
                    <span class="text-sm font-semibold text-gray-500">{{ $index + 1 }}</span>
                    @endif
                </div>
                <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->username) }}" 
                    alt="{{ $user->username }}" 
                    class="w-10 h-10 rounded-full object-cover" />
                <div class="flex-1">
                    <div class="font-medium text-gray-900">{{ $user->username }}</div>
                    <div class="text-sm text-gray-500"><span class="text-purple-500">{{ number_format($user->total_points) }}</span> total points</div>
                </div>
                <div class="text-right">
                    <div class="text-green-600 font-bold text-lg">{{ number_format($user->bottle_stored) }}</div>
                    <div class="text-xs text-gray-400">bottles stored</div>
                </div>
                </div>
            @endforeach
            </div>
    
            <!-- Top Points List -->
            <div class="divide-y divide-gray-200" x-show="tab === 'point'">
            @foreach ($topPoints as $index => $user)
                <div class="px-6 py-5 flex items-center gap-4 hover:bg-gray-50 transition">
                <div class="w-6 text-center">
                    @if($index === 0)
                    <i data-lucide="trophy" class="w-5 h-5 text-yellow-500"></i>
                    @elseif($index === 1)
                    <i data-lucide="medal" class="w-5 h-5 text-gray-400"></i>
                    @elseif($index === 2)
                    <i data-lucide="award" class="w-5 h-5 text-orange-500"></i>
                    @else
                    <span class="text-sm font-semibold text-gray-500">{{ $index + 1 }}</span>
                    @endif
                </div>
                <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->username) }}" 
                    alt="{{ $user->username }}" 
                    class="w-10 h-10 rounded-full object-cover" />
                <div class="flex-1">
                    <div class="font-medium text-gray-900">{{ $user->username }}</div>
                    <div class="text-sm text-gray-500"><span class="text-green-500">{{ number_format($user->bottle_stored) }}</span> bottles stored</div>
                </div>
                <div class="text-right">
                    <div class="text-purple-600 font-bold text-lg">{{ number_format($user->total_points) }}</div>
                    <div class="text-xs text-gray-400">total points</div>
                </div>
                </div>
            @endforeach
            </div>
        </div>
    </div>
</div>

@endsection

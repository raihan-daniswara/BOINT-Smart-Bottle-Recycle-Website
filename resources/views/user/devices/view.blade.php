@extends('layouts.user')
@section('content')
<!-- Welcome Section -->
<div class="mx-4 md:my-8 md:mx-8">
    <h2 class="mb-2 text-3xl font-bold text-blue-700">Device Status</h2>
    <p class="text-gray-600">Track all device status, activity, and storage</p>
<div class="mt-6">
  <div class="gap-4">
    @foreach ($devices as $device)
    <div class="p-4 mb-4 transition-all duration-200 bg-{{ $device->status === 0 ? 'red' : ($device->status === 1 ? 'green' : 'yellow') }}-50/70 border border-gray-200 rounded-xl bg-hover:border-gray-300 hover:shadow-md hover:-translate-y-1">
        <div class="flex items-start justify-between mb-3">
            <div class="flex items-center">
                    <i data-lucide="pc-case" class="w-8 h-8 mr-3 text-{{ $device->status === 0 ? 'red' : ($device->status === 1 ? 'green' : 'yellow') }}-600"></i>
                <div>
                    <h4 class="font-medium text-gray-900">{{ $device->name }}</h4>
                    <div class="flex items-center mt-1 text-sm text-gray-600">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                        {{ $device->location }}
                    </div>
                </div>
            </div>
            <div class="flex items-center">
                @if ($device->status === 0)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border bg-red-100 text-red-800 border-red-200">
                    <div class="w-2 h-2 mr-1 bg-red-500 rounded-full animate-pulse"></div>
                    <span class="capitalize">Offline</span>
                </span>
                @endif
                @if ($device->status === 1)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border bg-green-100 text-green-800 border-green-200">
                    <div class="w-2 h-2 mr-1 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="capitalize">Online</span>
                </span>
                @endif
                @if ($device->status === 2)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border bg-yellow-100 text-yellow-800 border-yellow-200">
                    <div class="w-2 h-2 mr-1 bg-yellow-500 rounded-full animate-pulse"></div>
                    <span class="capitalize">Maintenance</span>
                </span>
                @endif
            </div>
        </div>
        <div class="flex items-center justify-between mb-2 text-sm text-gray-600">
            <div class="flex items-center">
                <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
                Last activity: {{ \Carbon\Carbon::createFromTimestamp($device->last_active)->diffForHumans() }}
            </div>
            <div>
                Capacity: 
                @if ($device->capacity >= $device->max_capacity)
                    <span class="text-red-600">Full</span>
                @else
                    {{ $device->capacity }}/{{ $device->max_capacity }}
                @endif
            </div>
        </div>
        @php
            $capacityPercent = $device->max_capacity > 0
                ? round(($device->capacity / $device->max_capacity) * 100)
                : 0;
            $barColor = 'bg-gradient-to-r from-green-300 to-green-600';
            if ($capacityPercent >= 100) $barColor = 'bg-gradient-to-r from-red-700 to-red-900';
            elseif ($capacityPercent >= 70) $barColor = 'bg-gradient-to-r from-red-500 to-red-700';
            elseif ($capacityPercent >= 30) $barColor = 'bg-gradient-to-r from-yellow-500 to-yellow-600';
        @endphp
        <div class="w-full h-2 bg-gray-200 rounded-full">
            <div class="h-2 rounded-full {{ $barColor }} transition-all duration-500 ease-in-out"
                  style="width: {{ $capacityPercent <= 100 ? $capacityPercent : 100 }}%">
            </div>
        </div>
    </div> 
    @endforeach 
</div>
</div>
</div>
@endsection
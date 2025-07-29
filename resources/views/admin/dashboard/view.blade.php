@extends('layouts.admin')
@section('title','Admin Dashboard')
@section('content')

<!-- Welcome Section -->
<div class="mb-8">
    <h2 class="text-3xl font-bold text-blue-700 mb-2">Welcome back, {{ Auth::user()->username }}!</h2>
    <p class="text-gray-600">Track or manage any users and submissions</p>
</div>
<!-- Stats Cards -->
<div class="grid max-[947px]:grid-cols-1 gap-6 mb-8 min-[960px]:grid-cols-2 min-[1593px]:grid-cols-4">
    <div class="p-6 transition-shadow shadow-sm bg-gradient-to-br from-green-50 to-green-400 rounded-xl hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="mb-1 text-sm font-medium text-gray-600">Total Bottles Collected</p>
                <p id="total-bottles" class="text-2xl font-bold text-gray-900">
                    {{ $users->sum('bottle_stored') }}
                </p>
                <div class="flex items-center mt-2">
                    <span class="font-medium text-green-600 text-s">+<span id="last-total-bottles">{{ $bottlesLastWeek }}</span></span>
                    <span class="ml-1 text-gray-500 text-s">last 7 days</span>
                </div>
            </div>
            <div class="p-3 text-green-600 rounded-lg">
                <i data-lucide="recycle" class="w-12 h-12"></i>
            </div>
        </div>
    </div>

    <div class="p-6 transition-shadow shadow-sm bg-gradient-to-br from-purple-100 to-purple-400 rounded-xl hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="mb-1 text-sm font-medium text-gray-600">Points Distributed</p>
                <p id="total-points" class="text-2xl font-bold text-gray-900">
                    {{ number_format($pointsDistributed) }}
                </p>
                <div class="flex items-center mt-2">
                    <span class="font-medium text-green-600 text-s">+<span id="last-total-points">{{ $pointsLastWeek }}</span></span>
                    <span class="ml-1 text-gray-500 text-s">last 7 days</span>
                </div>
            </div>
            <div class="p-3 text-purple-600 rounded-lg">
                <i data-lucide="gift" class="w-12 h-12"></i>
            </div>
        </div>
    </div>
    <div class="p-6 transition-shadow shadow-sm bg-gradient-to-br from-sky-100 to-sky-400 rounded-xl hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="mb-1 text-sm font-medium text-gray-600">Total Unique Users</p>
                <p id="total-users" class="text-2xl font-bold text-gray-900">
                    {{ $users->count() }}
                </p>
                <div class="flex items-center mt-2">
                    <span class="font-medium text-green-600 text-s">+<span id="last-total-users">{{ $usersLastWeek }}</span></span>
                    <span class="ml-1 text-gray-500 text-s">last 7 days</span>
                </div>
            </div>
            <div class="p-3 text-blue-600 rounded-lg">
                <i data-lucide="users" class="w-12 h-12"></i>
            </div>
        </div>
    </div>


    <div class="p-6 transition-shadow shadow-sm bg-gradient-to-br from-orange-50 to-orange-300 rounded-xl hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="mb-1 text-sm font-medium text-gray-600">Vending Online</p>
                <p id="active-device" class="text-2xl font-bold text-gray-900">
                    {{ $devices->where('status', 1)->count() }}
                </p>
                <div class="items-center mt-2">
                    <span class="ml-1 text-gray-500 text-s">last activity:</span>
                    <span class="font-medium text-orange-600 text-s"id="last-activity">{{ $latestDevice?->last_active_human ?? 'Never Active' }}</span>
                </div>
            </div>
            <div class="p-3 text-orange-600 rounded-lg">
                <i data-lucide="pc-case" class="w-12 h-12"></i>
            </div>
        </div>
    </div>
</div>

<!-- Submissions Table -->
<div class="mb-8 overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Recent Submissions</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Submission Code</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Device</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Bottles</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Points</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($submissions as $submission)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $submission->submission_code }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ optional($submission->user)->username ?? 'Deleted User #' . $submission->user_deleted_id }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ optional($submission->device)->name ?? 'Deleted Device #' . $submission->device_deleted_id }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $submission->bottle_count }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-green-600">+{{ $submission->points_earned }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if ($submission->status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700">
                            <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
                            Pending
                            </span>
                        @endif
                        @if ($submission->status === 'failed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700">
                            <i data-lucide="ban" class="w-4 h-4 mr-1"></i>
                            Failed
                            </span>
                        @endif
                        @if ($submission->status === 'completed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">
                            <i data-lucide="circle-check-big" class="w-4 h-4 mr-1"></i>
                            Completed
                            </span>
                        @endif
                    </td> 
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    function animateCount(id, endValue, duration = 1000) {
        const el = document.getElementById(id);
        if (!el) return;
        
        let start = 0;
        const increment = endValue / (duration / 16); // asumsi 60fps
        const startTime = performance.now();

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const value = Math.floor(progress * endValue);

            el.textContent = value.toLocaleString();

            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                el.textContent = endValue.toLocaleString(); // pastikan angka akhir akurat
            }
        }

        requestAnimationFrame(update);
    }

    window.addEventListener('DOMContentLoaded', () => {
        animateCount("total-bottles", {{ $users->sum('bottle_stored') }});
        animateCount("total-users", {{ $users->count() }});
        animateCount("total-points", {{ $pointsDistributed }});
        animateCount("last-total-bottles", {{ $bottlesLastWeek }});
        animateCount("last-total-users", {{ $usersLastWeek }});
        animateCount("last-total-points", {{ $pointsLastWeek }});
        animateCount("active-device", {{ $devices->where('status', 1)->count() }});
    });
</script>

@endsection
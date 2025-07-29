@extends('layouts.user')
@section('title', 'My Submissions & Redeems')

@section('content')
<div class="md:my-8 md:mx-8 mx-4" 
     x-data="{ 
         open: false, 
         activeTab: '{{ $tab ?? 'submissions' }}',
         label() {
             return this.activeTab === 'submissions' ? 'Submissions' : 'Redeems';
         }
     }" 
     x-init="lucide.createIcons();">
    <div>
        <h2 class="text-3xl font-bold text-blue-700 mb-4" x-text="label()"></h2>
        <p class="text-gray-600 mb-5">Track all of your {{ $tab === 'submissions' ? 'submissions' : 'redeems' }} here</p>

        <!-- Dropdown Toggle -->
        <div class="inline-flex items-center gap-2 mb-6">
            <label class="text-md font-medium text-gray-700">View:</label>
            <div class="relative">
                <button type="button" @click="open = !open"
                    class="inline-flex items-center px-4 py-2.5 font-semibold text-gray-700 bg-white border border-gray-200 shadow-sm rounded-xl hover:bg-gray-50 hover:shadow-md transition-all duration-200">
                    <i :data-lucide="activeTab === 'submissions' ? 'list' : 'ticket'" class="w-5 h-5 mr-2"></i>
                    <span x-text="label()"></span>
                    <i data-lucide="chevron-down" class="w-4 h-4 ml-2"></i>
                </button>
                <ul x-show="open" @click.away="open = false" x-transition 
                    class="absolute z-50 mt-2 w-44 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden">
                    <li>
                        <a href="{{ url('/submissions?tab=submissions') }}"
                           @click.prevent="activeTab = 'submissions'; open = false"
                           class="flex items-center w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100 transition">
                            <i data-lucide="list" class="w-4 h-4 mr-2"></i>
                            Submissions
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/submissions?tab=redeems') }}"
                           @click.prevent="activeTab = 'redeems'; open = false"
                           class="flex items-center w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100 transition">
                            <i data-lucide="ticket" class="w-4 h-4 mr-2"></i>
                            Redeems
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Filter Dropdown for Submissions -->
        <div x-show="activeTab === 'submissions'" class="mb-6">
            <form method="get" action="{{ route('submissions') }}" class="relative"
                x-data="{ 
                    filterOpen: false, 
                    activeStatus: '{{ $status ?? 'all' }}',
                    statusLabel() {
                        return this.activeStatus === 'completed' ? 'Completed' 
                            : this.activeStatus === 'pending' ? 'Pending' 
                            : this.activeStatus === 'failed' ? 'Failed' 
                            : 'All';
                    }
                }">
                <input type="hidden" name="tab" value="submissions">
                <input type="hidden" name="status" x-model="activeStatus">
                <button type="button" @click="filterOpen = !filterOpen"
                    class="inline-flex items-center px-4 py-2.5 font-semibold text-gray-700 bg-white border border-gray-200 shadow-sm rounded-xl hover:bg-gray-50 hover:shadow-md transition-all duration-200">
                    <i :data-lucide="{
                        'all': 'list',
                        'completed': 'circle-check-big',
                        'failed': 'ban',
                        'pending': 'clock'
                    }[activeStatus]" class="w-5 h-5 mr-2"></i>
                    <span x-text="statusLabel()"></span>
                    <i data-lucide="chevron-down" class="w-4 h-4 ml-2"></i>
                </button>
                <ul x-show="filterOpen" @click.away="filterOpen = false" x-transition 
                    class="absolute z-50 mt-2 w-44 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden">
                    <template x-for="status in ['all', 'completed', 'pending', 'failed']" :key="status">
                        <li>
                            <button type="submit"
                                @click="activeStatus = status; filterOpen = false"
                                class="flex items-center w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100 transition">
                                <i :data-lucide="{
                                    'all': 'list',
                                    'completed': 'circle-check-big',
                                    'pending': 'clock',
                                    'failed': 'ban'
                                }[status]" class="w-4 h-4 mr-2"></i>
                                <span x-text="status.charAt(0).toUpperCase() + status.slice(1)"></span>
                            </button>
                        </li>
                    </template>
                </ul>
            </form>
        </div>

        <!-- Submissions Content -->
        <div x-show="activeTab === 'submissions'" class="mb-8 overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
            <div class="p-4">
                @forelse ($groupedSubmissions as $month => $submissions)
                    @if ($status === 'all' || $submissions->pluck('status')->contains($status))
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-700 mb-3">{{ $month }}</h3>
                        <div class="space-y-3">
                            @foreach ($submissions as $submission)
                                @if ($status === 'all' || $submission->status === $status)
                                <div class="p-4 bg-{{ $submission->status === 'completed' ? 'green' : ($submission->status === 'pending' ? 'yellow' : 'red') }}-50/80 shadow rounded-xl border-l-4 border-{{ $submission->status === 'completed' ? 'green' : ($submission->status === 'pending' ? 'yellow' : 'red') }}-400">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm text-gray-500">{{ $submission->created_at->format('d M Y, H:i') }}</p>
                                            <p class="mt-2 font-bold text-gray-800">{{ $submission->bottle_count }} Bottles</p>
                                            <p class="text-sm text-green-600">+{{ $submission->points_earned }} points</p>
                                        </div>
                                        <div class="text-left">
                                            <span class="inline-flex gap-2 text-sm px-3 py-1 rounded-full bg-{{ $submission->status === 'completed' ? 'green' : ($submission->status === 'pending' ? 'yellow' : 'red') }}-100 text-{{ $submission->status === 'completed' ? 'green' : ($submission->status === 'pending' ? 'yellow' : 'red') }}-700 font-medium capitalize">
                                                <i data-lucide="{{ 
                                                    $submission->status === 'completed' ? 'circle-check-big' : 
                                                    ($submission->status === 'pending' ? 'clock' : 'ban') 
                                                }}" class="w-5 h-5"></i>
                                                {{ $submission->status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                @empty
                    <div class="text-gray-500 text-center py-10">No submissions found.</div>
                @endforelse
            </div>
        </div>

        <!-- Redeems Content -->
        <div x-show="activeTab === 'redeems'" class="mb-8 overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
            <div class="p-4">
                @forelse ($groupedRedeems as $month => $redeems)
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-700 mb-3">{{ $month }}</h3>
                        <div class="space-y-3">
                            @foreach ($redeems as $redeem)
                                <div class="p-4 bg-{{ $redeem->status === 'active' ? 'green' : ($redeem->status === 'used' ? 'yellow' : 'red') }}-50/80 shadow rounded-xl border-l-4 border-{{ $redeem->status === 'active' ? 'green' : ($redeem->status === 'used' ? 'yellow' : 'red') }}-400">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm text-gray-500">{{ $redeem->created_at->format('d M Y, H:i') }}</p>
                                            <p class="mt-2 font-bold text-gray-800">{{ optional($redeem->reward)->name ?? 'Deleted Reward #' . $redeem->reward_deleted_id }}</p>
                                            <p class="text-sm text-red-600">-{{ $redeem->points_used }} points</p>
                                            @if ($redeem->expires_at)
                                                <p class="text-sm text-gray-600">Expires: {{ $redeem->expires_at->format('d M Y, H:i') }}</p>
                                            @endif
                                        </div>
                                        <div class="text-left">
                                            <span class="inline-flex gap-2 text-sm px-3 py-1 rounded-full bg-{{ $redeem->status === 'active' ? 'green' : ($redeem->status === 'used' ? 'yellow' : 'red') }}-100 text-{{ $redeem->status === 'active' ? 'green' : ($redeem->status === 'used' ? 'yellow' : 'red') }}-700 font-medium capitalize">
                                                <i data-lucide="{{ 
                                                    $redeem->status === 'active' ? 'ticket' : 
                                                    ($redeem->status === 'used' ? 'package-open' : 'calendar-x') 
                                                }}" class="w-5 h-5"></i>
                                                {{ $redeem->status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-gray-500 text-center py-10">No redeems found.</div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if ($groupedSubmissions instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div x-show="activeTab === 'submissions'" class="px-4 mt-4">
                {{ $groupedSubmissions->appends(['tab' => 'submissions', 'status' => $status])->links('pagination::tailwind') }}
            </div>
        @endif
        @if ($groupedRedeems instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div x-show="activeTab === 'redeems'" class="px-4 mt-4">
                {{ $groupedRedeems->appends(['tab' => 'redeems'])->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        lucide.createIcons();
    });
</script>
@endsection
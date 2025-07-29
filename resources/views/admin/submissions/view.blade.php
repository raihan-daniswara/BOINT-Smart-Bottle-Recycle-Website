@extends('layouts.admin')
@section('title', "Admin Submission")
@section('content')

<div x-data="{ 
    addModal: false 
}" 
x-init="lucide.createIcons()" 
x-effect="$nextTick(() => lucide.createIcons())">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-blue-700 mb-2">Submission Logs</h2>
        <p class="text-gray-600">Track and manage all existing submissions and redeems</p>
        <a x-show="'{{ $tab }}' === 'submissions'" @click.prevent="addModal = true" class="inline-flex px-4 py-2 mt-6 font-semibold text-white transition-all duration-200 bg-green-500 hover:scale-101 hover:cursor-pointer hover:bg-green-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            <i data-lucide="plus" class="w-6 h-6 mr-2"></i>
            <p>Add Submission</p>
        </a>
        <a x-show="'{{ $tab }}' === 'redeems'" @click.prevent="addModal = true" class="inline-flex px-4 py-2 mt-6 font-semibold text-white transition-all duration-200 bg-green-500 hover:scale-101 hover:cursor-pointer hover:bg-green-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            <i data-lucide="plus" class="w-6 h-6 mr-2"></i>
            <p>Add Redeem</p>
        </a>
    </div>

    <!-- Toggle View Dropdown -->
    <div class="inline-flex items-center gap-2 mb-6">
        <label class="text-md font-medium text-gray-700">View:</label>
        <select 
            onchange="window.location.href = this.value" 
            class="text-md font-medium text-gray-600 px-4 py-2 bg-white border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="{{ url('/admin/submissions?tab=submissions') }}" {{ $tab === 'submissions' ? 'selected' : '' }}>Submission</option>
            <option value="{{ url('/admin/submissions?tab=redeems') }}" {{ $tab === 'redeems' ? 'selected' : '' }}>Redeem</option>
        </select>
    </div>

    <!-- Add Submission Modal -->
    <div 
        x-show="addModal && '{{ $tab }}' === 'submissions'" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click.away="addModal = false"
        x-cloak
        class="fixed inset-0 z-50 w-full h-full overflow-y-auto backdrop-blur-xs"
    >
        <div class="relative w-full max-w-md p-5 mx-auto shadow-lg top-20 rounded-xl bg-green-300/10 backdrop-blur-md">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Add Submission</h3>
                <button @click="addModal = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <form action="{{ route('admin.submissions.create') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                    <select id="user_id" name="user_id" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="">Select User</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->username }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="device_id" class="block text-sm font-medium text-gray-700">Device</label>
                    <select id="device_id" name="device_id" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="">Select Device</option>
                        @foreach ($devices as $device)
                            <option value="{{ $device->id }}">{{ $device->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="bottle_count" class="block text-sm font-medium text-gray-700">Bottle Count</label>
                    <input type="number" id="bottle_count" name="bottle_count" placeholder="Enter bottle count" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm placeholder:text-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>
                <div>
                    <label for="points_earned" class="block text-sm font-medium text-gray-700">Points Earned</label>
                    <input type="number" id="points_earned" name="points_earned" placeholder="Enter points earned" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm placeholder:text-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button @click="addModal = false" class="px-4 py-2 font-semibold text-white bg-red-500 rounded-xl hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-800">Cancel</button>
                    <button type="submit" class="px-4 py-2 font-semibold text-white bg-green-500 rounded-xl hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">Add Submission</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Redeem Modal -->
    <div 
        x-show="addModal && '{{ $tab }}' === 'redeems'" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click.away="addModal = false"
        x-cloak
        class="fixed inset-0 z-50 w-full h-full overflow-y-auto backdrop-blur-xs"
    >
        <div class="relative w-full max-w-md p-5 mx-auto shadow-lg top-20 rounded-xl bg-green-300/10 backdrop-blur-md">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Add Redeem</h3>
                <button @click="addModal = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <form action="{{ route('admin.redeems.create') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                    <select id="user_id" name="user_id" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="">Select User</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->username }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="reward_id" class="block text-sm font-medium text-gray-700">Reward</label>
                    <select id="reward_id" name="reward_id" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="">Select Reward</option>
                        @foreach ($rewards as $reward)
                            <option value="{{ $reward->id }}">{{ $reward->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="points_used" class="block text-sm font-medium text-gray-700">Points Used</label>
                    <input type="number" id="points_used" name="points_used" placeholder="Enter points used" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm placeholder:text-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="active">Active</option>
                        <option value="used">Used</option>
                        <option value="expired">Expired</option>
                    </select>
                </div>
                <div>
                    <label for="expires_at" class="block text-sm font-medium text-gray-700">Expires At (Optional)</label>
                    <input type="datetime-local" id="expires_at" name="expires_at" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div class="flex justify-end gap-2">
                    <button @click="addModal = false" type="button" class="px-4 py-2 font-semibold text-white bg-red-500 rounded-xl hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-800">Cancel</button>
                    <button type="submit" class="px-4 py-2 font-semibold text-white bg-green-500 rounded-xl hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">Add Redeem</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Submissions Table -->
    <div x-show="'{{ $tab }}' === 'submissions'">
        <div class="mb-8 overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
            <div class="flex items-center justify-between gap-4 px-4 py-2 border-b border-gray-200">
                <a href='{{ url("/admin/submissions?tab=submissions") }}' class="inline-flex gap-2 items-center px-3 py-2.5 font-semibold text-gray-700 transition-all duration-200 bg-white border border-gray-100 shadow-sm rounded-xl hover:shadow-md focus:outline-none hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 hover:cursor-pointer">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                    Refresh
                </a>
                <form method="get" class="inline-flex items-center gap-2 my-1">
                    <input type="hidden" name="tab" value="submissions">
                    <div class="inline-flex items-center gap-2 mx-1 my-1">
                        <button class="ml-1 inline-flex items-center px-4 py-2.5 font-semibold text-gray-700 bg-white border shadow-sm rounded-xl border-gray-100 hover:shadow-md hover:bg-gray-100 hover:cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                            <i data-lucide="search" class="w-5 h-5"></i>
                        </button>
                        <input type="search" name="search" placeholder="Search..." value="{{ $keyword }}" class="w-full px-4 py-2.5 font-semibold text-gray-700 transition-all duration-200 bg-white border border-gray-100 shadow-sm min-w-10 lg:w-64 rounded-xl hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">User</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Device</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Bottles</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Points</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Timestamp</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Manage</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($submissions as $submission)
                        <tr class="transition-all duration-50 hover:bg-gray-100 hover:border-gray-300 hover:scale-101 hover:cursor-pointer">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $submission->submission_code }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ optional($submission->user)->username ?? 'Deleted User #' . $submission->user_deleted_id }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ optional($submission->device)->name ?? 'Deleted Device #' . $submission->device_deleted_id }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $submission->bottle_count }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-green-600">+{{ $submission->points_earned }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-600">{{ $submission->created_at }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($submission->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700">
                                    <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
                                    Pending
                                    </span>
                                @elseif ($submission->status === 'failed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700">
                                    <i data-lucide="ban" class="w-4 h-4 mr-1"></i>
                                    Failed
                                    </span>
                                @elseif ($submission->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                    <i data-lucide="circle-check-big" class="w-4 h-4 mr-1"></i>
                                    Completed
                                    </span>
                                @endif
                            </td>
                            <td class="inline-flex justify-center gap-2 my-2 mr-2 align-center">
                                @if ($submission->status === 'pending')
                                    <form action="{{ route('admin.submissions.update.status', ['id' => $submission->id, 'status' => 'failed'] ) }}" method="POST" onsubmit="return confirm('Are you sure you want to mark this submission as failed?');">
                                    @csrf
                                    @method('PUT')
                                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 font-semibold text-white bg-red-500 shadow-sm hover:cursor-pointer hover:bg-red-600 hover:shadow-md rounded-xl">
                                            <i data-lucide="ticket-x" class="w-5 h-5"></i>
                                            Reject
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.submissions.update.status', ['id' => $submission->id, 'status' => 'completed'] ) }}" method="POST" onsubmit="return confirm('Are you sure you want to mark this submission as complete?');">
                                    @csrf
                                    @method('PUT')
                                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 font-semibold text-white bg-green-500 shadow-sm hover:cursor-pointer hover:bg-green-600 hover:shadow-md rounded-xl">
                                            <i data-lucide="ticket-check" class="w-5 h-5"></i>
                                            Complete
                                        </button>
                                    </form>
                                @else
                                <a href="" class="inline-flex items-center gap-2 px-4 py-2 font-semibold text-white bg-blue-500 shadow-sm hover:cursor-pointer hover:bg-blue-600 hover:shadow-md rounded-xl">
                                    <i data-lucide="tickets" class="w-5 h-5"></i>
                                    Detail
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                            <tr class="hover:bg-gray-50">
                                <td colspan="8" class="px-6 py-4 text-sm font-medium text-center text-gray-900">No results found for keyword “{{ $keyword }}”</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Redeems Table -->
    <div x-show="'{{ $tab }}' === 'redeems'">
        <div class="mb-8 overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
            <div class="flex items-center justify-between gap-4 px-4 py-2 border-b border-gray-200">
                <a href='{{ url("/admin/submissions?tab=redeems") }}' class="inline-flex gap-2 items-center px-3 py-2.5 font-semibold text-gray-700 transition-all duration-200 bg-white border border-gray-100 shadow-sm rounded-xl hover:shadow-md focus:outline-none hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 hover:cursor-pointer">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                    Refresh
                </a>
                <form action="" method="get" class="inline-flex items-center gap-2 my-1">
                    <input type="hidden" name="tab" value="redeems">
                    <div class="inline-flex items-center gap-2 mx-1 my-1">
                        <button class="ml-1 inline-flex items-center px-4 py-2.5 font-semibold text-gray-700 bg-white border shadow-sm rounded-xl border-gray-100 hover:shadow-md hover:bg-gray-100 hover:cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                            <i data-lucide="search" class="w-5 h-5"></i>
                        </button>
                        <input type="search" id="search" name="search" placeholder="Search..." value="{{ $keyword }}" class="w-full px-4 py-2.5 font-semibold text-gray-700 transition-all duration-200 bg-white border border-gray-100 shadow-sm min-w-10 lg:w-64 rounded-xl hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    </div>
                </form>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Reward</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Points</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Redeemed At</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Manage</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($redeems as $redeem)
                    <tr class="transition-all duration-50 hover:bg-gray-100 hover:border-gray-300 hover:scale-101 hover:cursor-pointer">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $redeem->voucher_code }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ optional($redeem->user)->username ?? 'Deleted User #' . $redeem->user_deleted_id }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ optional($redeem->reward)->name ?? 'Deleted Reward #' . $redeem->reward_deleted_id }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-red-600">-{{ $redeem->points_used }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-600">{{ $redeem->created_at }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($redeem->status === 'used')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700">
                                <i data-lucide="package-open" class="w-4 h-4 mr-1"></i>
                                Used
                                </span>
                            @elseif ($redeem->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                <i data-lucide="ticket" class="w-4 h-4 mr-1"></i>
                                Active
                                </span>
                            @elseif ($redeem->status === 'expired')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700">
                                <i data-lucide="calendar-x" class="w-4 h-4 mr-1"></i>
                                Expired
                                </span>
                            @endif
                        </td>
                        <td>
                            <a href="" class="inline-flex items-center gap-2 px-4 py-2 font-semibold text-white bg-blue-500 shadow-sm hover:cursor-pointer hover:bg-blue-600 hover:shadow-md rounded-xl">
                                <i data-lucide="tickets" class="w-5 h-5"></i>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr class="hover:bg-gray-50">
                        <td colspan="8" class="px-6 py-4 text-sm font-medium text-center text-gray-900">No results found for keyword “{{ $keyword }}”</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($submissions instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div x-show="'{{ $tab }}' === 'submissions'" class="px-4 mt-4">
            {{ $submissions->appends(['tab' => 'submissions', 'search' => $keyword])->links('pagination::tailwind') }}
        </div>
    @endif
    @if ($redeems instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div x-show="'{{ $tab }}' === 'redeems'" class="px-4 mt-4">
            {{ $redeems->appends(['tab' => 'redeems', 'search' => $keyword])->links('pagination::tailwind') }}
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        lucide.createIcons();
    });
</script>
@endsection
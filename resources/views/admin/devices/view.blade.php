@extends('layouts.admin')
@section('content')

<!-- Combined Scope for Modals and Table -->
<div x-data="{
    addModal: false,
    editModal: false,
    table: false,
    deviceId: null,
    name: '',
    location: '',
    capacity: '',
    max_capacity: '',
    status: '',
    openEditModal(id, name, location, capacity, max_capacity, status) {
        this.editModal = true;
        this.deviceId = id;
        this.name = name;
        this.location = location;
        this.capacity = capacity;
        this.max_capacity = max_capacity;
        this.status = status;
    }
}" x-init="lucide.createIcons()" x-effect="$nextTick(() => lucide.createIcons())">

    <!-- Welcome Section -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-blue-700 mb-2">Device Management</h2>
        <p class="text-gray-600">Track and manage all device status, activity, and configurations</p>
        <a @click.prevent="addModal = true"
           class="inline-flex px-4 py-2 mt-6 font-semibold text-white transition-all duration-200 bg-green-500 hover:scale-101 hover:cursor-pointer hover:bg-green-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            <i data-lucide="plus" class="w-6 h-6 mr-2"></i>
            <p>Add Device</p>
        </a>
    </div>

    <!-- Add Device Modal -->
    <div 
        x-show="addModal" 
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
        <div class="relative w-full max-w-md p-5 mx-auto transition-transform duration-300 ease-in-out transform scale-100 shadow-lg top-20 rounded-xl bg-green-200/30">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Add Device</h3>
                <button @click="addModal = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <form action="{{ route('admin.devices.create') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter device name" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-md rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" required/>
                </div>
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                    <textarea id="location" name="location" placeholder="Enter device location" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" required></textarea>
                </div>
                <div>
                    <label for="max_capacity" class="block text-sm font-medium text-gray-700">Max Capacity</label>
                    <input type="number" id="max_capacity" name="max_capacity" placeholder="Enter max capacity" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" required/>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="addModal = false" class="px-4 py-2 font-semibold text-white bg-red-500 rounded-xl hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-800">Cancel</button>
                    <button type="submit" class="px-4 py-2 font-semibold text-white bg-green-500 rounded-xl hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">Add Device</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Device Modal -->
    <div 
        x-show="editModal" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click.away="editModal = false"
        x-cloak
        class="fixed inset-0 z-50 w-full h-full overflow-y-auto backdrop-blur-xs"
    >
        <div class="relative w-full max-w-md p-5 mx-auto transition-transform duration-300 ease-in-out transform scale-100 shadow-lg top-20 rounded-xl bg-blue-200/30">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Edit Device</h3>
                <button @click="editModal = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <form :action="'{{ url('/admin/devices') }}/' + deviceId" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input 
                        type="text" 
                        id="edit_name" 
                        name="name" 
                        x-model="name" 
                        placeholder="Enter device name" 
                        class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-md rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required
                    />
                </div>
                <div>
                    <label for="edit_location" class="block text-sm font-medium text-gray-700">Location</label>
                    <textarea 
                        id="edit_location" 
                        name="location" 
                        x-model="location" 
                        placeholder="Enter device location" 
                        class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required
                    ></textarea>
                </div>
                <div>
                    <label for="edit_capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                    <input 
                        type="number" 
                        id="edit_capacity" 
                        name="capacity" 
                        x-model="capacity" 
                        placeholder="Enter current capacity" 
                        class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required
                    />
                </div>
                <div>
                    <label for="edit_max_capacity" class="block text-sm font-medium text-gray-700">Max Capacity</label>
                    <input 
                        type="number" 
                        id="edit_max_capacity" 
                        name="max_capacity" 
                        x-model="max_capacity" 
                        placeholder="Enter max capacity" 
                        class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required
                    />
                </div>
                <div>
                    <label for="edit_status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select 
                        id="edit_status" 
                        name="status" 
                        x-model="status" 
                        class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                        <option value="0">Offline</option>
                        <option value="1">Online</option>
                        <option value="2">Maintenance</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button 
                        type="button" 
                        @click="editModal = false" 
                        class="px-4 py-2 font-semibold text-white bg-red-500 rounded-xl hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-800"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="px-4 py-2 font-semibold text-white bg-blue-500 rounded-xl hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Device Panel -->
    <div class="mb-8 overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
        <div class="pr-2 pl-3 py-2 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <!-- Refresh Button -->
                <a href="{{ url('/admin/devices') }}" 
                   class="inline-flex items-center px-3 py-2.5 font-semibold text-gray-700 transition-all duration-200 bg-white border border-gray-100 shadow-sm rounded-xl hover:shadow-md focus:outline-none hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 hover:cursor-pointer">
                    <i data-lucide="refresh-cw" class="w-5 h-5 mr-2"></i>
                    Refresh
                </a>

                <!-- Toggle View Button -->
                <div class="inline-flex items-center gap-2 mx-2 my-2">
                    <button 
                        @click="table = !table; $nextTick(() => lucide.createIcons())"
                        class="inline-flex items-center gap-2 px-4 py-2.5 font-semibold text-gray-700 bg-white border shadow-sm rounded-xl border-gray-100 hover:shadow-md hover:bg-gray-100 hover:cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200"
                    >
                        <i :data-lucide="table ? 'table-of-contents' : 'table'" class="w-5 h-5"></i>
                        <span x-text="table ? 'Simple View' : 'Table View'"></span>
                    </button>
                </div>
            </div>
        </div>
        <!-- Device Table Content -->
        <div class="p-4">
            <div class="gap-4">
                <div x-show="!table">
                    @foreach ($devices as $device)
                    <div class="p-4 transition-all duration-200 border bg-{{ $device->status === 0 ? 'red' : ($device->status === 1 ? 'green' : 'yellow') }}-50/70 border-gray-200 rounded-lg hover:border-gray-300 hover:shadow-md hover:-translate-y-1 mb-4">
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
            <div x-show="table">
                <div class="overflow-x-auto overflow-y-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Location</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Capacity</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Max Capacity</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Last Active</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Manage</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($devices as $device)
                            <tr class="transition-all duration-50 hover:bg-gray-100 hover:border-gray-300 hover:scale-101 hover:cursor-pointer">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $device->name }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $device->location }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $device->capacity }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $device->max_capacity }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-yellow-600">{{ \Carbon\Carbon::createFromTimestamp($device->last_active)->diffForHumans() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($device->status === 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <div class="w-2 h-2 mr-1 bg-red-500 rounded-full animate-pulse"></div>
                                            <span class="capitalize">Offline</span>
                                        </span>
                                    @endif
                                    @if ($device->status === 1)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <div class="w-2 h-2 mr-1 bg-green-500 rounded-full animate-pulse"></div>
                                            <span class="capitalize">Online</span>
                                        </span>
                                    @endif
                                    @if ($device->status === 2)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <div class="w-2 h-2 mr-1 bg-yellow-500 rounded-full animate-pulse"></div>
                                            <span class="capitalize">Maintenance</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('admin.devices.delete', $device->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this device?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 font-semibold text-white bg-red-500 rounded-xl hover:cursor-pointer hover:bg-red-600 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200">
                                                <i data-lucide="trash" class="w-5 h-5"></i>
                                                Delete
                                            </button>
                                        </form>
                                        <button 
                                            @click="openEditModal('{{ e($device->id) }}', '{{ e($device->name) }}', '{{ e($device->location) }}', '{{ e($device->capacity) }}', '{{ e($device->max_capacity) }}', '{{ e($device->status) }}')"
                                            class="inline-flex items-center gap-2 px-4 py-2 text-white bg-blue-500 rounded-xl hover:cursor-pointer hover:shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200"
                                        >
                                            <i data-lucide="pencil" class="w-5 h-5"></i>
                                            Edit
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Scripts -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        lucide.createIcons();
    });
</script>

@endsection
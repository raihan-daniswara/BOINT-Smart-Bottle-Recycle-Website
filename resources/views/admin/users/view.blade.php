@extends('layouts.admin')
@section('title', 'User Management')
@section('content')
<!-- Welcome Section -->
<div x-data="{
    addModal: false,
    editModal: false,
    userId: null,
    username: '',
    email: '',
    total_points: '',
    bottle_stored: '',
    role: '',
    openEditModal(id, username, email, total_points, bottle_stored, role) {
        this.editModal = true;
        this.userId = id;
        this.username = username;
        this.email = email;
        this.total_points = total_points;
        this.bottle_stored = bottle_stored;
        this.role = role;
    }
}" x-init="lucide.createIcons()" x-effect="$nextTick(() => lucide.createIcons())">

    <div class="mb-8">
        <h2 class="text-3xl font-bold text-blue-700 mb-2">User Management</h2>
        <p class="text-gray-600">Track and manage all existing users</p>
        <a @click.prevent="addModal = true" class="inline-flex px-4 py-2 mt-6 font-semibold text-white duration-100 bg-green-500 hover:cursor-pointer hover:bg-green-600 hover:scale-101 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            <i data-lucide="user-plus" class="w-6 h-6 mr-2"></i>
            <p>Add Account</p>
        </a>
    </div>

    <!-- Add Account Modal -->
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
                <h3 class="text-lg font-semibold text-gray-900">Add Account</h3>
                <button @click="addModal = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <form action="{{ route('admin.users.create') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter username" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-md rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" required/>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter email" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" required/>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter password" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" required/>
                </div>
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select id="role" name="role" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="">Select Role</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button @click="addModal = false" class="px-4 py-2 font-semibold text-white bg-red-500 rounded-xl hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-800">Cancel</button>
                    <button type="submit" class="px-4 py-2 font-semibold text-white bg-green-500 rounded-xl hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">Add Account</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Account Modal -->
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
        <div class="relative w-full max-w-md p-5 mx-auto transition-transform duration-300 ease-in-out transform scale-95 shadow-lg top-20 rounded-xl bg-blue-200/30">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Edit User</h3>
                <button @click="editModal = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <form :action="'{{ url('/admin/users') }}/' + userId" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input 
                        type="text" 
                        id="edit_name" 
                        name="username" 
                        x-model="username" 
                        placeholder="Enter username" 
                        class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-md rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required
                    />
                </div>
                <div>
                    <label for="edit_email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input 
                        id="edit_location" 
                        name="email" 
                        x-model="email"
                        class="w-full px-3 py-2 mt-1 bg-white border text-gray-600 border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        readonly
                    >
                </div>
                <div>
                    <label for="edit_total_points" class="block text-sm font-medium text-gray-700">Total Points</label>
                    <input 
                        type="number" 
                        id="edit_total_points" 
                        name="total_points" 
                        x-model="total_points" 
                        placeholder="Enter total points" 
                        class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required
                    />
                </div>
                <div>
                    <label for="edit_bottle_stored" class="block text-sm font-medium text-gray-700">Bottle Stored</label>
                    <input 
                        type="number" 
                        id="edit_bottle_stored" 
                        name="bottle_stored" 
                        x-model="bottle_stored" 
                        placeholder="Enter bottle stored" 
                        class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required
                    />
                </div>
                <div>
                    <label for="edit_role" class="block text-sm font-medium text-gray-700">Status</label>
                    <select 
                        id="edit_role" 
                        name="role" 
                        x-model="role" 
                        class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
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

    <!-- Users Table -->
    <div class="mb-8 overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
        <!-- Filter and Search Forms -->
        <div class="relative px-4 py-4 border-b border-gray-200">
            <form action="{{ route('admin.users') }}" method="GET" class="flex items-center justify-between gap-2">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" type="button" class="inline-flex items-center px-4 py-2.5 font-semibold text-gray-700 transition-all duration-200 bg-white border border-gray-200 shadow-sm rounded-xl hover:bg-gray-50 hover:shadow-md">
                        <i data-lucide="filter" class="w-5 h-5 mr-2"></i>
                        <span>{{ request('sort') == 'def' ? 'Filter' : (request('sort') == 'desc' ? 'Z-A' : (request('sort') == 'asc' ? 'A-Z' : 'Filter')) }}</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <ul x-show="open" @click.away="open = false" class="absolute z-50 w-40 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg">
                        <li>
                            <button type="submit" name="sort" value="def" class="flex items-center w-full px-4 py-2 text-sm text-left text-gray-700 sort-button hover:bg-gray-100">
                                <i data-lucide="align-vertical-distribute-center" class="w-4 h-4 mr-2"></i>
                                Default
                            </button>
                        </li>
                        <li>
                            <button type="submit" name="sort" value="asc" class="flex items-center w-full px-4 py-2 text-sm text-left text-gray-700 sort-button hover:bg-gray-100">
                                <i data-lucide="arrow-up" class="w-4 h-4 mr-2"></i>
                                A-Z
                            </button>
                        </li>
                        <li>
                            <button type="submit" name="sort" value="desc" class="flex items-center w-full px-4 py-2 text-sm text-left text-gray-700 sort-button hover:bg-gray-100">
                                <i data-lucide="arrow-down" class="w-4 h-4 mr-2"></i>
                                Z-A
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center px-4 py-2.5 font-semibold text-gray-700 transition-all duration-200 bg-white border border-gray-200 shadow-sm rounded-xl hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i data-lucide="search" class="w-5 h-5"></i>
                    </button>
                    <input type="search" id="search" name="search" placeholder="Search..." value="{{ $keyword }}" class="w-full px-4 py-2.5 font-semibold text-gray-700 transition-all duration-200 bg-white border border-gray-200 shadow-sm min-w-10 lg:w-64 rounded-xl hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                </div>
            </form>
        </div>
        <div class="max-w-full overflow-x-auto">
        <table class="min-w-full table-fixed">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500">#</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500">USERNAME</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500">EMAIL</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500">TOTAL POINTS</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500">ROLE</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500">MANAGE</th>
                    </tr>
                </thead>
                @if ($users->count()  >= 1)
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($users as $user)
                    <div class="">
                        <tr class="transition-all duration-50 hover:bg-gray-100 hover:border-gray-300 hover:scale-101 hover:cursor-pointer">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->username }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->total_points }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 capitalize">{{ $user->role }}</td>
                            <td class="inline-flex gap-2 my-2 mr-2">
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 font-semibold text-white bg-red-500 shadow-sm hover:cursor-pointer hover:bg-red-600 hover:shadow-md rounded-xl">
                                        <i data-lucide="trash" class="w-5 h-5"></i>
                                        Delete
                                    </button>
                                </form>
                                <button 
                                    @click="openEditModal('{{ e($user->id) }}', '{{ e($user->username) }}', '{{ e($user->email) }}', '{{ e($user->total_points) }}', '{{ e($user->bottle_stored) }}', '{{ e($user->role) }}')"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-white bg-blue-500 rounded-xl hover:cursor-pointer hover:shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200"
                                >
                                    <i data-lucide="pencil" class="w-5 h-5"></i>
                                    Edit
                                </button>
                            </td>
                        </tr>
                    </div>
                    
                    @endforeach
                </tbody>
                @endif
                @if ($users->count() <= 0 && $keyword !== null)
                <tbody class="bg-white">
                    <tr class="hover:bg-gray-50">
                        <td colspan="6" class="px-6 py-4 text-sm font-medium text-center text-gray-900">No results found for keyword “{{ $keyword }}”</td>
                    </tr>
                </tbody>
                @endif
            </table>
        </div>
    </div>

    <div class="px-4 mt-4">
        {{ $users->links('pagination::tailwind') }}
    </div>
</div>
<!-- Include Lucide Icons -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Lucide icons
        lucide.createIcons();
    });
</script>
@endsection 
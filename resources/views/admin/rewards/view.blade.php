    @extends('layouts.admin')
    @section('content')

    <div x-data="{ addModal: false, editModal: false, selectedReward: {} }" x-init="lucide.createIcons()" x-effect="$nextTick(() => lucide.createIcons())">
        <div class="mb-8">
            <h2 class="mb-2 text-3xl font-bold text-blue-700">Reward Management</h2>
            <p class="text-gray-600">Manage rewards available for users to redeem</p>
            <a @click.prevent="addModal = true"
            class="inline-flex px-4 py-2 mt-6 font-semibold text-white transition-all duration-200 bg-green-500 hover:scale-101 hover:cursor-pointer hover:bg-green-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i data-lucide="plus" class="w-6 h-6 mr-2"></i>
                <p>Add Reward</p>
            </a>
        </div>

        {{-- Add Modal --}}
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
        class="fixed inset-0 z-50 w-full h-full overflow-y-auto backdrop-blur-xs">
            <div class="relative w-full max-w-md p-5 mx-auto transition-transform duration-300 ease-in-out transform scale-100 shadow-lg top-20 rounded-xl bg-green-200/30">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Add Reward</h3>
                    <button @click="addModal = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                <form action="{{ route('admin.rewards.create') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" placeholder="Enter reward name"class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-md rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" placeholder="Enter reward description" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-md rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Point Cost</label>
                            <input type="number" name="points"  placeholder="Enter Points" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Stock</label>
                            <input type="number" name="stock" 
                            placeholder="Enter Stock" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Categories</label>
                        <input type="text" name="categories" 
                        placeholder="Enter Reward Categories" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-md rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Reward Photo</label>
                        <div>
                        <input type="file" name="reward_photo" accept="image/jpeg, image/png, image/webp, image/jpg" class="w-full file:border-0 file:py-2 file:px-3 file:mt-1 file:bg-indigo-600 file:text-white file:rounded-xl" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="addModal = false"
                                class="px-4 py-2 font-semibold text-white bg-red-500 rounded-xl hover:bg-red-600">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 font-semibold text-white bg-green-500 rounded-xl hover:bg-green-600">
                            Add Reward
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Edit Modal --}}
        <div
            x-show="editModal"
            x-transition
            x-cloak
            @click.away="editModal = false"
            class="fixed inset-0 z-50 w-full h-full overflow-y-auto backdrop-blur-xs">
            <div class="relative w-full max-w-md p-5 mx-auto top-20 rounded-xl bg-blue-200/30 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Reward</h3>
                    <button @click="editModal = false" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                <form :action="'/admin/rewards/' + selectedReward.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" x-model="selectedReward.name"
                            class="w-full px-3 py-2 mt-1 bg-white  shadow-md rounded-xl focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" x-model="selectedReward.description"
                                class="w-full px-3 py-2 mt-1 bg-white  shadow-md rounded-xl focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Points</label>
                            <input type="number" name="points" x-model="selectedReward.points"
                                class="w-full px-3 py-2 mt-1 bg-white  shadow-sm rounded-xl focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Stock</label>
                            <input type="number" name="stock" x-model="selectedReward.stock"
                                class="w-full px-3 py-2 mt-1 bg-white  shadow-sm rounded-xl focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Categories</label>
                        <input type="text" name="categories" x-model="selectedReward.categories"
                            class="w-full px-3 py-2 mt-1 bg-white  shadow-md rounded-xl focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" x-model="selectedReward.status"
                                class="w-full px-3 py-2 mt-1 bg-white  shadow-sm rounded-xl focus:ring-2 focus:ring-blue-500">
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Change Photo (optional)</label>
                        <input type="file" name="reward_photo"
                            class="w-full file: file:py-2 file:px-3 file:bg-indigo-600 file:text-white file:rounded-xl" />
                    </div>

                    <div x-show="selectedReward.reward_photo">
                        <label class="block text-sm font-medium text-gray-700">Current Reward Photo</label>
                        <img 
                            :src="'/storage/' + selectedReward.reward_photo" 
                            alt="Current Reward Photo"
                            class="object-cover w-auto h-40 mt-1 border-blue-500 shadow-md border-3 rounded-xl" />
                    </div>
                    
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="editModal = false"
                                class="px-4 py-2 font-semibold text-white bg-red-500 rounded-xl hover:bg-red-600">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 font-semibold text-white bg-blue-500 rounded-xl hover:bg-blue-600">
                            Update Reward
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <div class="grid max-[947px]:grid-cols-1 mb-8 min-[550px]:grid-cols-2 min-[1270px]:grid-cols-4 gap-6">
            @forelse ($rewards as $reward)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition duration-200 overflow-hidden">
                    <img src="{{ asset('storage/' . $reward->reward_photo) }}"
                        alt="{{ $reward->name }}"
                        class="w-full h-48 object-cover">

                    <div class="p-4 space-y-1">
                        <div class="flex justify-between items-center rounded-md">
                            <h3 class="text-xl font-semibold truncate">{{ $reward->name }}</h3>
                            <form action="{{ route('admin.rewards.delete', $reward->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this reward?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-500 hover:text-red-600 hover:shadow-md hover:cursor-pointer focus:outline-none transition-all duration-200 rounded-xl ">
                                    <i data-lucide="trash" class="w-5 h-5"></i>
                                </button>
                            </form>
                        </div>
                        <p class="text-sm text-gray-600 truncate">{{ $reward->categories }}</p>
                        <p class="text-sm text-gray-500 line-clamp-2">{{ $reward->description }}</p>

                        <div class="flex items-center justify-between text-sm font-semibold mt-2">
                            <span class="text-green-600">{{ $reward->points }} Points</span>
                            <span class="text-gray-600">Stock: {{ $reward->stock }}</span>
                        </div>

                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs px-2 py-1 rounded-full font-medium
                                {{ $reward->status === 'available' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ ucfirst($reward->status) }}
                            </span>
                            <button @click.prevent="selectedReward = {{ $reward }}; editModal = true"
                                    class="text-sm text-blue-600 hover:text-blue-800 hover:cursor-pointer flex items-center gap-1">
                                <i data-lucide="pencil" class="w-4 h-4"></i> Edit
                            </button>
                        </div>
                    </div>
                </div>

            @empty
                <div class="p-10 text-center text-sm text-gray-500 shadow-sm rounded-xl bg-gray-50">
                    You haven't redeemed any rewards yet.
                </div>
            @endforelse
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            lucide.createIcons();
        });
    </script>

    @endsection

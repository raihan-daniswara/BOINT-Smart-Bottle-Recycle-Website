@extends('layouts.user')
@section('title', 'My Profile')

@section('content')
<!-- Modal -->
<div x-data="{ editModal: false }" class="mx-5 md:my-10">
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
          <form :action="'{{ url('/profile'.'/'. Auth::id()) }}'" method="POST" enctype="multipart/form-data" class="space-y-4">
              @csrf
              @method('PUT')
              <div>
                  <label for="edit_username" class="block text-sm font-medium text-gray-700">Username</label>
                  <input 
                      type="text"
                      name="username" 
                      value="{{ Auth::user()->username }}"
                      placeholder="Enter username" 
                      class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-md rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" 
                      required
                  />
              </div>
              <div>
                  <label for="edit_email" class="block text-sm font-medium text-gray-700">Email</label>
                  <input 
                      type="email"
                      name="email" 
                      value="{{ Auth::user()->email }}"
                      class="w-full px-3 py-2 mt-1 text-gray-600 bg-white border border-white shadow-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" 
                      readonly
                  >
              </div>
              <div>
                  <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                  <textarea
                      name="description" 
                      class="w-full px-3 py-2 mt-1 bg-white border border-white shadow-md rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" 
                  >{{ Auth::user()->description }}</textarea>
              </div>
              @if (Auth::user()->profile_photo)
              <div>
                <label class="block text-sm font-medium text-gray-700">Current Profile Photo</label>
                  <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"  class="object-cover w-auto h-40 mt-1 border-blue-500 shadow-md border-3 rounded-xl" />
              </div>
              @endif
              <div>
                <label class="block text-sm font-medium text-gray-700">Profile Photo</label>
                <input type="file" name="profile_photo" accept="image/jpeg, image/png, image/webp, image/jpg" class="w-full file:border-0 file:py-2 file:px-3 file:mt-1 file:bg-indigo-600 file:text-white file:rounded-xl" />
              </div>
              <div class="flex justify-end gap-2">
                  <button 
                      type="button" 
                      @click="editModal = false" 
                      class="px-4 py-2 font-semibold text-white bg-red-600 rounded-xl hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-800"
                  >
                      Cancel
                  </button>
                  <button 
                      type="submit" 
                      class="px-4 py-2 font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-800"
                  >
                      Save Changes
                  </button>
              </div>
          </form>
      </div>
  </div>
  <div class="max-w-4xl p-6 px-2 mx-auto sm:px-4">
    <div class="p-6 shadow-xl bg-white/70 backdrop-blur-lg rounded-2xl">
      <div class="flex max-[460px]:flex-col items-center text-center gap-4 mb-6 min-[460px]:flex-row min-[460px]:items-start min-[460px]:text-left">
        <div class="max-[460px]:w-25 max-[460px]:h-25 max-[460px]:min-w-25 w-20 h-20 min-w-20 rounded-full overflow-hidden bg-blue-100 shadow-md flex max-[460px]:items-center max-[460px]:justify-center">
          @if (Auth::user()->profile_photo)
            <img 
              src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
              alt="Profile Photo" 
              class="object-cover object-center w-full h-full"
            />
          @else
            <div class="flex items-center justify-center w-full h-full bg-blue-200">
              <i data-lucide="user" class="w-10 h-10 text-blue-600"></i>
            </div>
          @endif
        </div>

        <div>
          <h2 class="text-2xl font-bold text-gray-800">{{ Auth::user()->username }}</h2>
          <p class="text-gray-600">{{ Auth::user()->email }}</p>
          @if (Auth::user()->description)
            <p class="mt-1 text-sm text-gray-500" style="word-break: break-word">"{{ Auth::user()->description }}"</p>
          @endif
        </div>
      </div>
      <div class="space-y-4">
        <!-- Card Join Date -->
        <div class="flex items-center gap-4 p-4 shadow-md bg-gradient-to-br from-yellow-200 to-yellow-300 rounded-xl">
          <i data-lucide="calendar" class="text-yellow-600 w-9 h-9"></i>
          <div>
            <p class="text-sm font-semibold text-gray-600">Join Date</p>
            <h3 class="text-lg font-semibold text-gray-800">
              {{ \Carbon\Carbon::parse(Auth::user()->created_at)->format('d M Y') }}
            </h3>
          </div>
        </div>

          <div class="grid grid-cols-1 min-[460px]:grid-cols-2 gap-4">
            <!-- Bottle Stored -->
            <div class="flex items-center gap-4 p-4 shadow-md bg-gradient-to-br from-green-200 to-green-400 rounded-xl">
              <i data-lucide="bottle-wine" class="text-green-600 w-9 h-9"></i>
              <div>
                <p class="text-sm font-semibold text-gray-600">Bottle Stored</p>
                <p class="text-lg font-bold text-gray-900">{{ Auth::user()->bottle_stored ?? 0 }}</p>
              </div>
            </div>

            <!-- Total Points -->
            <div class="flex items-center gap-4 p-4 shadow-md bg-gradient-to-br from-purple-200 to-purple-400 rounded-xl">
              <i data-lucide="coins" class="text-purple-600 w-9 h-9"></i>
              <div>
                <p class="text-sm font-semibold text-gray-600">Total Points</p>
                <p class="text-lg font-bold text-gray-900">{{ Auth::user()->total_points ?? 0 }}</p>
              </div>
            </div>
          </div>




      <div class="flex justify-end mt-6">
        <button @click="editModal = true"
          class="inline-flex items-center gap-2 px-4 py-2 text-white transition-all duration-200 bg-blue-600 rounded-xl hover:cursor-pointer hover:shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600"
      >
          <i data-lucide="pencil" class="w-5 h-5"></i>
          Edit Profile
      </button>
      </div>
    </div>
  </div>
</div>
</div>
@endsection
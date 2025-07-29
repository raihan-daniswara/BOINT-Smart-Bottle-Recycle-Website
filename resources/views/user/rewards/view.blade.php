@extends('layouts.user')

@section('content')
<div x-data="{ detailModal: false, selectedReward: null }" x-init="lucide.createIcons()" x-effect="$nextTick(() => lucide.createIcons())" class="mb-8 md:my-8 md:mx-8 mx-4">
  @if ($errors->any())
  <div class="flex items-center gap-3 p-4 mb-6 border rounded-lg bg-red-200/70 border-red-300/50">
    <i data-lucide="alert-circle" class="flex-shrink-0 w-5 h-5 mt-1 text-red-600"></i>
    <ul class="pl-5 space-y-1 text-sm font-semibold text-red-700 list-disc" id="errorText">
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <!-- Success Messages -->
  @if (session('success'))
  <div class="flex items-center gap-3 p-4 mb-6 border rounded-lg bg-green-200/70 border-green-300/70">
    <i data-lucide="check-circle" class="flex-shrink-0 w-5 h-5 mt-1 text-green-600"></i>
    <ul class="pl-5 space-y-1 text-sm font-semibold text-green-700 list-disc" id="errorText">
      {{ session('success') }}
    </ul>
  </div>
  @endif
    <div class="mb-6">
        <h2 class="mb-2 text-3xl font-bold text-blue-700">Available Rewards</h2>
        <p class="text-gray-600">Browse and redeem your points for rewards</p>
    </div>

    {{-- Detail Modal --}}
    <div
        x-show="detailModal"
        x-transition
        x-cloak
        @click.away="detailModal = false"
        class="fixed inset-0 z-50 w-full h-full overflow-y-auto backdrop-blur-xs">
        <div class="relative w-full max-w-md p-5 mx-auto top-20 rounded-xl bg-white shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Reward Detail</h3>
                <button @click="detailModal = false" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div x-show="selectedReward?.reward_photo">
                    <img :src="'/storage/' + selectedReward.reward_photo" alt="Reward Photo" class="w-full h-48 object-cover rounded-xl">
                </div>
                <div>
                    <h3 class="text-xl font-semibold" x-text="selectedReward.name"></h3>
                    <p class="text-sm text-gray-600" x-text="selectedReward.categories"></p>
                    <p class="text-sm text-gray-500 mt-2" x-text="selectedReward.description"></p>
                </div>
                <div class="flex justify-between text-sm font-semibold">
                    <span class="text-green-600" x-text="selectedReward.points + ' Points'"></span>
                    <span class="text-gray-600" x-text="'Stock: ' + selectedReward.stock"></span>
                </div>
                <form method="POST" :action="'rewards/' + selectedReward.id">
                    @csrf
                    <div class="flex justify-end">
                        <div class="flex justify-end">
                          <template x-if="selectedReward?.stock > 0">
                            <button type="submit" class="px-4 py-2 font-semibold text-white bg-blue-500 rounded-xl hover:bg-blue-600">
                                Redeem Reward
                            </button>
                          </template>
                          <template x-if="selectedReward?.stock == 0">
                              <button type="button" class="px-4 py-2 font-semibold text-white bg-gray-400 rounded-xl cursor-not-allowed" disabled>
                                  Out of Stock
                              </button>
                          </template>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Reward Grid --}}
    <div class="grid max-[947px]:grid-cols-1 mb-8 min-[550px]:grid-cols-2 min-[1270px]:grid-cols-4 gap-6">
        @forelse ($rewards as $reward)
            <div class="bg-white h-full flex flex-col rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition duration-200 overflow-hidden">
                <img src="{{ asset('storage/' . $reward->reward_photo) }}"
                     alt="{{ $reward->name }}"
                     class="w-full h-48 object-cover">

                <div class="p-4 flex flex-col justify-between h-full">
                  <div>
                      <h3 class="text-xl font-semibold truncate">{{ $reward->name }}</h3>
                      <p class="text-sm text-gray-600 truncate">{{ $reward->categories }}</p>
                      <p class="text-sm text-gray-500 line-clamp-2">{{ $reward->description }}</p>
                  </div>

                  <div class="mt-auto space-y-2 pt-4">
                      <div class="flex items-center justify-between text-sm font-semibold">
                          <span class="text-green-600">{{ $reward->points }} Points</span>
                          <span class="text-gray-600">Stock: {{ $reward->stock }}</span>
                      </div>
                      <div>
                          @if ($reward->stock > 0)
                            <button @click.prevent="selectedReward = {{ json_encode($reward) }}; detailModal = true" class="px-4 py-2 text-sm font-semibold text-white bg-blue-500 rounded-xl hover:bg-blue-600 w-full">
                              Redeem
                            </button>
                          @else
                            <button class="px-4 py-2 text-sm font-semibold text-white bg-gray-400 rounded-xl cursor-not-allowed w-full" disabled>
                              Out of Stock
                            </button>
                          @endif
                      </div>
                  </div>
              </div>

            </div>
        @empty
            <div class="p-10 text-center text-sm text-gray-500 shadow-sm rounded-xl bg-gray-50">
                No Reward Available.
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
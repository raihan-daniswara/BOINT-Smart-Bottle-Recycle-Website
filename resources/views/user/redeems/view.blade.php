@extends('layouts.user')

@section('content')
<div class="mx-4 md:mx-8 my-8" x-data x-init="lucide.createIcons()">
  <div class="mb-6">
    <h2 class="text-3xl font-bold text-blue-700 mb-2">My Redeemed Rewards</h2>
    <p class="text-gray-600">View your claimed rewards and their status.</p>
  </div>
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
  @if ($redeems->isEmpty())
    <div class="p-10 text-center text-sm text-gray-500 shadow-sm rounded-xl bg-gray-50">
      You haven't redeemed any rewards yet.
    </div>
  @else
    <div class="grid max-[947px]:grid-cols-1 mb-8 min-[550px]:grid-cols-2 min-[1270px]:grid-cols-4 gap-6">
      @foreach ($redeems as $redeem)
        @php
          $status = $redeem->status;
          $reward = $redeem->reward;
          $icon = $status === 'active' ? 'ticket' : ($status === 'used' ? 'package-open' : 'calendar-x');
          $statusColor = match ($status) {
            'active' => 'text-green-600',
            'used' => 'text-yellow-600',
            'expired' => 'text-red-600',
            default => 'text-gray-500'
          };
        @endphp

        <div class="bg-{{ $status === 'active' ? 'green' : ($status === 'used' ? 'yellow' : 'red') }}-50 rounded-2xl border border-gray-200 shadow-md transition hover:shadow-lg flex flex-col overflow-hidden">
          <img src="{{ asset('storage/' . ($reward->reward_photo ?? 'default.jpg')) }}"
               alt="{{ $reward->name ?? 'Deleted Reward' }}"
               class="w-full h-40 object-cover">

          <div class="p-4 flex flex-col flex-grow justify-between relative">
            <div class="space-y-1 mb-2">
              <div class="flex justify-between items-start mb-1">
                <h3 class="text-lg font-bold text-gray-800 truncate">
                  {{ $reward->name ?? 'Deleted Reward #' . $redeem->reward_deleted_id }}
                </h3>
                <span class="flex items-center gap-1 {{ $statusColor }} text-sm font-semibold">
                  <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
                  {{ ucfirst($status) }}
                </span>
              </div>

              {{-- This section handles category and expiry --}}
              <div class="flex justify-between items-center text-sm text-gray-600">
                <span class="truncate max-w-[60%]">{{ $reward->categories ?? '-' }}</span>
                @if ($redeem->expires_at && $redeem->status === 'active' || $redeem->status === 'expired')
                  <span class="text-xs text-red-500 text-right">
                    Expired: {{ \Carbon\Carbon::parse($redeem->expires_at)->format('d M Y') }}
                  </span>
                @endif
              </div>

              <p class="text-sm text-gray-500 line-clamp-2">{{ $reward->description ?? 'Reward has been deleted.' }}</p>
            </div>


            <div class="mt-auto">
              <div class="flex justify-between text-xs text-gray-500 mb-3">
                <span>Redeemed: {{ $redeem->created_at->format('d M Y') }}</span>
                <span>Code: {{ $redeem->voucher_code }}</span>
              </div>

              <form method="POST" action="{{ route('user.redeems.use', $redeem->id) }}">
                @csrf
                @method('PATCH')
                <button type="submit"
                        class="w-full px-4 py-2 text-sm font-semibold text-white rounded-xl
                          @if ($status === 'active') bg-blue-500 hover:bg-blue-600
                          @else bg-gray-500 @endif transition"
                        @if($status !== 'active') disabled @endif>
                  @if($status === 'active')Use Now @endif
                  @if($status === 'used')Already in Use @endif
                  @if($status === 'expired')Expired @endif  
                </button>
              </form>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    lucide.createIcons();
  });
</script>
@endsection

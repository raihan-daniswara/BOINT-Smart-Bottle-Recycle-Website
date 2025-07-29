<!-- Error Messages -->
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
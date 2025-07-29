@extends('layouts.admin')
@section('title', 'Submission Details')
@section('content')
<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('admin.submissions') }}" class="inline-flex items-center px-3 py-2 text-gray-700 bg-gray-100 border shadow-sm rounded-xl border-gray-50 hover:shadow-md hover:bg-gray-200">
        <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
        Back to Submissions
    </a>
</div>

<!-- Submission Details -->
<div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
    <h2 class="mb-4 text-2xl font-bold text-gray-900">Submission Details</h2>
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Submission Info -->
        <div>
            <h3 class="mb-3 text-lg font-semibold text-gray-800">Submission Information</h3>
            <dl class="space-y-3">
                <div class="flex items-center">
                    <dt class="w-1/3 text-sm font-medium text-gray-500">Invoice ID</dt>
                    <dd class="w-2/3 text-sm text-gray-900">#AS1239XK</dd>
                </div>
                <div class="flex items-center">
                    <dt class="w-1/3 text-sm font-medium text-gray-500">Status</dt>
                    <dd class="w-2/3 text-sm">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">
                            <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i>
                            Completed
                        </span>
                    </dd>
                </div>
                <div class="flex items-center">
                    <dt class="w-1/3 text-sm font-medium text-gray-500">Date</dt>
                    <dd class="w-2/3 text-sm text-gray-900">July 1, 2025, 08:15 PM</dd>
                </div>
                <div class="flex items-center">
                    <dt class="w-1/3 text-sm font-medium text-gray-500">Points Earned</dt>
                    <dd class="w-2/3 text-sm text-green-600">+50</dd>
                </div>
                <div class="flex items-center">
                    <dt class="w-1/3 text-sm font-medium text-gray-500">Bottles</dt>
                    <dd class="w-2/3 text-sm text-gray-900">5</dd>
                </div>
            </dl>
        </div>

        <!-- User Info -->
        <div>
            <h3 class="mb-3 text-lg font-semibold text-gray-800">User Information</h3>
            <dl class="space-y-3">
                <div class="flex items-center">
                    <dt class="w-1/3 text-sm font-medium text-gray-500">Name</dt>
                    <dd class="w-2/3 text-sm text-gray-900">Alice Johnson</dd>
                </div>
                <div class="flex items-center">
                    <dt class="w-1/3 text-sm font-medium text-gray-500">Email</dt>
                    <dd class="w-2/3 text-sm text-gray-900">alice.johnson@example.com</dd>
                </div>
                <div class="flex items-center">
                    <dt class="w-1/3 text-sm font-medium text-gray-500">User ID</dt>
                    <dd class="w-2/3 text-sm text-gray-900">USR12345</dd>
                </div>
            </dl>
        </div>

        <!-- Location Info -->
        <div>
            <h3 class="mb-3 text-lg font-semibold text-gray-800">Location Information</h3>
            <dl class="space-y-3">
                <div class="flex items-center">
                    <dt class="w-1/3 text-sm font-medium text-gray-500">Location</dt>
                    <dd class="w-2/3 text-sm text-gray-900">Main Campus - Building A</dd>
                </div>
                <div class="flex items-center">
                    <dt class="w-1/3 text-sm font-medium text-gray-500">Device ID</dt>
                    <dd class="w-2/3 text-sm text-gray-900">DEV789</dd>
                </div>
            </dl>
        </div>

        <!-- Additional Notes -->
        <div>
            <h3 class="mb-3 text-lg font-semibold text-gray-800">Additional Notes</h3>
            <p class="text-sm text-gray-600">Submission completed successfully. User deposited 5 plastic bottles at the recycling station.</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end gap-3 mt-6">
        <button class="inline-flex items-center px-4 py-2 text-white bg-blue-600 border border-blue-600 shadow-sm rounded-xl hover:bg-blue-700">
            <i data-lucide="edit" class="w-5 h-5 mr-2"></i>
            Edit Submission
        </button>
        <button class="inline-flex items-center px-4 py-2 text-white bg-red-600 border border-red-600 shadow-sm rounded-xl hover:bg-red-700">
            <i data-lucide="trash" class="w-5 h-5 mr-2"></i>
            Delete Submission
        </button>
    </div>
</div>
@endsection
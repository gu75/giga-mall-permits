@extends('layouts.app')

@section('title', 'Edit Tenant')

@section('content')
<div class="max-w-3xl mx-auto py-4">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#C8A951]">Tenant Management</p>
            <h1 class="mt-1 text-3xl font-extrabold text-[#0A2342]">Edit Tenant: {{ $tenant->name }}</h1>
        </div>
        <a href="{{ route('tenants.index') }}" class="text-sm font-semibold text-gray-600 hover:underline">
            ← Back to Tenants
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-[#E2E8F0]">
        <form method="POST" action="{{ route('tenants.update', $tenant) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $tenant->name) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#0A2342]">
                @error('name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $tenant->email) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#0A2342]">
                @error('email') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1">Shop Name</label>
                    <input type="text" name="shop_name" value="{{ old('shop_name', $tenant->shop_name) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#0A2342]">
                    @error('shop_name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1">Floor / Location</label>
                    <input type="text" name="floor_location" value="{{ old('floor_location', $tenant->floor_location) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#0A2342]">
                    @error('floor_location') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1">Cell Number</label>
                    <input type="text" name="cell_no" value="{{ old('cell_no', $tenant->cell_no) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#0A2342]">
                    @error('cell_no') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('tenants.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="bg-[#0A2342] text-white px-5 py-2 rounded-lg text-sm font-bold hover:bg-slate-800">
                    Update Tenant
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
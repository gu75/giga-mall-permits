@extends('layouts.app')

@section('title', 'Edit Work Permit')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Edit Work Permit #{{ $permit->id }}</h1>

    <form action="{{ route('work-permits.update', $permit->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Outlet Name</label>
                <input type="text" name="outlet_name" value="{{ old('outlet_name', $permit->outlet_name) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Floor / Location</label>
                <input type="text" name="floor_location" value="{{ old('floor_location', $permit->floor_location) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Site Incharge Name</label>
                <input type="text" name="site_incharge_name" value="{{ old('site_incharge_name', $permit->site_incharge_name) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Site Incharge Cell No</label>
                <input type="text" name="site_incharge_cell_no" value="{{ old('site_incharge_cell_no', $permit->site_incharge_cell_no) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Site Incharge CNIC</label>
                <input type="text" name="site_incharge_cnic" value="{{ old('site_incharge_cnic', $permit->site_incharge_cnic) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Nature of Work</label>
                <input type="text" name="nature_of_work" value="{{ old('nature_of_work', $permit->nature_of_work) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Requested By</label>
                <input type="text" name="requested_by" value="{{ old('requested_by', $permit->requested_by) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Requested By Cell No</label>
                <input type="text" name="requested_by_cell_no" value="{{ old('requested_by_cell_no', $permit->requested_by_cell_no) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Valid From</label>
                <input type="date" name="valid_from" value="{{ old('valid_from', $permit->valid_from?->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Valid From — Time</label>
                <input type="time" name="valid_from_time" value="{{ old('valid_from_time', $permit->valid_from_time?->format('H:i')) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Valid To</label>
                <input type="date" name="valid_to" value="{{ old('valid_to', $permit->valid_to?->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Valid To — Time</label>
                <input type="time" name="valid_to_time" value="{{ old('valid_to_time', $permit->valid_to_time?->format('H:i')) }}" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <div class="mt-6 flex space-x-3">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Save Changes</button>
            <a href="{{ route('work-permits.show', $permit->id) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
        </div>
    </form>
</div>
@endsection
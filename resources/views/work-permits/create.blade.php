@extends('layouts.app')

@section('title', 'New Work Permit')

@section('content')
    <h1 class="text-2xl font-bold mb-4">New Work Permit Request</h1>

    <form method="POST" action="{{ route('work-permits.store') }}" x-data="workerRows()" class="bg-white rounded shadow p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Outlet Name</label>
                <input type="text" name="outlet_name" value="{{ old('outlet_name') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Floor / Location</label>
                <input type="text" name="floor_location" value="{{ old('floor_location') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Site Incharge Name</label>
                <input type="text" name="site_incharge_name" value="{{ old('site_incharge_name') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Site Incharge Cell No</label>
                <input type="text" name="site_incharge_cell_no" value="{{ old('site_incharge_cell_no') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Site Incharge CNIC</label>
                <input type="text" name="site_incharge_cnic" value="{{ old('site_incharge_cnic') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Requested By</label>
                <input type="text" name="requested_by" value="{{ old('requested_by') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Requested By — Cell No</label>
                <input type="text" name="requested_by_cell_no" value="{{ old('requested_by_cell_no') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Valid From</label>
                <input type="date" name="valid_from" value="{{ old('valid_from') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Valid To</label>
                <input type="date" name="valid_to" value="{{ old('valid_to') }}" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Nature of Work</label>
            <textarea name="nature_of_work" rows="2" class="w-full border rounded px-3 py-2" required>{{ old('nature_of_work') }}</textarea>
        </div>

        <div class="border-t pt-4">
            <p class="text-sm text-gray-600 mb-2">
                Standard work hours: Monday to Sunday, 09:00 PM – 08:00 AM.
                Day-time work requires special permission from Mall Management.
            </p>
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="daytime_work_requested" value="1" x-model="daytimeRequested">
                <span class="text-sm">Requesting day-time work (special permission)</span>
            </label>
            <div x-show="daytimeRequested" class="mt-2">
                <label class="block text-sm font-medium mb-1">Reason for day-time work</label>
                <textarea name="daytime_work_reason" rows="2" class="w-full border rounded px-3 py-2">{{ old('daytime_work_reason') }}</textarea>
            </div>
        </div>

        <div class="border-t pt-4">
            <div class="flex items-center justify-between mb-2">
                <h2 class="font-semibold">Workers</h2>
                <button type="button" @click="addRow()" class="text-sm text-blue-600">+ Add worker</button>
            </div>

            <template x-for="(row, index) in rows" :key="index">
                <div class="grid grid-cols-12 gap-2 mb-2 items-center">
                    <input type="text" :name="`workers[${index}][worker_name]`" x-model="row.worker_name" placeholder="Worker name" class="col-span-4 border rounded px-2 py-1 text-sm" required>
                    <input type="text" :name="`workers[${index}][job_description]`" x-model="row.job_description" placeholder="Job description" class="col-span-4 border rounded px-2 py-1 text-sm" required>
                    <input type="text" :name="`workers[${index}][cnic_number]`" x-model="row.cnic_number" placeholder="CNIC number" class="col-span-3 border rounded px-2 py-1 text-sm" required>
                    <button type="button" @click="removeRow(index)" class="col-span-1 text-red-600 text-sm">✕</button>
                </div>
            </template>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Submit for Approval</button>
    </form>

    <script>
        function workerRows() {
            return {
                daytimeRequested: false,
                rows: [{ worker_name: '', job_description: '', cnic_number: '' }],
                addRow() {
                    this.rows.push({ worker_name: '', job_description: '', cnic_number: '' });
                },
                removeRow(index) {
                    if (this.rows.length > 1) this.rows.splice(index, 1);
                },
            };
        }
    </script>
@endsection

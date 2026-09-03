@extends('layouts.app')

@section('title', 'New Material Permit')

@section('content')
    <h1 class="text-2xl font-bold mb-4">New Material Inward/Outward Permit</h1>

    <form method="POST" action="{{ route('material-permits.store') }}" x-data="itemRows()" class="bg-white rounded shadow p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Shop Type</label>
                <select name="shop_type" class="w-full border rounded px-3 py-2" required>
                    <option value="retail">Retail (09:00 PM – 01:00 PM)</option>
                    <option value="food_court">Food Court (11:00 PM – 01:00 PM / 02:00 PM – 05:00 PM)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Direction</label>
                <select name="direction" class="w-full border rounded px-3 py-2" required>
                    <option value="in">IN</option>
                    <option value="out">OUT</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Shop Details</label>
                <input type="text" name="shop_details" value="{{ old('shop_details') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Dated</label>
                <input type="date" name="dated" value="{{ old('dated') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Time</label>
                <input type="time" name="time" value="{{ old('time') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Manager/Sup Name</label>
                <input type="text" name="manager_sup_name" value="{{ old('manager_sup_name') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Manager/Sup Cell No</label>
                <input type="text" name="manager_sup_cell_no" value="{{ old('manager_sup_cell_no') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">CNIC No</label>
                <input type="text" name="cnic_no" value="{{ old('cnic_no') }}" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <div class="border-t pt-4">
            <div class="flex items-center justify-between mb-2">
                <h2 class="font-semibold">Items</h2>
                <button type="button" @click="addRow()" class="text-sm text-blue-600">+ Add item</button>
            </div>

            <template x-for="(row, index) in rows" :key="index">
                <div class="grid grid-cols-12 gap-2 mb-2 items-center">
                    <input type="text" :name="`items[${index}][description]`" x-model="row.description" placeholder="Description" class="col-span-5 border rounded px-2 py-1 text-sm" required>
                    <input type="text" :name="`items[${index}][quantity]`" x-model="row.quantity" placeholder="Quantity" class="col-span-3 border rounded px-2 py-1 text-sm" required>
                    <input type="text" :name="`items[${index}][remarks]`" x-model="row.remarks" placeholder="Remarks" class="col-span-3 border rounded px-2 py-1 text-sm">
                    <button type="button" @click="removeRow(index)" class="col-span-1 text-red-600 text-sm">✕</button>
                </div>
            </template>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Submit for Approval</button>
    </form>

    <script>
        function itemRows() {
            return {
                rows: [{ description: '', quantity: '', remarks: '' }],
                addRow() {
                    this.rows.push({ description: '', quantity: '', remarks: '' });
                },
                removeRow(index) {
                    if (this.rows.length > 1) this.rows.splice(index, 1);
                },
            };
        }
    </script>
@endsection

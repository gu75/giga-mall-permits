@extends('layouts.app')

@section('title', 'Material Permit #' . $permit->id)

@section('content')
    @php $user = auth()->user(); @endphp

    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Material Permit #{{ $permit->id }}</h1>
        <a href="{{ route('material-permits.pdf', $permit) }}" class="bg-gray-800 text-white px-4 py-2 rounded text-sm">
            Download PDF
        </a>
    </div>

    <div class="bg-white rounded shadow p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <p><span class="text-gray-500">Shop:</span> {{ $permit->shop_details }} ({{ str($permit->shop_type)->replace('_',' ')->title() }})</p>
            <p><span class="text-gray-500">Direction:</span> {{ strtoupper($permit->direction) }}</p>
            <p><span class="text-gray-500">Manager/Sup:</span> {{ $permit->manager_sup_name }} ({{ $permit->manager_sup_cell_no }})</p>
            <p><span class="text-gray-500">CNIC:</span> {{ $permit->cnic_no }}</p>
            <p><span class="text-gray-500">Dated:</span> {{ $permit->dated->format('d M Y') }} {{ $permit->time?->format('h:i A') }}</p>        </div>

        <div class="border-t pt-4">
            <h2 class="font-semibold mb-2">Items</h2>
            <table class="w-full text-sm border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left p-2 border">Description</th>
                        <th class="text-left p-2 border">Quantity</th>
                        <th class="text-left p-2 border">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permit->items as $item)
                        <tr>
                            <td class="p-2 border">{{ $item->description }}</td>
                            <td class="p-2 border">{{ $item->quantity }}</td>
                            <td class="p-2 border">{{ $item->remarks }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="border-t pt-4">
            <h2 class="font-semibold mb-2">Status Trail</h2>
            <ul class="text-sm space-y-1">
                <li>
                    Operations:
                    @if ($permit->operations_approved_at)
                        ✅ {{ $permit->operationsApprover->name }} on {{ $permit->operations_approved_at->format('d M Y H:i') }}
                    @elseif ($permit->status === 'rejected')
                        ❌ Rejected by {{ $permit->rejectedBy->name }} ({{ $permit->rejectedBy->role }}) — {{ $permit->rejection_reason }}
                    @else
                        ⏳ Pending
                    @endif
                </li>
                <li>
                    Gate (Security):
                    @if ($permit->gate_logged_at)
                        ✅ {{ $permit->gateLogger->name }} on {{ $permit->gate_logged_at->format('d M Y H:i') }}
                    @else
                        ⏳ Awaiting gate clearance
                    @endif
                </li>
            </ul>
        </div>

        @if ($permit->status === 'pending_operations' && ($user->isOperations() || $user->isAdmin()))
            <div class="border-t pt-4">
                <h2 class="font-semibold mb-2">Operations Action</h2>
                <div class="flex gap-3">
                    <form method="POST" action="{{ route('material-permits.approve', $permit) }}">
                        @csrf
                        <button class="bg-green-600 text-white px-4 py-2 rounded text-sm">Approve</button>
                    </form>
                    <form method="POST" action="{{ route('material-permits.reject', $permit) }}"
                          x-data
                          @submit.prevent="const reason = prompt('Reason for rejection?'); if (reason) { $el.reason.value = reason; $el.submit(); }">
                        @csrf
                        <input type="hidden" name="reason">
                        <button class="bg-red-600 text-white px-4 py-2 rounded text-sm">Reject</button>
                    </form>
                </div>
            </div>
        @endif

        @if ($permit->status === 'approved' && ($user->isSecurity() || $user->isAdmin()))
            <div class="border-t pt-4">
                <h2 class="font-semibold mb-2">Security — Log Gate Movement</h2>
                <form method="POST" action="{{ route('material-permits.log-gate', $permit) }}" class="flex gap-3">
                    @csrf
                    <input type="text" name="remarks" placeholder="Gate remarks (optional)" class="border rounded px-3 py-2 text-sm flex-1">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded text-sm">Log {{ strtoupper($permit->direction) }}</button>
                </form>
            </div>
        @endif
    </div>
@endsection

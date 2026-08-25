@extends('layouts.app')

@section('title', 'Work Permit #' . $permit->id)

@section('content')
    @php $user = auth()->user(); @endphp

    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Work Permit #{{ $permit->id }}</h1>
        <a href="{{ route('work-permits.pdf', $permit) }}" class="bg-gray-800 text-white px-4 py-2 rounded text-sm">
            Download PDF
        </a>
    </div>

    <div class="bg-white rounded shadow p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <p><span class="text-gray-500">Outlet:</span> {{ $permit->outlet_name }}</p>
            <p><span class="text-gray-500">Floor/Location:</span> {{ $permit->floor_location }}</p>
            <p><span class="text-gray-500">Site Incharge:</span> {{ $permit->site_incharge_name }} ({{ $permit->site_incharge_cell_no }})</p>
            <p><span class="text-gray-500">CNIC:</span> {{ $permit->site_incharge_cnic }}</p>
            <p><span class="text-gray-500">Requested By:</span> {{ $permit->requested_by }} ({{ $permit->requested_by_cell_no }})</p>
            <p><span class="text-gray-500">Valid:</span> {{ $permit->valid_from->format('d M Y') }} – {{ $permit->valid_to->format('d M Y') }}</p>
        </div>

        <div class="text-sm">
            <span class="text-gray-500">Nature of Work:</span> {{ $permit->nature_of_work }}
        </div>

        @if ($permit->daytime_work_requested)
            <div class="text-sm bg-amber-50 border border-amber-200 rounded p-2">
                Day-time work requested — {{ $permit->daytime_work_reason }}
            </div>
        @endif

        <div class="border-t pt-4">
            <h2 class="font-semibold mb-2">Workers</h2>
            <table class="w-full text-sm border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left p-2 border">Name</th>
                        <th class="text-left p-2 border">Job Description</th>
                        <th class="text-left p-2 border">CNIC</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permit->workers as $worker)
                        <tr>
                            <td class="p-2 border">{{ $worker->worker_name }}</td>
                            <td class="p-2 border">{{ $worker->job_description }}</td>
                            <td class="p-2 border">{{ $worker->cnic_number }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="border-t pt-4">
            <h2 class="font-semibold mb-2">Approval Trail</h2>
            <ul class="text-sm space-y-1">
                <li>
                    Operations:
                    @if ($permit->operations_approved_at)
                        ✅ {{ $permit->operationsApprover->name }} on {{ $permit->operations_approved_at->format('d M Y H:i') }}
                    @else
                        ⏳ Pending
                    @endif
                </li>
                <li>
                    HSE:
                    @if ($permit->hse_approved_at)
                        ✅ {{ $permit->hseApprover->name }} on {{ $permit->hse_approved_at->format('d M Y H:i') }}
                    @else
                        ⏳ Pending
                    @endif
                </li>
                <li>
                    Security:
                    @if ($permit->security_approved_at)
                        ✅ {{ $permit->securityApprover->name }} on {{ $permit->security_approved_at->format('d M Y H:i') }}
                    @else
                        ⏳ Pending
                    @endif
                </li>
            </ul>

@if ($permit->status === 'rejected')
    <p class="text-sm text-red-600 mt-2">
        Rejected by {{ $permit->rejectedBy->name }} ({{ $permit->rejectedBy->role }}) — {{ $permit->rejection_reason }}
    </p>
@endif
        </div>

        @if ($permit->nextApprovalRole() === $user->role || $user->isAdmin())
            <div class="border-t pt-4">
                <h2 class="font-semibold mb-2">Your Action</h2>
                <div class="flex gap-3">
                    <form method="POST" action="{{ route('work-permits.approve', $permit) }}">
                        @csrf
                        <button class="bg-green-600 text-white px-4 py-2 rounded text-sm">Approve</button>
                    </form>
                    <form method="POST" action="{{ route('work-permits.reject', $permit) }}" x-data x-on:submit="if(!confirm('Reject this permit?')) { $event.preventDefault(); } else { $refs.reason.value = prompt('Reason for rejection?') || ''; }">
                        @csrf
                        <input type="hidden" name="reason" x-ref="reason">
                        <button class="bg-red-600 text-white px-4 py-2 rounded text-sm">Reject</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection

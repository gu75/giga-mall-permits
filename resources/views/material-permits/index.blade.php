@extends('layouts.app')

@section('title', 'Material Inward/Outward Permits')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Material Inward/Outward Permits</h1>
        @if (auth()->user()->isTenant())
            <a href="{{ route('material-permits.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm">
                + New Material Permit
            </a>
        @endif
    </div>

    <div class="bg-white rounded shadow divide-y">
        @forelse ($permits as $permit)
            <a href="{{ route('material-permits.show', $permit) }}" class="block p-4 hover:bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold">{{ $permit->shop_details }} — {{ strtoupper($permit->direction) }}</p>
                        <p class="text-sm text-gray-500">
                            {{ $permit->tenant->name }} · {{ $permit->dated->format('d M Y') }}
                        </p>
                    </div>
                    <span @class([
                        'text-xs font-medium px-2 py-1 rounded',
                        'bg-yellow-100 text-yellow-800' => $permit->status === 'pending_operations',
                        'bg-blue-100 text-blue-800' => $permit->status === 'approved',
                        'bg-green-100 text-green-800' => $permit->status === 'gate_cleared',
                        'bg-red-100 text-red-800' => $permit->status === 'rejected',
                    ])>
                        {{ str($permit->status)->replace('_', ' ')->title() }}
                    </span>
                </div>
            </a>
        @empty
            <p class="p-4 text-gray-500 text-sm">No material permits found.</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $permits->links() }}
    </div>
@endsection

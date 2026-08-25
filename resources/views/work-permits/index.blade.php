@extends('layouts.app')

@section('title', 'Work Permits')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Work Permits</h1>
        @if (auth()->user()->isTenant())
            <a href="{{ route('work-permits.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm">
                + New Work Permit
            </a>
        @endif
    </div>

    <div class="bg-white rounded shadow divide-y">
        @forelse ($permits as $permit)
            <a href="{{ route('work-permits.show', $permit) }}" class="block p-4 hover:bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold">{{ $permit->outlet_name }} — {{ $permit->nature_of_work }}</p>
                        <p class="text-sm text-gray-500">
                            {{ $permit->tenant->name }} · {{ $permit->valid_from->format('d M Y') }} to {{ $permit->valid_to->format('d M Y') }}
                        </p>
                    </div>
                    <span @class([
                        'text-xs font-medium px-2 py-1 rounded',
                        'bg-yellow-100 text-yellow-800' => str_starts_with($permit->status, 'pending'),
                        'bg-green-100 text-green-800' => $permit->status === 'approved',
                        'bg-red-100 text-red-800' => $permit->status === 'rejected',
                    ])>
                        {{ str($permit->status)->replace('_', ' ')->title() }}
                    </span>
                </div>
            </a>
        @empty
            <p class="p-4 text-gray-500 text-sm">No work permits found.</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $permits->links() }}
    </div>
@endsection

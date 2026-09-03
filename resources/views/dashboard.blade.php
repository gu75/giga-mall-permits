@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

@if ($dashboardType === 'tenant')

    <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#C8A951]">Tenant Dashboard</p>
            <h1 class="mt-2 text-3xl font-extrabold text-[#0A2342]">Welcome back, {{ $user->name }}</h1>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('work-permits.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0A2342] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-[#0A2342]/15 transition hover:-translate-y-0.5">
                <span>➕</span> Request Work Permit
            </a>
            <a href="{{ route('material-permits.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#C8A951] px-5 py-3 text-sm font-bold text-[#0A2342] shadow-lg shadow-[#C8A951]/25 transition hover:-translate-y-0.5">
                <span>➕</span> Request Material Pass
            </a>
        </div>
    </div>

    <div class="space-y-8">
        <section class="rounded-2xl border border-[#E2E8F0] bg-white p-6 shadow-sm">
            <div class="mb-6 flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-2xl font-bold text-[#0A2342]">Work Permits</h2>
                    <p class="text-sm text-gray-500">Operations → HSE → Security</p>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="mb-3 text-lg font-bold text-[#0A2342]">Active</h3>
                @if ($activeWorkPermits->isEmpty())
                    <div class="rounded-xl border border-dashed border-[#D6DCE6] bg-[#F8FAFC] p-4 text-sm text-gray-500">No active work permits.</div>
                @else
                    <div class="space-y-4">
                        @foreach ($activeWorkPermits as $permit)
                            @php
                                $workStatus = match ($permit->status) {
                                    'pending_operations' => ['steps' => ['Operations', 'HSE', 'Security'], 'currentStep' => 0, 'status' => 'Awaiting Operations review'],
                                    'pending_hse' => ['steps' => ['Operations', 'HSE', 'Security'], 'currentStep' => 1, 'status' => 'Approved by Operations, waiting on HSE'],
                                    'pending_security' => ['steps' => ['Operations', 'HSE', 'Security'], 'currentStep' => 2, 'status' => 'Approved by HSE, waiting on Security'],
                                    default => ['steps' => ['Operations', 'HSE', 'Security'], 'currentStep' => 0, 'status' => 'In progress'],
                                };
                            @endphp

                            <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-4">
                                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-gray-500">Work Permit #{{ $permit->id }}</p>
                                        <h4 class="mt-1 text-lg font-bold text-[#0A2342]">{{ $permit->outlet_name }}</h4>
                                    </div>
                                    <a href="{{ route('work-permits.show', $permit) }}" class="text-sm font-bold text-[#0A2342] hover:underline">View</a>
                                </div>

                                @include('partials.approval-progress', $workStatus)
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <h3 class="mb-3 text-lg font-bold text-[#0A2342]">History</h3>
                @if ($historyWorkPermits->isEmpty())
                    <div class="rounded-xl border border-dashed border-[#D6DCE6] bg-[#F8FAFC] p-4 text-sm text-gray-500">No work permit history yet.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm text-gray-700">
                            <thead class="bg-[#F8FAFC] text-xs uppercase tracking-wide text-gray-600">
                                <tr>
                                    <th class="px-4 py-3 font-semibold">Date</th>
                                    <th class="px-4 py-3 font-semibold">Outlet</th>
                                    <th class="px-4 py-3 font-semibold">Status</th>
                                    <th class="px-4 py-3 font-semibold">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($historyWorkPermits as $permit)
                                    <tr class="border-t border-[#E2E8F0]">
                                        <td class="px-4 py-3">{{ $permit->valid_from?->format('d M Y') ?? '-' }}</td>
                                        <td class="px-4 py-3 font-semibold text-[#0A2342]">{{ $permit->outlet_name }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-bold {{ $permit->status === 'rejected' ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700' }}">
                                                {{ $permit->status === 'rejected' ? 'Rejected' : 'Approved' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                <a href="{{ route('work-permits.show', $permit) }}" class="font-semibold text-[#0A2342] hover:underline">View</a>
                                                <a href="{{ route('work-permits.pdf', $permit) }}" class="font-semibold text-[#0A2342] hover:underline">PDF</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </section>

        <section class="rounded-2xl border border-[#E2E8F0] bg-white p-6 shadow-sm">
            <div class="mb-6 flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-2xl font-bold text-[#0A2342]">Material Permits</h2>
                    <p class="text-sm text-gray-500">Operations → Gate</p>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="mb-3 text-lg font-bold text-[#0A2342]">Active</h3>
                @if ($activeMaterialPermits->isEmpty())
                    <div class="rounded-xl border border-dashed border-[#D6DCE6] bg-[#F8FAFC] p-4 text-sm text-gray-500">No active material permits.</div>
                @else
                    <div class="space-y-4">
                        @foreach ($activeMaterialPermits as $permit)
                            @php
                                $materialStatus = match ($permit->status) {
                                    'pending_operations' => ['steps' => ['Operations', 'Gate'], 'currentStep' => 0, 'status' => 'Awaiting Operations approval'],
                                    'approved' => ['steps' => ['Operations', 'Gate'], 'currentStep' => 1, 'status' => 'Approved by Operations, waiting on gate'],
                                    default => ['steps' => ['Operations', 'Gate'], 'currentStep' => 0, 'status' => 'In progress'],
                                };
                            @endphp

                            <div class="rounded-2xl border border-[#E2E8F0] bg-[#F8FAFC] p-4">
                                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-gray-500">Material Permit #{{ $permit->id }}</p>
                                        <h4 class="mt-1 text-lg font-bold text-[#0A2342]">{{ $permit->shop_details }}</h4>
                                    </div>
                                    <a href="{{ route('material-permits.show', $permit) }}" class="text-sm font-bold text-[#0A2342] hover:underline">View</a>
                                </div>

                                @include('partials.approval-progress', $materialStatus)
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <h3 class="mb-3 text-lg font-bold text-[#0A2342]">History</h3>
                @if ($historyMaterialPermits->isEmpty())
                    <div class="rounded-xl border border-dashed border-[#D6DCE6] bg-[#F8FAFC] p-4 text-sm text-gray-500">No material permit history yet.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm text-gray-700">
                            <thead class="bg-[#F8FAFC] text-xs uppercase tracking-wide text-gray-600">
                                <tr>
                                    <th class="px-4 py-3 font-semibold">Date</th>
                                    <th class="px-4 py-3 font-semibold">Shop</th>
                                    <th class="px-4 py-3 font-semibold">Direction</th>
                                    <th class="px-4 py-3 font-semibold">Status</th>
                                    <th class="px-4 py-3 font-semibold">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($historyMaterialPermits as $permit)
                                    <tr class="border-t border-[#E2E8F0]">
                                        <td class="px-4 py-3">{{ $permit->dated?->format('d M Y') ?? '-' }}</td>
                                        <td class="px-4 py-3 font-semibold text-[#0A2342]">{{ $permit->shop_details }}</td>
                                        <td class="px-4 py-3 uppercase">{{ $permit->direction }}</td>
                                        <td class="px-4 py-3">
                                            @php
                                                $statusLabel = match ($permit->status) {
                                                    'rejected' => 'Rejected',
                                                    'gate_cleared' => 'Gate Cleared',
                                                    default => 'Approved',
                                                };
                                                $badgeClass = $permit->status === 'rejected' ? 'bg-red-50 text-red-700' : ($permit->status === 'gate_cleared' ? 'bg-green-50 text-green-700' : 'bg-emerald-50 text-emerald-700');
                                            @endphp
                                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-bold {{ $badgeClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                <a href="{{ route('material-permits.show', $permit) }}" class="font-semibold text-[#0A2342] hover:underline">View</a>
                                                <a href="{{ route('material-permits.pdf', $permit) }}" class="font-semibold text-[#0A2342] hover:underline">PDF</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </section>
    </div>

@elseif ($dashboardType === 'manager')

    <h1 class="text-2xl font-bold mb-4">{{ ucfirst(auth()->user()->role) }} Dashboard</h1>

    <div class="mb-8">
        @if ($totalPendingCount > 0)
            <a href="{{ auth()->user()->isSecurity() && $pendingMaterialCount > $pendingWorkCount
                    ? route('material-permits.index')
                    : route('work-permits.index') }}"
               class="block bg-red-600 hover:bg-red-700 text-white rounded-lg p-6 shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl font-bold">{{ $totalPendingCount }}</p>
                        <p class="text-sm opacity-90">
                            Permit{{ $totalPendingCount === 1 ? '' : 's' }} Require Your
                            {{ auth()->user()->isSecurity() ? 'Signature / Gate Action' : 'Signature' }}
                        </p>
                    </div>
                    <span class="text-4xl">🔴</span>
                </div>
                <div class="flex gap-4 mt-3 text-xs opacity-90">
                    @if ($pendingWorkCount > 0)
                        <span>{{ $pendingWorkCount }} Work Permit{{ $pendingWorkCount === 1 ? '' : 's' }}</span>
                    @endif
                    @if ($pendingMaterialCount > 0)
                        <span>{{ $pendingMaterialCount }} Material Permit{{ $pendingMaterialCount === 1 ? '' : 's' }}</span>
                    @endif
                </div>
            </a>
        @else
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-6 text-center">
                ✅ You're all caught up — nothing pending your approval.
            </div>
        @endif
    </div>

    <div class="grid grid-cols-2 gap-4 mb-8">
        <a href="{{ route('work-permits.index') }}" class="bg-white rounded shadow p-4 text-center hover:bg-gray-50">
            View All Work Permits
        </a>
        <a href="{{ route('material-permits.index') }}" class="bg-white rounded shadow p-4 text-center hover:bg-gray-50">
            View All Material Permits
        </a>
    </div>

        @if ($recentActivity->isEmpty())
            <p class="text-sm text-gray-500">You haven't approved or rejected any permits yet.</p>
        @else
            <div class="bg-white rounded shadow divide-y">
                @foreach ($recentActivity as $activity)
                    <a href="{{ $activity['route'] }}" class="flex items-center justify-between p-4 hover:bg-gray-50">
                        <div>
                            <p class="font-medium">{{ $activity['ref'] }} — {{ $activity['label'] }}</p>
                            <p class="text-xs text-gray-500">{{ $activity['type'] }} · {{ $activity['when']->diffForHumans() }}</p>
                        </div>
                        <span @class([
                            'text-xs font-medium px-2 py-1 rounded',
                            'bg-green-100 text-green-800' => in_array($activity['status'], ['approved', 'gate_cleared']),
                            'bg-red-100 text-red-800' => $activity['status'] === 'rejected',
                            'bg-yellow-100 text-yellow-800' => ! in_array($activity['status'], ['approved', 'gate_cleared', 'rejected']),
                        ])>{{ str($activity['status'])->replace('_', ' ')->title() }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

@else

    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
    <div class="bg-white rounded shadow p-6">
        <p class="text-gray-600 mb-4">Welcome, {{ auth()->user()->name }}.</p>
        <div class="flex gap-4">
            <a href="{{ route('work-permits.index') }}" class="text-blue-600 hover:underline">Work Permits</a>
            <a href="{{ route('material-permits.index') }}" class="text-blue-600 hover:underline">Material Permits</a>
        </div>
    </div>

@endif

@endsection
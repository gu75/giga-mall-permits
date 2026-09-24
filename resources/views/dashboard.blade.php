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

    <div class="mb-6 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#C8A951]">Management Dashboard</p>
            <h1 class="mt-1 text-3xl font-extrabold text-[#0A2342]">{{ ucfirst(auth()->user()->role) }} Overview</h1>
        </div>

        @if (auth()->user()->isOperations() || auth()->user()->isAdmin())
            <a href="{{ route('tenants.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0A2342] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-[#0A2342]/15 transition hover:-translate-y-0.5">
                <span>👥</span> Manage Tenants
            </a>
        @endif
    </div>

    <div class="grid grid-cols-2 gap-4 mb-6 md:grid-cols-4">
        <div class="rounded-xl p-5 text-white shadow-sm" style="background:#0A2342;">
            <div class="text-3xl font-extrabold">{{ $stats['total'] }}</div>
            <div class="mt-1 text-xs font-semibold uppercase tracking-wider opacity-80">
                {{ $permitType === 'material' ? 'Material Permits' : 'Work Permits' }}
            </div>
        </div>
        <div class="rounded-xl p-5 text-white shadow-sm bg-emerald-600">
            <div class="text-3xl font-extrabold">{{ $stats['approved'] }}</div>
            <div class="mt-1 text-xs font-semibold uppercase tracking-wider opacity-80">Approved</div>
        </div>
        <div class="rounded-xl p-5 text-white shadow-sm bg-amber-500">
            <div class="text-3xl font-extrabold">{{ $stats['pending'] }}</div>
            <div class="mt-1 text-xs font-semibold uppercase tracking-wider opacity-80">Pending</div>
        </div>
        <div class="rounded-xl p-5 text-white shadow-sm bg-red-600">
            <div class="text-3xl font-extrabold">{{ $stats['rejected'] }}</div>
            <div class="mt-1 text-xs font-semibold uppercase tracking-wider opacity-80">Rejected</div>
        </div>
    </div>

    <div class="rounded-xl bg-white shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('dashboard') }}" class="flex flex-col gap-3 md:flex-row md:items-end">
            @if ($canSeeMaterial)
                <input type="hidden" name="type" value="{{ $permitType }}">
                <div class="flex gap-2">
                    <a href="{{ request()->fullUrlWithQuery(['type' => 'work', 'status' => 'all']) }}"
                       @class(['px-3 py-2 rounded text-sm font-semibold', 'bg-[#0A2342] text-white' => $permitType === 'work', 'bg-gray-100 text-gray-600' => $permitType !== 'work'])>
                        Work Permits
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['type' => 'material', 'status' => 'all']) }}"
                       @class(['px-3 py-2 rounded text-sm font-semibold', 'bg-[#0A2342] text-white' => $permitType === 'material', 'bg-gray-100 text-gray-600' => $permitType !== 'material'])>
                        Material Permits
                    </a>
                </div>
            @else
                <input type="hidden" name="type" value="work">
            @endif

            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Search</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Outlet / shop name..."
                       class="w-full border rounded px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Status</label>
                <select name="status" onchange="this.form.submit()" class="border rounded px-3 py-2 text-sm">
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}" {{ $statusFilter === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-[#0A2342] text-white px-4 py-2 rounded text-sm font-semibold">
                Filter
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            @if ($permits->isEmpty())
                <div class="rounded-xl border border-dashed border-[#D6DCE6] bg-[#F8FAFC] p-6 text-center text-sm text-gray-500">
                    No permits match your filters.
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($permits as $permit)
                        @php
                            $borderColor = match(true) {
                                $permit->status === 'approved' || $permit->status === 'gate_cleared' => '#16A34A',
                                $permit->status === 'rejected' => '#DC2626',
                                default => '#D97706',
                            };
                            $badgeClass = match(true) {
                                $permit->status === 'approved' || $permit->status === 'gate_cleared' => 'bg-emerald-50 text-emerald-700',
                                $permit->status === 'rejected' => 'bg-red-50 text-red-700',
                                default => 'bg-amber-50 text-amber-700',
                            };
                            $showRoute = $permitType === 'material' ? route('material-permits.show', $permit) : route('work-permits.show', $permit);
                            $pdfRoute = $permitType === 'material' ? route('material-permits.pdf', $permit) : route('work-permits.pdf', $permit);
                            $title = $permitType === 'material' ? $permit->shop_details : $permit->outlet_name;
                        @endphp

                        <div class="bg-white rounded-xl shadow-sm p-4" style="border-left: 5px solid {{ $borderColor }};">
                            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <p class="font-bold text-[#0A2342]">
                                        {{ $title }}
                                        <span class="ml-2 inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold {{ $badgeClass }}">
                                            {{ str($permit->status)->replace('_', ' ')->title() }}
                                        </span>
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Tenant: {{ $permit->tenant->name ?? '—' }}
                                        @if ($permitType === 'work')
                                            &nbsp;|&nbsp; {{ $permit->valid_from?->format('d M Y') }} {{ $permit->valid_from_time?->format('h:i A') }}
                                        @else
                                            &nbsp;|&nbsp; {{ strtoupper($permit->direction) }} &nbsp;|&nbsp; {{ $permit->dated?->format('d M Y') }} {{ $permit->time?->format('h:i A') }}
                                        @endif
                                    </p>
                                </div>
                                <div class="flex gap-3 shrink-0">
                                    <a href="{{ $showRoute }}" class="text-sm font-semibold text-[#0A2342] hover:underline">View</a>
                                    <a href="{{ $pdfRoute }}" class="text-sm font-semibold text-[#0A2342] hover:underline">PDF</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $permits->links() }}
                </div>
            @endif
        </div>

        <div>
            <div class="bg-white rounded-xl shadow-sm">
                <div class="px-4 py-3 border-b border-[#E2E8F0]">
                    <h3 class="font-bold text-[#0A2342]">Recent Activity</h3>
                </div>
                <ul class="max-h-[600px] overflow-y-auto divide-y divide-[#E2E8F0]">
                    @forelse ($recentActivity as $activity)
                        <li>
                            <a href="{{ $activity['route'] }}" class="block px-4 py-3 hover:bg-gray-50">
                                <p class="text-sm font-semibold text-[#0A2342]">{{ $activity['ref'] }} — {{ $activity['label'] }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $activity['type'] }} · {{ $activity['when']->diffForHumans() }}</p>
                            </a>
                        </li>
                    @empty
                        <li class="px-4 py-6 text-center text-sm text-gray-500">No recent activity yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

@endif

@endsection
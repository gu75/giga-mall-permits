@extends('layouts.app')

@section('title', 'Tenant Management')

@section('content')
<div class="max-w-6xl mx-auto py-4">
    <div class="mb-6 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#C8A951]">Tenant Management</p>
            <h1 class="mt-1 text-3xl font-extrabold text-[#0A2342]">All Tenants</h1>
        </div>
        <div>
            <a href="{{ route('tenants.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0A2342] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-[#0A2342]/15 transition hover:-translate-y-0.5">
                <span>➕</span> Add New Tenant
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-lg bg-emerald-50 text-emerald-700 px-4 py-3 text-sm font-semibold border border-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-50 text-red-700 px-4 py-3 text-sm font-semibold border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="rounded-xl bg-white shadow-sm p-4 mb-6 border border-[#E2E8F0]">
        <form method="GET" action="{{ route('tenants.index') }}" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, shop, email, cell no..."
                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0A2342]">
            <button type="submit" class="bg-[#0A2342] text-white px-5 py-2 rounded-lg text-sm font-semibold">
                Search
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-[#E2E8F0]">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-[#0A2342] text-white">
                    <tr>
                        <th class="px-4 py-3 font-bold">Name</th>
                        <th class="px-4 py-3 font-bold">Shop Name</th>
                        <th class="px-4 py-3 font-bold">Floor/Location</th>
                        <th class="px-4 py-3 font-bold">Cell No</th>
                        <th class="px-4 py-3 font-bold">Email</th>
                        <th class="px-4 py-3 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0]">
                    @forelse ($tenants as $tenant)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-bold text-[#0A2342]">{{ $tenant->name }}</td>
                            <td class="px-4 py-3">{{ $tenant->shop_name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $tenant->floor_location ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $tenant->cell_no ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $tenant->email }}</td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <a href="{{ route('tenants.edit', $tenant) }}" class="font-semibold text-[#0A2342] hover:underline mr-3">Edit</a>
                                <form action="{{ route('tenants.destroy', $tenant) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Delete this tenant? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-semibold text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">No tenants found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $tenants->links() }}
    </div>
</div>
@endsection
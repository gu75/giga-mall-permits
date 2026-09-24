<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('search', ''));

        $tenants = User::where('role', 'tenant')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('shop_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('cell_no', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('tenants.index', [
            'tenants' => $tenants,
            'search' => $search,
        ]);
    }

    public function edit(User $tenant): View
    {
        abort_unless($tenant->role === 'tenant', 404);

        return view('tenants.edit', [
            'tenant' => $tenant,
        ]);
    }

    public function update(Request $request, User $tenant): RedirectResponse
    {
        abort_unless($tenant->role === 'tenant', 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
           'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $tenant->id],
            'shop_name' => ['required', 'string', 'max:255'],
            'floor_location' => ['required', 'string', 'max:255'],

        ]);

        $tenant->update($validated);

        return redirect()->route('tenants.index')->with('status', 'Tenant updated successfully.');
    }

    public function destroy(User $tenant): RedirectResponse
    {
        abort_unless($tenant->role === 'tenant', 404);

        $hasPermits = $tenant->workPermits()->exists() || $tenant->materialPermits()->exists();

        if ($hasPermits) {
            return redirect()->route('tenants.index')
                ->with('error', 'This tenant has existing work/material permits and cannot be deleted. Remove or reassign their permits first.');
        }

        $tenant->delete();

        return redirect()->route('tenants.index')->with('status', 'Tenant deleted successfully.');
    }
        public function create(): View
    {
        return view('tenants.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'shop_name' => ['required', 'string', 'max:255'],
            'floor_location' => ['required', 'string', 'max:255'],
            'cell_no' => ['required', 'string', 'max:20'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role' => 'tenant',
            'shop_name' => $validated['shop_name'],
            'floor_location' => $validated['floor_location'],
            'cell_no' => $validated['cell_no'],
            'email_verified_at' => now(),
        ]);

        return redirect()->route('tenants.index')->with('status', 'Tenant created successfully.');
    }
}
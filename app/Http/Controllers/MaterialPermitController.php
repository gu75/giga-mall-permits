<?php

namespace App\Http\Controllers;

use App\Models\MaterialPermit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaterialPermitController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = MaterialPermit::with(['tenant', 'items'])->latest();

        if ($user->isTenant()) {
            $query->where('tenant_id', $user->id);
        } elseif ($user->isOperations()) {
            $query->where('status', 'pending_operations')->orWhere('operations_approved_by', $user->id);
        } elseif ($user->isSecurity()) {
            $query->whereIn('status', ['approved', 'gate_cleared']);
        }

        return view('material-permits.index', [
            'permits' => $query->paginate(15),
        ]);
    }

    public function create()
    {
        return view('material-permits.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shop_type' => 'required|in:retail,food_court',
            'shop_details' => 'required|string|max:255',
            'manager_sup_name' => 'required|string|max:255',
            'manager_sup_cell_no' => 'required|string|max:50',
            'cnic_no' => 'required|string|max:20',
            'dated' => 'required|date',
            'time' => 'required',
            'direction' => 'required|in:in,out',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|string|max:50',
            'items.*.remarks' => 'nullable|string|max:255',
            'terms_accepted' => 'required|accepted',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $permit = MaterialPermit::create([
                ...collect($validated)->except('items')->toArray(),
                'tenant_id' => $request->user()->id,
                'status' => 'pending_operations',
            ]);

            foreach ($validated['items'] as $item) {
                $permit->items()->create($item);
            }
        });

        return redirect()->route('material-permits.index')
            ->with('success', 'Material permit request submitted for approval.');
    }

    public function show(MaterialPermit $materialPermit)
    {
        $this->authorizeView($materialPermit);

        return view('material-permits.show', ['permit' => $materialPermit->load(['tenant', 'items', 'rejectedBy'])]);
    }

    public function approve(Request $request, MaterialPermit $materialPermit)
    {
        $user = $request->user();

        if (! $user->isAdmin() && ! $user->isOperations()) {
            abort(403);
        }

        if ($materialPermit->status !== 'pending_operations') {
            abort(403, 'This permit has already been actioned.');
        }

        $request->validate(['remarks' => 'nullable|string']);

        $materialPermit->approve($user, $request->input('remarks'));

        return back()->with('success', 'Approval recorded.');
    }

    public function reject(Request $request, MaterialPermit $materialPermit)
    {
        $user = $request->user();

        if (! $user->isAdmin() && ! $user->isOperations()) {
            abort(403);
        }

        $validated = $request->validate(['reason' => 'required|string']);

        $materialPermit->reject($user, $validated['reason']);

        return back()->with('success', 'Permit rejected.');
    }

    public function logGate(Request $request, MaterialPermit $materialPermit)
    {
        $user = $request->user();

        if (! $user->isAdmin() && ! $user->isSecurity()) {
            abort(403);
        }

        if ($materialPermit->status !== 'approved') {
            abort(403, 'This permit must be approved by Operations first.');
        }

        $request->validate(['remarks' => 'nullable|string']);

        $materialPermit->logGatePass($user, $request->input('remarks'));

        return back()->with('success', 'Gate movement logged.');
    }

    public function pdf(MaterialPermit $materialPermit)
    {
        $this->authorizeView($materialPermit);

        $pdf = Pdf::loadView('material-permits.pdf', [
            'permit' => $materialPermit->load(['tenant', 'items', 'operationsApprover', 'gateLogger']),
        ])->setPaper('a4');

        return $pdf->download("material-permit-{$materialPermit->id}.pdf");
    }

    private function authorizeView(MaterialPermit $materialPermit): void
    {
        $user = Auth::user();

        if ($user->isTenant() && $materialPermit->tenant_id !== $user->id) {
            abort(403);
        }
    }
}

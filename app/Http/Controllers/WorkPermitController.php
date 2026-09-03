<?php

namespace App\Http\Controllers;

use App\Models\WorkPermit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkPermitController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = WorkPermit::with(['tenant', 'workers'])->latest();

        if ($user->isTenant()) {
            $query->where('tenant_id', $user->id);
        } elseif ($user->isOperations()) {
            $query->where('status', 'pending_operations')->orWhere('operations_approved_by', $user->id);
        } elseif (in_array($user->role, WorkPermit::REQUIRED_DEPARTMENTS, true)) {
            $query->where(function ($q) use ($user) {
                $q->where('status', 'in_review')
                  ->whereDoesntHave('departmentApprovals', fn ($sub) => $sub->where('department', $user->role));
            })->orWhereHas('departmentApprovals', fn ($sub) => $sub->where('department', $user->role)->where('actioned_by', $user->id));
        }
        // admin sees everything

        return view('work-permits.index', [
            'permits' => $query->paginate(15),
        ]);
    }

    public function create()
    {
        return view('work-permits.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'outlet_name' => 'required|string|max:255',
            'floor_location' => 'required|string|max:255',
            'site_incharge_name' => 'required|string|max:255',
            'site_incharge_cell_no' => 'required|string|max:50',
            'site_incharge_cnic' => 'required|string|max:20',
            'nature_of_work' => 'required|string',
            'requested_by' => 'required|string|max:255',
            'requested_by_cell_no' => 'required|string|max:50',
            'valid_from' => 'required|date',
            'valid_from_time' => 'required',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'valid_to_time' => 'required',
            'daytime_work_requested' => 'nullable|boolean',
            'daytime_work_reason' => 'nullable|required_if:daytime_work_requested,1|string',
            'workers' => 'required|array|min:1',
            'workers.*.worker_name' => 'required|string|max:255',
            'workers.*.job_description' => 'required|string|max:255',
            'workers.*.cnic_number' => 'required|string|max:20',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $permit = WorkPermit::create([
                ...collect($validated)->except('workers')->toArray(),
                'tenant_id' => $request->user()->id,
                'daytime_work_requested' => $request->boolean('daytime_work_requested'),
                'status' => 'pending_operations',
            ]);

            foreach ($validated['workers'] as $worker) {
                $permit->workers()->create($worker);
            }
        });

        return redirect()->route('work-permits.index')
            ->with('success', 'Work permit request submitted for approval.');
    }

    public function show(WorkPermit $workPermit)
    {
        $this->authorizeView($workPermit);

        return view('work-permits.show', ['permit' => $workPermit->load(['tenant', 'workers', 'rejectedBy', 'departmentApprovals.actionedBy'])]);    }

    public function approve(Request $request, WorkPermit $workPermit)
    {
        $user = $request->user();

        if (! $user->isAdmin() && ! $workPermit->canDepartmentAct($user->role)) {
            abort(403, 'This permit is not awaiting your approval.');
        }

        $request->validate(['remarks' => 'nullable|string']);

        $workPermit->approve($user, $request->input('remarks'));

        return back()->with('success', 'Approval recorded.');
    }

    public function reject(Request $request, WorkPermit $workPermit)
    {
        $user = $request->user();

        if (! $user->isAdmin() && ! $workPermit->canDepartmentAct($user->role)) {
            abort(403, 'This permit is not awaiting your action.');
        }

        $validated = $request->validate(['reason' => 'required|string']);

        $workPermit->reject($user, $validated['reason']);

        return back()->with('success', 'Permit rejected.');
    }

    public function pdf(WorkPermit $workPermit)
    {
        $this->authorizeView($workPermit);

        $pdf = Pdf::loadView('work-permits.pdf', [
            'permit' => $workPermit->load(['tenant', 'workers', 'operationsApprover', 'departmentApprovals.actionedBy']),
        ])->setPaper('a4');

        return $pdf->download("work-permit-{$workPermit->id}.pdf");
    }

    private function authorizeView(WorkPermit $workPermit): void
    {
        $user = Auth::user();

        if ($user->isTenant() && $workPermit->tenant_id !== $user->id) {
            abort(403);
        }
    }
public function edit(WorkPermit $workPermit)
    {
        // Security check: Only Admin or Operations can edit
        if (!in_array(auth()->user()->role ?? '', ['admin', 'operations', 'operation'])) {
            abort(403, 'Unauthorized. Only Admin and Operations can edit permits.');
        }

        return view('work-permits.edit', ['permit' => $workPermit]);
    }

public function update(Request $request, WorkPermit $workPermit)
    {
        // Security check
        if (!in_array(auth()->user()->role ?? '', ['admin', 'operations', 'operation'])) {
            abort(403, 'Unauthorized. Only Admin and Operations can edit permits.');
        }

        $validated = $request->validate([
            'outlet_name' => 'required|string',
            'floor_location' => 'required|string',
            'site_incharge_name' => 'required|string',
            'site_incharge_cell_no' => 'required|string',
            'site_incharge_cnic' => 'required|string',
            'nature_of_work' => 'required|string',
            'requested_by' => 'required|string',
            'requested_by_cell_no' => 'required|string',
            'valid_from' => 'required|date',
            'valid_from_time' => 'required',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'valid_to_time' => 'required',
        ]);

        $workPermit->update($validated);

        return redirect()->route('work-permits.show', $workPermit->id)
                         ->with('success', 'Work Permit updated successfully.');
    }
}

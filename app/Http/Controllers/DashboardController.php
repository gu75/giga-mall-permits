<?php

namespace App\Http\Controllers;

use App\Models\MaterialPermit;
use App\Models\User;
use App\Models\WorkPermit;
use App\Models\WorkPermitDepartmentApproval;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->isTenant()) {
            return $this->tenantDashboard($user);
        }

        // Operations, HSE, Security, Admin all get the management dashboard.
        return $this->managerDashboard($request, $user);
    }

    private function tenantDashboard(User $user)
    {
        $activeWorkPermits = WorkPermit::where('tenant_id', $user->id)
            ->whereNotIn('status', ['approved', 'rejected'])
            ->latest()
            ->get();

        $historyWorkPermits = WorkPermit::where('tenant_id', $user->id)
            ->whereIn('status', ['approved', 'rejected'])
            ->latest()
            ->get();

        $activeMaterialPermits = MaterialPermit::where('tenant_id', $user->id)
            ->whereNotIn('status', ['approved', 'gate_cleared', 'rejected'])
            ->latest()
            ->get();

        $historyMaterialPermits = MaterialPermit::where('tenant_id', $user->id)
            ->whereIn('status', ['approved', 'gate_cleared', 'rejected'])
            ->latest()
            ->get();

        return view('dashboard', [
            'dashboardType' => 'tenant',
            'user' => $user,
            'activeWorkPermits' => $activeWorkPermits,
            'historyWorkPermits' => $historyWorkPermits,
            'activeMaterialPermits' => $activeMaterialPermits,
            'historyMaterialPermits' => $historyMaterialPermits,
        ]);
    }

    /**
     * Work permits this user is allowed to see, BEFORE search/status filters.
     * Wrapped in a closure so it composes safely with further ->where() calls.
     */
    private function workPermitScope(User $user)
    {
        $query = WorkPermit::query();

        if ($user->isAdmin()) {
            return $query;
        }

        if ($user->isOperations()) {
            return $query->where(fn ($q) => $q->where('status', 'pending_operations')
                ->orWhere('operations_approved_by', $user->id));
        }

        if (in_array($user->role, WorkPermit::REQUIRED_DEPARTMENTS, true)) {
            return $query->where(fn ($q) => $q->where('status', 'in_review')
                ->orWhereHas('departmentApprovals', fn ($d) => $d->where('department', $user->role)->where('actioned_by', $user->id)));
        }

        return $query->whereRaw('1 = 0');
    }

    /**
     * Material permits this user is allowed to see, BEFORE search/status filters.
     */
    private function materialPermitScope(User $user)
    {
        $query = MaterialPermit::query();

        if ($user->isAdmin()) {
            return $query;
        }

        if ($user->isOperations()) {
            return $query->where(fn ($q) => $q->where('status', 'pending_operations')
                ->orWhere('operations_approved_by', $user->id));
        }

        if ($user->role === 'security') {
            return $query->where(fn ($q) => $q->where('status', 'approved')
                ->orWhere('gate_logged_by', $user->id));
        }

        // HSE (and anyone else) never touches material permits.
        return $query->whereRaw('1 = 0');
    }

    private function managerDashboard(Request $request, User $user)
    {
        $canSeeMaterial = $user->isAdmin() || $user->isOperations() || $user->role === 'security';

        $type = $request->get('type', 'work');
        if (! $canSeeMaterial || ! in_array($type, ['work', 'material'], true)) {
            $type = 'work';
        }

        $search = trim((string) $request->get('search', ''));
        $status = $request->get('status', 'all');

        if ($type === 'material') {
            $baseQuery = $this->materialPermitScope($user);

            $stats = [
                'total' => (clone $baseQuery)->count(),
                'approved' => (clone $baseQuery)->whereIn('status', ['approved', 'gate_cleared'])->count(),
                'pending' => (clone $baseQuery)->whereNotIn('status', ['approved', 'gate_cleared', 'rejected'])->count(),
                'rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
            ];

            $listQuery = (clone $baseQuery)->with('tenant');

            if ($search !== '') {
                $listQuery->where('shop_details', 'like', "%{$search}%");
            }
            if ($status !== 'all') {
                $listQuery->where('status', $status);
            }

            $permits = $listQuery->latest()->paginate(10)->withQueryString();

            $statusOptions = [
                'all' => 'All Status',
                'pending_operations' => 'Pending Operations',
                'approved' => 'Approved (Awaiting Gate)',
                'gate_cleared' => 'Gate Cleared',
                'rejected' => 'Rejected',
            ];
        } else {
            $baseQuery = $this->workPermitScope($user);

            $stats = [
                'total' => (clone $baseQuery)->count(),
                'approved' => (clone $baseQuery)->where('status', 'approved')->count(),
                'pending' => (clone $baseQuery)->whereIn('status', ['pending_operations', 'in_review'])->count(),
                'rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
            ];

            $listQuery = (clone $baseQuery)->with('tenant');

            if ($search !== '') {
                $listQuery->where('outlet_name', 'like', "%{$search}%");
            }
            if ($status !== 'all') {
                $listQuery->where('status', $status);
            }

            $permits = $listQuery->latest()->paginate(10)->withQueryString();

            $statusOptions = [
                'all' => 'All Status',
                'pending_operations' => 'Pending Operations',
                'in_review' => 'In Review (Departments)',
                'approved' => 'Approved',
                'rejected' => 'Rejected',
            ];
        }

        return view('dashboard', [
            'dashboardType' => 'manager',
            'permitType' => $type,
            'canSeeMaterial' => $canSeeMaterial,
            'stats' => $stats,
            'permits' => $permits,
            'search' => $search,
            'statusFilter' => $status,
            'statusOptions' => $statusOptions,
            'recentActivity' => $this->buildRecentActivity($user),
        ]);
    }

    private function buildRecentActivity(User $user)
    {
        $recentWorkPermits = collect();
        $recentMaterialPermits = collect();

        if ($user->isAdmin()) {
            $recentWorkPermits = WorkPermit::latest('updated_at')->take(8)->get();
            $recentMaterialPermits = MaterialPermit::latest('updated_at')->take(8)->get();
        } elseif ($user->isOperations()) {
            $recentWorkPermits = WorkPermit::where('operations_approved_by', $user->id)
                ->orWhere('rejected_by', $user->id)
                ->latest('updated_at')->take(8)->get();

            $recentMaterialPermits = MaterialPermit::where('operations_approved_by', $user->id)
                ->orWhere('rejected_by', $user->id)
                ->latest('updated_at')->take(8)->get();
        } elseif (in_array($user->role, WorkPermit::REQUIRED_DEPARTMENTS, true)) {
            $recentWorkPermitIds = WorkPermitDepartmentApproval::where('actioned_by', $user->id)
                ->where('department', $user->role)
                ->latest('actioned_at')->take(8)->pluck('work_permit_id');

            $recentWorkPermits = WorkPermit::whereIn('id', $recentWorkPermitIds)->get();

            if ($user->role === 'security') {
                $recentMaterialPermits = MaterialPermit::where('gate_logged_by', $user->id)
                    ->latest('updated_at')->take(8)->get();
            }
        }

        return $recentWorkPermits->map(fn ($p) => [
            'type' => 'Work Permit',
            'ref' => 'WP-'.$p->id,
            'label' => $p->outlet_name,
            'status' => $p->status,
            'route' => route('work-permits.show', $p),
            'when' => $p->updated_at,
        ])
            ->concat($recentMaterialPermits->map(fn ($p) => [
                'type' => 'Material Permit',
                'ref' => 'MP-'.$p->id,
                'label' => $p->shop_details,
                'status' => $p->status,
                'route' => route('material-permits.show', $p),
                'when' => $p->updated_at,
            ]))
            ->sortByDesc('when')
            ->take(10)
            ->values();
    }
}

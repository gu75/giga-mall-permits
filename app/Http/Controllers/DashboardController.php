<?php

namespace App\Http\Controllers;

use App\Models\MaterialPermit;
use App\Models\User;
use App\Models\WorkPermit;
use Illuminate\Http\Request;
use App\Models\WorkPermitDepartmentApproval;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->isTenant()) {
            return $this->tenantDashboard($user);
        }

        if ($user->isOperations() || $user->isHse() || $user->isSecurity()) {
            return $this->managerDashboard($user);
        }

        // Admin (or any other role) gets a simple neutral landing page.
        return view('dashboard', ['dashboardType' => 'default']);
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

    private function managerDashboard(User $user)
    {
        $pendingWorkCount = 0;
        $pendingMaterialCount = 0;
        $recentWorkPermits = collect();
        $recentMaterialPermits = collect();

        if ($user->isOperations()) {
            $pendingWorkCount = WorkPermit::where('status', 'pending_operations')->count();
            $pendingMaterialCount = MaterialPermit::where('status', 'pending_operations')->count();

            $recentWorkPermits = WorkPermit::where('operations_approved_by', $user->id)
                ->orWhere('rejected_by', $user->id)
                ->latest('updated_at')
                ->take(8)
                ->get();

            $recentMaterialPermits = MaterialPermit::where('operations_approved_by', $user->id)
                ->orWhere('rejected_by', $user->id)
                ->latest('updated_at')
                ->take(8)
                ->get();
          }        elseif (in_array($user->role, WorkPermit::REQUIRED_DEPARTMENTS, true)) {
            $pendingWorkCount = WorkPermit::where('status', 'in_review')
                ->whereDoesntHave('departmentApprovals', fn ($q) => $q->where('department', $user->role))
                ->count();

            $recentWorkPermitIds = WorkPermitDepartmentApproval::where('actioned_by', $user->id)
                ->where('department', $user->role)
                ->latest('actioned_at')
                ->take(8)
                ->pluck('work_permit_id');

            $recentWorkPermits = WorkPermit::whereIn('id', $recentWorkPermitIds)->get();

            if ($user->role === 'security') {
                // Security also gate-logs Material Permits once Operations approves them.
                $pendingMaterialCount = MaterialPermit::where('status', 'approved')->count();

                $recentMaterialPermits = MaterialPermit::where('gate_logged_by', $user->id)
                    ->latest('updated_at')
                    ->take(8)
                    ->get();
            }
        }

        // Merge both permit types into one recent-activity feed, newest first.
        $recentActivity = $recentWorkPermits->map(fn ($p) => [
                'type' => 'Work Permit',
                'ref' => 'WP-' . $p->id,
                'label' => $p->outlet_name,
                'status' => $p->status,
                'route' => route('work-permits.show', $p),
                'when' => $p->updated_at,
            ])
            ->concat($recentMaterialPermits->map(fn ($p) => [
                'type' => 'Material Permit',
                'ref' => 'MP-' . $p->id,
                'label' => $p->shop_details,
                'status' => $p->status,
                'route' => route('material-permits.show', $p),
                'when' => $p->updated_at,
            ]))
            ->sortByDesc('when')
            ->take(10)
            ->values();

        return view('dashboard', [
            'dashboardType' => 'manager',
            'pendingWorkCount' => $pendingWorkCount,
            'pendingMaterialCount' => $pendingMaterialCount,
            'totalPendingCount' => $pendingWorkCount + $pendingMaterialCount,
            'recentActivity' => $recentActivity,
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\MaterialPermit;
use App\Models\WorkPermit;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user || ! $user->isTenant()) {
            return view('dashboard', [
                'user' => $user,
                'activeWorkPermits' => collect(),
                'historyWorkPermits' => collect(),
                'activeMaterialPermits' => collect(),
                'historyMaterialPermits' => collect(),
            ]);
        }

        $workPermits = WorkPermit::where('tenant_id', $user->id)->latest()->get();
        $materialPermits = MaterialPermit::where('tenant_id', $user->id)->latest()->get();

        $activeWorkPermits = $workPermits
            ->reject(fn ($permit) => in_array($permit->status, ['approved', 'rejected'], true))
            ->values();

        $historyWorkPermits = $workPermits
            ->filter(fn ($permit) => in_array($permit->status, ['approved', 'rejected'], true))
            ->values();

        $activeMaterialPermits = $materialPermits
            ->reject(fn ($permit) => in_array($permit->status, ['rejected', 'gate_cleared'], true))
            ->values();

        $historyMaterialPermits = $materialPermits
            ->filter(fn ($permit) => in_array($permit->status, ['approved', 'gate_cleared', 'rejected'], true))
            ->values();

        return view('dashboard', [
            'user' => $user,
            'activeWorkPermits' => $activeWorkPermits,
            'historyWorkPermits' => $historyWorkPermits,
            'activeMaterialPermits' => $activeMaterialPermits,
            'historyMaterialPermits' => $historyMaterialPermits,
        ]);
    }
}

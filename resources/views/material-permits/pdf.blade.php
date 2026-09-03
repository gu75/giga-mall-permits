<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #111; }
        h1 { text-align: center; font-size: 16px; margin-bottom: 0; }
        .center { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        td, th { border: 1px solid #333; padding: 5px; font-size: 11px; }
        .label { color: #555; width: 160px; }
        .section-title { background: #222; color: #fff; padding: 4px 8px; margin-top: 16px; }
    </style>
</head>
<body>
    <h1>GIGA MALL</h1>
    <p class="center">MATERIAL INWARD/OUTWARD PERMIT</p>

    <table>
        <tr>
            <td class="label">Shop Details</td><td>{{ $permit->shop_details }}</td>
            <td class="label">Direction</td><td>{{ strtoupper($permit->direction) }}</td>
        </tr>
        <tr>
            <td class="label">Shop Type</td><td>{{ str($permit->shop_type)->replace('_',' ')->title() }}</td>
            <td class="label">Dated</td><td>{{ $permit->dated->format('d F Y') }} {{ $permit->time?->format('h:i A') }}</td>
        </tr>
        <tr>
            <td class="label">Manager/Sup Name</td><td>{{ $permit->manager_sup_name }}</td>
            <td class="label">Cell No</td><td>{{ $permit->manager_sup_cell_no }}</td>
        </tr>
        <tr>
            <td class="label">CNIC No</td><td colspan="3">{{ $permit->cnic_no }}</td>
        </tr>
    </table>

    <p class="section-title">ITEMS</p>
    <table>
        <thead>
            <tr><th>S.No</th><th>Description</th><th>Quantity</th><th>Remarks</th></tr>
        </thead>
        <tbody>
            @foreach ($permit->items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->remarks }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="section-title">GATE LOG</p>
    <table>
        <tr>
            <td class="label">Operations Approved By</td>
            <td>{{ $permit->operationsApprover->name ?? '—' }}</td>
            <td class="label">Date</td>
            <td>{{ optional($permit->operations_approved_at)->format('d M Y H:i') ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Security Gate Log</td>
            <td>{{ $permit->gateLogger->name ?? '—' }}</td>
            <td class="label">Time</td>
            <td>{{ optional($permit->gate_logged_at)->format('d M Y H:i') ?? '—' }}</td>
        </tr>
    </table>

    <p style="margin-top: 16px;"><strong>Issued by:</strong> Giga Mall Operation Dept. &nbsp;&nbsp; <strong>Verified by:</strong> Giga Mall Security Dept.</p>
</body>
</html>

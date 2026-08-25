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
        .status { font-weight: bold; }
    </style>
</head>
<body>
    <h1>GIGAMALL</h1>
    <p class="center">WORK PERMIT FORM</p>
    <p class="center">{{ $permit->valid_from->format('d F Y') }} to {{ $permit->valid_to->format('d F Y') }}</p>

    <table>
        <tr>
            <td class="label">Outlet Name</td><td>{{ $permit->outlet_name }}</td>
            <td class="label">Floor/Location</td><td>{{ $permit->floor_location }}</td>
        </tr>
        <tr>
            <td class="label">Site Incharge Name</td><td>{{ $permit->site_incharge_name }}</td>
            <td class="label">Cell No / CNIC</td><td>{{ $permit->site_incharge_cell_no }} / {{ $permit->site_incharge_cnic }}</td>
        </tr>
        <tr>
            <td class="label">Nature of Work</td><td colspan="3">{{ $permit->nature_of_work }}</td>
        </tr>
        <tr>
            <td class="label">Requested By</td><td>{{ $permit->requested_by }}</td>
            <td class="label">Cell No</td><td>{{ $permit->requested_by_cell_no }}</td>
        </tr>
    </table>

    <p class="section-title">WORKERS</p>
    <table>
        <thead>
            <tr><th>Worker Name</th><th>Job Description</th><th>CNIC Number</th></tr>
        </thead>
        <tbody>
            @foreach ($permit->workers as $worker)
                <tr>
                    <td>{{ $worker->worker_name }}</td>
                    <td>{{ $worker->job_description }}</td>
                    <td>{{ $worker->cnic_number }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Duration of Work in Mall:</strong> Monday to Sunday, 09:00 PM to 08:00 AM
        @if ($permit->daytime_work_requested)
            <br><strong>Day-time work approved for:</strong> {{ $permit->daytime_work_reason }}
        @endif
    </p>

    <p class="section-title">APPROVALS</p>
    <table>
        <tr>
            <td class="label">Operations Dept.</td>
            <td>{{ $permit->operationsApprover->name ?? '—' }}</td>
            <td class="label">Date</td>
            <td>{{ optional($permit->operations_approved_at)->format('d M Y H:i') ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">HOD HSE</td>
            <td>{{ $permit->hseApprover->name ?? '—' }}</td>
            <td class="label">Date</td>
            <td>{{ optional($permit->hse_approved_at)->format('d M Y H:i') ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">HOD Security</td>
            <td>{{ $permit->securityApprover->name ?? '—' }}</td>
            <td class="label">Date</td>
            <td>{{ optional($permit->security_approved_at)->format('d M Y H:i') ?? '—' }}</td>
        </tr>
    </table>

    <p class="status">
        Overall Status: {{ strtoupper(str_replace('_', ' ', $permit->status)) }}
    </p>

    <ol style="font-size: 10px; margin-top: 20px;">
        <li>Staff supervision is mandatory by outlet applying for permit.</li>
        <li>Workers' personal protective equipment must be used as per nature of job. Fire extinguishers must be placed at work location.</li>
        <li>No smoking is allowed inside the mall (Rs. 10,000 will be imposed for violation).</li>
        <li>In case any serious incident happens due to negligence of staff/contractor, the brand will be responsible to pay for damage to property of Mall and other tenants.</li>
        <li>No underage worker will be employed for work.</li>
        <li>All kinds of work shall be carried out in accordance with standards mentioned in the Mall Fit-out Manual.</li>
    </ol>
</body>
</html>

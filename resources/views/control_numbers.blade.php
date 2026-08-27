@extends('layouts.topmenu')
@section('title', 'Control Numbers')
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;">
        <h2 style="font-weight:700;color:#1a2332;font-size:1.1rem;">Control Numbers</h2>
        <a href="/payment/account" style="background:#6c757d;color:#fff;padding:5px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
    </div>

    <div style="background:#dc3545;color:#fff;padding:8px 20px;border-radius:6px;text-align:center;font-size:1.2rem;font-weight:700;margin:8px 0;">
        BALANCE: <span style="font-size:1.5rem;">{{ number_format($totalBalance ?? 0, 0) }}</span> TZS
    </div>

    <div style="background:#fff;border-radius:8px;padding:10px 15px;box-shadow:0 2px 8px rgba(0,0,0,0.05);display:flex;flex-wrap:wrap;gap:5px 20px;">
        <div style="display:flex;padding:2px 0;">
            <span style="font-weight:600;color:#666;font-size:0.75rem;width:70px;">Reg No</span>
            <span style="color:#1a2332;font-weight:500;font-size:0.75rem;"><strong>{{ $student->reg ?? '-' }}</strong></span>
        </div>
        <div style="display:flex;padding:2px 0;">
            <span style="font-weight:600;color:#666;font-size:0.75rem;width:70px;">Name</span>
            <span style="color:#1a2332;font-weight:500;font-size:0.75rem;">{{ $student->sname ?? '-' }}</span>
        </div>
        <div style="display:flex;padding:2px 0;">
            <span style="font-weight:600;color:#666;font-size:0.75rem;width:70px;">Class</span>
            <span style="color:#1a2332;font-weight:500;font-size:0.75rem;">{{ $enrollment->class ?? $student->class ?? '-' }}</span>
        </div>
        <div style="display:flex;padding:2px 0;">
            <span style="font-weight:600;color:#666;font-size:0.75rem;width:70px;">Level</span>
            <span style="color:#1a2332;font-weight:500;font-size:0.75rem;">{{ $enrollment->level ?? $student->level ?? '-' }}</span>
        </div>
    </div>

    <div style="display:flex;gap:20px;margin-top:15px;flex-wrap:wrap;">
        <!-- Generate Control Number Panel -->
        <div style="background:#fff;border-radius:10px;box-shadow:0 3px 12px rgba(0,0,0,0.08);padding:20px;flex:0 0 350px;min-width:300px;">
            <h5 style="font-weight:700;color:#1a2332;margin-bottom:15px;">Generate Control Number</h5>
            <div style="overflow:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
                    <thead>
                        <tr style="background:#1a2332;color:#fff;">
                            <th style="padding:10px 12px;text-align:left;">Category</th>
                            <th style="padding:10px 12px;text-align:left;">Amount</th>
                            <th style="padding:10px 12px;text-align:left;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories ?? [] as $cat)
                        <tr style="border-bottom:1px solid #eee;">
                            <td style="padding:8px 12px;">{{ $cat->category ?? '-' }}</td>
                            <td style="padding:8px 12px;">{{ number_format($cat->balance ?? 0, 0) }}</td>
                            <td style="padding:8px 12px;">
                                <button onclick="generateControl('{{ $cat->id }}')" style="background:#28a745;color:#fff;border:none;padding:4px 12px;border-radius:4px;font-weight:600;font-size:0.7rem;cursor:pointer;">Generate</button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" style="text-align:center;padding:30px;color:#888;">No pending payments</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Control Numbers List Panel -->
        <div style="background:#fff;border-radius:10px;box-shadow:0 3px 12px rgba(0,0,0,0.08);padding:20px;flex:1;min-width:300px;">
            <h5 style="font-weight:700;color:#1a2332;margin-bottom:15px;">Control Numbers</h5>
            <div style="overflow:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
                    <thead>
                        <tr style="background:#1a2332;color:#fff;">
                            <th style="padding:10px 12px;text-align:left;">Control Number</th>
                            <th style="padding:10px 12px;text-align:left;">Category</th>
                            <th style="padding:10px 12px;text-align:left;">Amount</th>
                            <th style="padding:10px 12px;text-align:left;">Status</th>
                            <th style="padding:10px 12px;text-align:left;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($controlNumbers ?? [] as $cn)
                        <tr style="border-bottom:1px solid #eee;">
                            <td style="padding:8px 12px;"><strong>{{ $cn->cnamba ?? '-' }}</strong></td>
                            <td style="padding:8px 12px;">{{ $cn->category ?? '-' }}</td>
                            <td style="padding:8px 12px;">{{ number_format($cn->amount ?? 0, 0) }}</td>
                            <td style="padding:8px 12px;">
                                <span style="padding:2px 8px;border-radius:20px;font-size:0.65rem;font-weight:600;
                                    {{ strtolower($cn->status ?? 'pending') == 'canceled' ? 'background:#f8d7da;color:#721c24;' : 'background:#fff3cd;color:#856404;' }}">
                                    {{ $cn->status ?? 'Pending' }}
                                </span>
                            </td>
                            <td style="padding:8px 12px;">
                                @if(($cn->status ?? '') != 'Canceled')
                                    <button onclick="deleteControl('{{ $cn->cnamba }}')" style="background:#dc3545;color:#fff;border:none;padding:4px 12px;border-radius:4px;font-weight:600;font-size:0.7rem;cursor:pointer;">Delete</button>
                                @else
                                    <span style="color:#dc3545;">Canceled</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center;padding:30px;color:#888;">No control numbers found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function generateControl(catid) {
    if (!catid) return;
    if (!confirm('Generate control number for this payment?')) return;

    $.ajax({
        url: '/api/control-number/generate',
        type: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        data: {
            catid: catid,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                alert('Control number generated: ' + response.control_number);
                window.location.reload();
            } else {
                alert(response.message || 'Error generating control number');
            }
        },
        error: function(xhr) {
            alert(xhr.responseJSON?.message || 'Error generating control number');
        }
    });
}

function deleteControl(cnamba) {
    if (!cnamba) return;
    if (!confirm('Delete control number ' + cnamba + '?')) return;

    $.ajax({
        url: '/api/control-number/delete',
        type: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        data: {
            cnamba: cnamba,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                alert(response.message);
                window.location.reload();
            } else {
                alert(response.message || 'Error deleting control number');
            }
        },
        error: function(xhr) {
            alert(xhr.responseJSON?.message || 'Error deleting control number');
        }
    });
}
</script>
@endsection
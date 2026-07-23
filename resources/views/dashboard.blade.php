@extends('layouts.topmenu')

@section('title', 'Dashboard - Glorious Academy')

@section('content')
<div style="padding:15px 20px;">
    <h4 style="color:#336699;font-weight:700;">DIRECTOR PANEL</h4>
    <hr color="#FFCC00" size="1">
    
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:15px;margin-bottom:20px;">
        <div style="background:#fff;padding:18px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
            <h6 style="color:#888;font-size:0.75rem;">Total Students</h6>
            <h3 style="color:#1a2332;font-size:1.5rem;">{{ $totalStudents ?? 0 }}</h3>
        </div>
        <div style="background:#fff;padding:18px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
            <h6 style="color:#888;font-size:0.75rem;">Total Staff</h6>
            <h3 style="color:#1a2332;font-size:1.5rem;">{{ $totalStaff ?? 0 }}</h3>
        </div>
        <div style="background:#fff;padding:18px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
            <h6 style="color:#888;font-size:0.75rem;">Applications</h6>
            <h3 style="color:#1a2332;font-size:1.5rem;">{{ $totalApplications ?? 0 }}</h3>
        </div>
        <div style="background:#fff;padding:18px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
            <h6 style="color:#888;font-size:0.75rem;">Total Revenue</h6>
            <h3 id="revenueAmount" style="color:#28a745;font-size:1.5rem;">Loading...</h3>
        </div>
    </div>

    <h5 style="font-weight:700;color:#1a2332;">Recent Students</h5>
    <div class="table-responsive">
        <table class="table table-bordered table-hover" style="background:#fff;border-radius:8px;overflow:hidden;">
            <thead style="background:#1a2332;color:#fff;"><tr><th>#</th><th>Reg No</th><th>Name</th><th>Level</th><th>Class</th><th>Gender</th></tr></thead>
            <tbody>
                @forelse($recentStudents ?? [] as $index => $student)
                    <tr><td>{{ $index + 1 }}</td><td>{{ $student->reg ?? '-' }}</td><td>{{ $student->sname ?? '-' }}</td><td>{{ $student->level ?? '-' }}</td><td>{{ $student->class ?? '-' }}</td><td>{{ $student->gender ?? '-' }}</td></tr>
                @empty
                    <tr><td colspan="6" class="text-center">No students found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="text-center mt-2"><a href="/students" class="btn btn-sm" style="background:#D9A52A;color:#fff;font-weight:600;padding:6px 20px;border-radius:6px;text-decoration:none;">View All Students</a></div>
</div>
@endsection

@section('scripts')
<script>
    $.get('/api/dashboard-revenue', function(r) { 
        if (r.success) {
            var rev = Number(r.revenue || 0);
            $('#revenueAmount').text(rev >= 1000000 ? (rev/1000000).toFixed(1)+'M TSh' : rev.toLocaleString()+' TSh');
        }
    });
</script>
@endsection

@extends('layouts.topmenu')
@section('title', 'All Students')
@section('content')
<div style="padding:10px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #e9ecef;flex-wrap:wrap;gap:10px;">
        <h2 style="font-weight:700;color:#1a2332;font-size:1.1rem;">All Students</h2>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <a href="/dashboard" style="background:#6c757d;color:#fff;padding:6px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Back</a>
            <form method="GET" action="/students" style="display:flex;gap:8px;flex-wrap:wrap;">
                <input type="text" name="search" placeholder="Search..." value="{{ $search ?? '' }}" style="padding:6px 12px;border:2px solid #e9ecef;border-radius:6px;font-size:0.85rem;">
                <select name="year" style="padding:6px 12px;border:2px solid #e9ecef;border-radius:6px;"><option value="">All Years</option>@foreach($years??[] as $y)<option value="{{$y}}" {{($year??'')==$y?'selected':''}}>{{$y}}</option>@endforeach</select>
                <button type="submit" style="background:#D9A52A;color:#fff;border:none;padding:6px 16px;border-radius:6px;font-weight:600;cursor:pointer;">Search</button>
                @if($search||$year)<a href="/students" style="background:#dc3545;color:#fff;padding:6px 14px;border-radius:6px;text-decoration:none;font-size:0.8rem;">Clear</a>@endif
            </form>
        </div>
    </div>
    <div style="background:#fff;border-radius:10px;overflow:auto;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
        <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
            <thead><tr style="background:#1a2332;color:#fff;"><th style="padding:8px 12px;">#</th><th>Reg No</th><th>Name</th><th>Level</th><th>Class</th><th>Gender</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
                @forelse($students??[] as $i=>$s)
                <tr><td>{{$i+1}}</td><td><strong>{{$s->reg??'-'}}</strong></td><td>{{$s->sname??'-'}}</td><td>{{$s->level??'-'}}</td><td>{{$s->class??'-'}}</td><td>{{$s->gender??'-'}}</td><td><span style="padding:2px 10px;border-radius:20px;font-size:0.7rem;background:{{strtolower($s->status??'applicant')=='active'?'#d4edda':(strtolower($s->status??'')=='inactive'?'#f8d7da':'#fff3cd')}};color:{{strtolower($s->status??'applicant')=='active'?'#155724':(strtolower($s->status??'')=='inactive'?'#721c24':'#856404')}};">{{$s->status??'Applicant'}}</span></td><td><a href="/students/{{$s->sid}}" style="background:#D9A52A;color:#fff;padding:3px 12px;border-radius:4px;text-decoration:none;font-size:0.75rem;">View</a></td></tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:30px;">No students found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

<!DOCTYPE html>
<html>
<head>
    <title>Admissions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f0f2f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .btn-add { background: #27ae60; color: white; padding: 10px 20px; border: none; text-decoration: none; display: inline-block; }
        .btn-add:hover { background: #219a52; color: white; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .status-pending { background: #ffc107; color: #000; }
        .status-approved { background: #28a745; color: #fff; }
        .status-rejected { background: #dc3545; color: #fff; }
    </style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user-graduate"></i> Admissions</h2>
        <a href="{{ route('admin.admissions.create') }}" class="btn-add"><i class="fas fa-plus"></i> New Application</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Student Name</th>
                <th>DOB</th>
                <th>Class</th>
                <th>Parent</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admissions as $admission)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $admission->student_name }}</td>
                <td>{{ \Carbon\Carbon::parse($admission->date_of_birth)->format('d/m/Y') }}</td>
                <td>{{ $admission->class_applying_for }}</td>
                <td>{{ $admission->parent_full_name }}</td>
                <td>{{ $admission->parent_mobile }}</td>
                <td>
                    <span class="status-badge status-{{ $admission->status }}">
                        {{ ucfirst($admission->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.admissions.edit', $admission->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form method="POST" action="{{ route('admin.admissions.destroy', $admission->id) }}" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>

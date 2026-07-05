@extends('layouts.vufypms')

@section('content')
<div class="hero-strip p-4 mb-4">
    <h3 class="mb-1">Archive Management</h3>
    <p class="mb-0">Archive completed projects, restore archived records, and manage semester-wise archives.</p>
</div>

<!-- Bulk Archive Section -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Bulk Archive by Semester</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.archive.bulkArchive') }}" class="row g-2">
            @csrf
            <div class="col-md-6">
                <select name="semester_id" class="form-select" required>
                    <option value="">-- Select Semester to Archive All Projects --</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}">
                            {{ $semester->name }} ({{ $semester->start_date->format('M Y') }} - {{ $semester->end_date->format('M Y') }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Archive all projects from this semester?');">
                    Archive Entire Semester
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.archive.index') }}" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search project or team..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="archive_status" class="form-select form-select-sm">
                    <option value="">All Projects</option>
                    <option value="active" @selected(request('archive_status') === 'active')>Active</option>
                    <option value="archived" @selected(request('archive_status') === 'archived')>Archived</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="semester_id" class="form-select form-select-sm">
                    <option value="">All Semesters</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}" @selected(request('semester_id') == $semester->id)>
                            {{ $semester->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
            </div>
            <div class="col-md-12">
                <a href="{{ route('admin.archive.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Projects List for Archival -->
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Projects ({{ $projects->total() }})</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                <tr>
                    <th>Project Title</th>
                    <th>Team</th>
                    <th>Semester</th>
                    <th>Supervisor</th>
                    <th>Status</th>
                    <th>Proposal Status</th>
                    <th width="200">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($projects as $project)
                    <tr>
                        <td>
                            <div class="fw-bold small">{{ $project->title }}</div>
                            <div class="small text-muted">{{ $project->domain->name ?? 'N/A' }}</div>
                        </td>
                        <td>{{ $project->team->name ?? 'N/A' }}</td>
                        <td>{{ $project->semester->name ?? 'N/A' }}</td>
                        <td>{{ $project->supervisor->name ?? 'Not Assigned' }}</td>
                        <td>
                            @if($project->is_published)
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-secondary">Unpublished</span>
                            @endif
                        </td>
                        <td>
                            @if($project->proposal_status === 'draft')
                                <span class="badge bg-secondary">Draft</span>
                            @elseif($project->proposal_status === 'submitted')
                                <span class="badge bg-info">Submitted</span>
                            @elseif($project->proposal_status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($project->proposal_status === 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @elseif($project->proposal_status === 'revision_required')
                                <span class="badge bg-warning text-dark">Revision Required</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.archive.details', $project) }}" class="btn btn-info" title="View Details">
                                    👁️
                                </a>
                                @if($project->is_published || in_array($project->proposal_status, ['approved', 'submitted']))
                                    <form method="POST" action="{{ route('admin.archive.store', $project) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm" title="Archive Project" onclick="return confirm('Archive this project?');">
                                            📦
                                        </button>
                                    </form>
                                @endif
                                @if(!$project->is_published)
                                    <form method="POST" action="{{ route('admin.archive.restore', $project) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" title="Restore Project" onclick="return confirm('Restore this project?');">
                                            ♻️
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No projects found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $projects->links() }}
    </div>
</div>

@endsection

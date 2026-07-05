@extends('layouts.vufypms')

@section('content')
<div class="hero-strip p-4 mb-4">
    <h3 class="mb-1">Team Management</h3>
    <p class="mb-0">View and manage all student teams, assign supervisors, and track team status.</p>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.teams.index') }}" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search team name or leader..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="forming" @selected(request('status') === 'forming')>Forming</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="archived" @selected(request('status') === 'archived')>Archived</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                <a href="{{ route('admin.teams.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Teams List -->
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Teams ({{ $teams->total() }})</h5>
    </div>
    <div class="card-body">
        @forelse($teams as $team)
            <div class="border rounded p-4 mb-3">
                <div class="row g-2 mb-3">
                    <div class="col-md-8">
                        <div>
                            <h6 class="mb-1">{{ $team->name }}</h6>
                            <div class="d-flex gap-3 flex-wrap small text-muted mb-2">
                                <span>📌 Leader: <strong>{{ $team->leader->name ?? 'N/A' }}</strong></span>
                                <span>📚 Members: <strong>{{ $team->members->count() }}</strong></span>
                                <span>🗓️ Semester: <strong>{{ $team->semester?->name ?? 'N/A' }}</strong></span>
                                <span>📄 Project: <strong>{{ $team->project?->title ?? 'No Project' }}</strong></span>
                            </div>
                        </div>

                        <!-- Team Members List -->
                        <div class="mt-2 pt-2 border-top">
                            <small class="text-muted d-block mb-2">Team Members:</small>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($team->members as $member)
                                    <span class="badge bg-info">
                                        {{ $member->student->name }}
                                        @if($member->role === 'leader')
                                            (Leader)
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="d-flex flex-column gap-2">
                            <!-- Status Badge -->
                            <div>
                                @if($team->status === 'forming')
                                    <span class="badge bg-warning text-dark">Forming</span>
                                @elseif($team->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($team->status === 'archived')
                                    <span class="badge bg-secondary">Archived</span>
                                @endif
                            </div>

                            <!-- Current Supervisor -->
                            <div class="small">
                                <span class="text-muted">Supervisor:</span>
                                <div class="fw-bold">{{ $team->supervisor?->name ?? 'Not Assigned' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management Actions -->
                <div class="border-top pt-3">
                    <form method="POST" action="{{ route('admin.teams.update', $team) }}" class="row g-2">
                        @csrf
                        @method('PATCH')

                        <div class="col-md-3">
                            <select name="status" class="form-select form-select-sm" required>
                                <option value="forming" @selected($team->status === 'forming')>Forming</option>
                                <option value="active" @selected($team->status === 'active')>Active</option>
                                <option value="archived" @selected($team->status === 'archived')>Archived</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <select name="supervisor_id" class="form-select form-select-sm">
                                <option value="">-- Assign Supervisor --</option>
                                @foreach($supervisors as $supervisor)
                                    <option value="{{ $supervisor->id }}" @selected($team->supervisor_id === $supervisor->id)>
                                        {{ $supervisor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-sm btn-primary w-100">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="alert alert-info mb-0">
                No teams found. Students can create teams from their dashboard.
            </div>
        @endforelse

        {{ $teams->links() }}
    </div>
</div>
@endsection

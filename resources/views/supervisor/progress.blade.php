@extends('layouts.vufypms')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="mb-1">Progress Monitoring</h2>
            <p class="text-muted mb-0">Monitor milestone completion, documents, and project status for assigned teams.</p>
        </div>
        <a href="{{ route('supervisor.proposals.index') }}" class="btn btn-outline-primary">Proposal Review</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><small class="text-muted">Projects</small><h4 class="mb-0">{{ $overall['projects'] }}</h4></div></div></div>
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><small class="text-muted">Milestones</small><h4 class="mb-0">{{ $overall['milestones_total'] }}</h4></div></div></div>
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><small class="text-muted">Overdue</small><h4 class="mb-0">{{ $overall['milestones_overdue'] }}</h4></div></div></div>
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><small class="text-muted">Pending Docs</small><h4 class="mb-0">{{ $overall['pending_documents'] }}</h4></div></div></div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Filters</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('supervisor.progress.index') }}" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label">Team</label>
                <select name="team_id" class="form-select">
                    <option value="">All Teams</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" @selected(($filters['team_id'] ?? null) == $team->id)>{{ $team->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">Project</label>
                <select name="project_id" class="form-select">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" @selected(($filters['project_id'] ?? null) == $project->id)>{{ $project->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-primary">Apply</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        @forelse($projects as $project)
            @php
                $milestoneTotal = $project->milestones->count();
                $milestoneCompleted = $project->milestones->where('status', 'completed')->count();
                $milestoneInProgress = $project->milestones->where('status', 'in_progress')->count();
                $milestoneOverdue = $project->milestones->filter(fn ($milestone) => $milestone->due_date && $milestone->due_date->isPast() && $milestone->status !== 'completed')->count();
                $progressPercent = $milestoneTotal > 0 ? round(($milestoneCompleted / $milestoneTotal) * 100) : 0;
            @endphp

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="mb-0">{{ $project->title }}</h5>
                        <small class="text-muted">Team: {{ $project->team?->name ?? 'N/A' }} | Semester: {{ $project->semester?->name ?? 'N/A' }}</small>
                    </div>
                    <span class="badge bg-secondary text-uppercase">{{ $project->proposal_status }}</span>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-3"><div class="border rounded p-3"><small class="text-muted d-block">Completion</small><h4 class="mb-0">{{ $progressPercent }}%</h4></div></div>
                        <div class="col-md-3"><div class="border rounded p-3"><small class="text-muted d-block">Completed</small><h4 class="mb-0">{{ $milestoneCompleted }}</h4></div></div>
                        <div class="col-md-3"><div class="border rounded p-3"><small class="text-muted d-block">In Progress</small><h4 class="mb-0">{{ $milestoneInProgress }}</h4></div></div>
                        <div class="col-md-3"><div class="border rounded p-3"><small class="text-muted d-block">Overdue</small><h4 class="mb-0">{{ $milestoneOverdue }}</h4></div></div>
                    </div>

                    <div class="progress mb-4" style="height: 10px;">
                        <div class="progress-bar" role="progressbar" data-progress="{{ $progressPercent }}" aria-valuenow="{{ $progressPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body">
                                    <h6 class="mb-3">Milestones</h6>
                                    @forelse($project->milestones as $milestone)
                                        @php
                                            $isOverdue = $milestone->due_date && $milestone->due_date->isPast() && $milestone->status !== 'completed';
                                        @endphp
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <strong>{{ $milestone->title }}</strong>
                                                <div class="small text-muted">{{ $milestone->due_date?->format('d M Y') ?? 'No due date' }}</div>
                                            </div>
                                            <span class="badge {{ $isOverdue ? 'bg-danger' : ($milestone->status === 'completed' ? 'bg-success' : ($milestone->status === 'in_progress' ? 'bg-warning text-dark' : 'bg-secondary')) }} text-uppercase">
                                                {{ $isOverdue ? 'overdue' : $milestone->status }}
                                            </span>
                                        </div>
                                    @empty
                                        <p class="mb-0 text-muted">No milestones created yet.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card border-0 bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="mb-3">Documents</h6>
                                    <p class="mb-1"><strong>Total:</strong> {{ $project->documents->count() }}</p>
                                    <p class="mb-1"><strong>Pending Review:</strong> {{ $project->documents->where('review_status', 'pending')->count() }}</p>
                                    <p class="mb-0"><strong>Accepted:</strong> {{ $project->documents->where('review_status', 'accepted')->count() }}</p>
                                </div>
                            </div>

                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="mb-3">Latest Evaluation</h6>
                                    @php $latestEvaluation = $project->evaluations->sortByDesc('created_at')->first(); @endphp
                                    @if($latestEvaluation)
                                        <p class="mb-1"><strong>Type:</strong> {{ $latestEvaluation->evaluation_type }}</p>
                                        <p class="mb-1"><strong>Marks:</strong> {{ $latestEvaluation->marks ?? 'N/A' }}</p>
                                        <p class="mb-0"><strong>Remarks:</strong> {{ $latestEvaluation->remarks ?? 'N/A' }}</p>
                                    @else
                                        <p class="mb-0 text-muted">No evaluation recorded yet.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info mb-0">No assigned projects match the selected filters.</div>
        @endforelse
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="mb-0">Summary</h5></div>
            <div class="card-body">
                <p class="mb-2"><strong>Projects:</strong> {{ $overall['projects'] }}</p>
                <p class="mb-2"><strong>Teams:</strong> {{ $overall['teams'] }}</p>
                <p class="mb-2"><strong>Total Milestones:</strong> {{ $overall['milestones_total'] }}</p>
                <p class="mb-2"><strong>Completed Milestones:</strong> {{ $overall['milestones_completed'] }}</p>
                <p class="mb-2"><strong>Overdue Milestones:</strong> {{ $overall['milestones_overdue'] }}</p>
                <p class="mb-2"><strong>Total Documents:</strong> {{ $overall['documents'] }}</p>
                <p class="mb-0"><strong>Pending Documents:</strong> {{ $overall['pending_documents'] }}</p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Scope</h5></div>
            <div class="card-body">
                <p class="mb-0">Only projects assigned to the logged-in supervisor are shown.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-progress]').forEach(function (element) {
        const value = Math.max(0, Math.min(100, parseInt(element.dataset.progress || '0', 10)));
        element.style.width = value + '%';
    });
});
</script>
@endpush
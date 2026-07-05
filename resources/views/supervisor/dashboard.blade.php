@extends('layouts.vufypms')

@section('content')
<div class="hero-strip p-4 mb-4">
    <h3 class="mb-1">Supervisor Dashboard</h3>
    <p class="mb-0">Live oversight of assigned teams, proposals, evaluations, feedback, and presentations.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card shadow-sm h-100"><div class="card-body"><small class="text-muted">Assigned Teams</small><h4 class="mb-0">{{ $stats['teams'] }}</h4></div></div></div>
    <div class="col-md-3"><div class="card shadow-sm h-100"><div class="card-body"><small class="text-muted">Supervised Projects</small><h4 class="mb-0">{{ $stats['projects'] }}</h4></div></div></div>
    <div class="col-md-3"><div class="card shadow-sm h-100"><div class="card-body"><small class="text-muted">Pending Proposals</small><h4 class="mb-0">{{ $stats['pending_proposals'] }}</h4></div></div></div>
    <div class="col-md-3"><div class="card shadow-sm h-100"><div class="card-body"><small class="text-muted">Feedback Items</small><h4 class="mb-0">{{ $stats['feedback'] }}</h4></div></div></div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card shadow-sm h-100"><div class="card-body"><small class="text-muted">Documents to Review</small><h4 class="mb-0">{{ $stats['documents'] }}</h4></div></div></div>
    <div class="col-md-4"><div class="card shadow-sm h-100"><div class="card-body"><small class="text-muted">Evaluation Entries</small><h4 class="mb-0">{{ $stats['evaluations'] }}</h4></div></div></div>
    <div class="col-md-4"><div class="card shadow-sm h-100"><div class="card-body"><small class="text-muted">Published Presentations</small><h4 class="mb-0">{{ $stats['published_presentations'] }}</h4></div></div></div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Supervised Projects</h5>
                <a href="{{ route('supervisor.proposals.index') }}" class="btn btn-sm btn-primary">Open Reviews</a>
            </div>
            <div class="card-body">
                @forelse($recentProjects as $project)
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between gap-3 flex-wrap mb-2">
                            <div>
                                <strong>{{ $project->title }}</strong>
                                <div class="text-muted small">Team: {{ $project->team?->name ?? 'N/A' }} | Domain: {{ $project->domain?->name ?? 'N/A' }}</div>
                            </div>
                            <span class="badge bg-secondary text-uppercase">{{ $project->proposal_status }}</span>
                        </div>
                        <div class="small text-muted">
                            Presentation: {{ $project->presentation_date?->format('d M Y') ?? 'Not scheduled' }}
                            @if($project->is_published)
                                | Published
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="mb-0">No supervised projects yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recent Assigned Teams</h5>
            </div>
            <div class="card-body">
                @forelse($recentTeams as $team)
                    <div class="border rounded p-3 mb-3">
                        <strong>{{ $team->name }}</strong>
                        <div class="small text-muted">Leader: {{ $team->leader?->name ?? 'N/A' }}</div>
                        <div class="small text-muted">Status: {{ ucfirst($team->status) }}</div>
                    </div>
                @empty
                    <p class="mb-0">No assigned teams yet.</p>
                @endforelse
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body d-flex flex-wrap gap-2">
                <a class="btn btn-primary" href="{{ route('supervisor.proposals.index') }}">Proposal Review</a>
                <a class="btn btn-outline-primary" href="{{ route('supervisor.assignedTeams.index') }}">Assigned Teams</a>
                <a class="btn btn-outline-primary" href="{{ route('supervisor.communication.index') }}">Communication</a>
                <a class="btn btn-outline-primary" href="{{ route('supervisor.documents.index') }}">Documents</a>
            </div>
        </div>
    </div>
</div>
@endsection

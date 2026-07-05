@extends('layouts.vufypms')

@section('content')
<div class="hero-strip p-4 mb-4">
    <h3 class="mb-1">{{ $project->title }}</h3>
    <p class="mb-0">Archive details for selected project</p>
</div>

<div class="row g-4">
    <!-- Project Information -->
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Project Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Title:</strong> {{ $project->title }}
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong> 
                        @if($project->is_published)
                            <span class="badge bg-success">Published</span>
                        @else
                            <span class="badge bg-secondary">Unpublished</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <strong>Proposal Status:</strong>
                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $project->proposal_status)) }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Domain:</strong> {{ $project->domain->name ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Team:</strong> {{ $project->team->name ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Supervisor:</strong> {{ $project->supervisor->name ?? 'Not Assigned' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Semester:</strong> {{ $project->semester->name ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Created:</strong> {{ $project->created_at->format('M d, Y') }}
                    </div>
                    <div class="col-12">
                        <strong>Abstract:</strong>
                        <p class="text-muted">{{ $project->abstract ?? 'No abstract provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Members -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Team Members</h5>
            </div>
            <div class="card-body">
                @if($project->team && $project->team->members->count() > 0)
                    <div class="list-group">
                        @foreach($project->team->members as $member)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $member->student->name }}</h6>
                                    <span class="badge bg-primary">{{ ucfirst($member->role) }}</span>
                                </div>
                                <small class="text-muted">{{ $member->student->email }}</small>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No team members found</p>
                @endif
            </div>
        </div>

        <!-- Milestones -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Milestones ({{ $project->milestones->count() }})</h5>
            </div>
            <div class="card-body">
                @if($project->milestones->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($project->milestones as $milestone)
                                    <tr>
                                        <td>{{ $milestone->title }}</td>
                                        <td>{{ $milestone->due_date?->format('M d, Y') ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ ucfirst($milestone->status) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No milestones recorded</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar: Documents & Evaluations -->
    <div class="col-lg-4">
        <!-- Documents -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Documents ({{ $project->documents->count() }})</h5>
            </div>
            <div class="card-body">
                @if($project->documents->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($project->documents as $doc)
                            <a href="{{ route('admin.documents.download', $doc) }}" class="list-group-item list-group-item-action small">
                                <div class="d-flex justify-content-between">
                                    <span>{{ $doc->document_type }}</span>
                                    <span class="badge bg-success">v{{ $doc->version }}</span>
                                </div>
                                <small class="text-muted">{{ $doc->created_at->format('M d, Y') }}</small>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted small mb-0">No documents submitted</p>
                @endif
            </div>
        </div>

        <!-- Evaluations -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Evaluations ({{ $project->evaluations->count() }})</h5>
            </div>
            <div class="card-body">
                @if($project->evaluations->count() > 0)
                    @foreach($project->evaluations as $eval)
                        <div class="mb-3 pb-2 border-bottom">
                            <div class="small">
                                <strong>{{ ucfirst(str_replace('_', ' ', $eval->evaluation_type)) }}</strong>
                                <div class="text-muted small">By: {{ $eval->evaluator->name }}</div>
                                @if($eval->marks !== null)
                                    <div class="text-warning">Marks: <strong>{{ $eval->marks }}</strong> / 100</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted small mb-0">No evaluations recorded</p>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.archive.index') }}" class="btn btn-secondary btn-sm w-100 mb-2">
                    ← Back to Archive
                </a>
                @if($project->is_published || in_array($project->proposal_status, ['approved', 'submitted']))
                    <form method="POST" action="{{ route('admin.archive.store', $project) }}" class="d-grid">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Archive this project?');">
                            📦 Archive Project
                        </button>
                    </form>
                @endif
                @if(!$project->is_published)
                    <form method="POST" action="{{ route('admin.archive.restore', $project) }}" class="d-grid">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Restore this project?');">
                            ♻️ Restore Project
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

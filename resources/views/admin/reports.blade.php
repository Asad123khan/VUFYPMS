@extends('layouts.vufypms')

@section('content')
<div class="hero-strip p-4 mb-4">
    <h3 class="mb-1">Reports & Analytics</h3>
    <p class="mb-0">Comprehensive dashboard with system-wide statistics, project metrics, and analytics.</p>
</div>

<!-- Key Statistics -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="small text-muted">Total Students</div>
                <h3 class="mb-0 text-primary">{{ $stats['total_students'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="small text-muted">Total Supervisors</div>
                <h3 class="mb-0 text-info">{{ $stats['total_supervisors'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="small text-muted">Total Teams</div>
                <h3 class="mb-0 text-success">{{ $stats['total_teams'] }}</h3>
                <small class="text-success">{{ $stats['teams_active'] }} active</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="small text-muted">Total Projects</div>
                <h3 class="mb-0 text-warning">{{ $stats['total_projects'] }}</h3>
                <small class="text-warning">{{ $stats['active_projects'] }} published</small>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Statistics -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="small text-muted">Documents Submitted</div>
                <h4 class="mb-0">{{ $stats['documents_submitted'] }}</h4>
                <small class="text-danger">{{ $stats['documents_pending_review'] }} pending review</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="small text-muted">Total Evaluations</div>
                <h4 class="mb-0">{{ $stats['total_evaluations'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="small text-muted">Active Semester</div>
                <h5 class="mb-0">{{ $activeSemester?->name ?? 'None Set' }}</h5>
                @if($activeSemester)
                    <small>{{ $activeSemester->start_date->format('M d') }} - {{ $activeSemester->end_date->format('M d, Y') }}</small>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="small text-muted">Project Domains</div>
                <h4 class="mb-0">{{ $domainStats->count() }}</h4>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Statistics Section -->
<div class="row g-4 mb-4">
    <!-- Proposal Status Breakdown -->
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Proposal Status Breakdown</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr>
                                <td>Draft</td>
                                <td><span class="badge bg-secondary">{{ $proposalStats['draft'] ?? 0 }}</span></td>
                            </tr>
                            <tr>
                                <td>Submitted</td>
                                <td><span class="badge bg-info">{{ $proposalStats['submitted'] ?? 0 }}</span></td>
                            </tr>
                            <tr>
                                <td>Approved</td>
                                <td><span class="badge bg-success">{{ $proposalStats['approved'] ?? 0 }}</span></td>
                            </tr>
                            <tr>
                                <td>Rejected</td>
                                <td><span class="badge bg-danger">{{ $proposalStats['rejected'] ?? 0 }}</span></td>
                            </tr>
                            <tr>
                                <td>Revision Required</td>
                                <td><span class="badge bg-warning text-dark">{{ $proposalStats['revision_required'] ?? 0 }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Review Status -->
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Document Review Status</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr>
                                <td>Pending Review</td>
                                <td><span class="badge bg-warning text-dark">{{ $reviewStatusStats['pending'] ?? 0 }}</span></td>
                            </tr>
                            <tr>
                                <td>Accepted</td>
                                <td><span class="badge bg-success">{{ $reviewStatusStats['accepted'] ?? 0 }}</span></td>
                            </tr>
                            <tr>
                                <td>Revision Required</td>
                                <td><span class="badge bg-danger">{{ $reviewStatusStats['revision_required'] ?? 0 }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Project Domain and Evaluation Statistics -->
<div class="row g-4 mb-4">
    <!-- Projects by Domain -->
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Projects by Domain</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Domain</th>
                                <th class="text-end">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($domainStats as $domain)
                                <tr>
                                    <td>{{ $domain['name'] }}</td>
                                    <td class="text-end"><span class="badge bg-primary">{{ $domain['count'] }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">No domain data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Evaluation Type Statistics -->
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Evaluation Type Statistics</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr>
                                <td>Proposal Defense</td>
                                <td><span class="badge bg-info">{{ $evaluationStats['proposal_defense'] ?? 0 }}</span></td>
                            </tr>
                            <tr>
                                <td>Progress Review</td>
                                <td><span class="badge bg-warning text-dark">{{ $evaluationStats['progress_review'] ?? 0 }}</span></td>
                            </tr>
                            <tr>
                                <td>Final Defense</td>
                                <td><span class="badge bg-success">{{ $evaluationStats['final_defense'] ?? 0 }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Projects by Semester and Document Types -->
<div class="row g-4 mb-4">
    <!-- Projects by Semester -->
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Projects by Semester</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Semester</th>
                                <th class="text-end">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($semesterStats as $semester)
                                <tr>
                                    <td>{{ $semester['name'] }}</td>
                                    <td class="text-end"><span class="badge bg-primary">{{ $semester['count'] }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">No semester data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Type Statistics -->
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Submitted Document Types</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Document Type</th>
                                <th class="text-end">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documentStats as $type => $count)
                                <tr>
                                    <td>{{ $type }}</td>
                                    <td class="text-end"><span class="badge bg-primary">{{ $count }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">No document data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Supervisor Workload -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Supervisor Workload (Assigned Projects)</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Supervisor Name</th>
                        <th class="text-end">Project Count</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($supervisorWorkload as $supervisor)
                        <tr>
                            <td>{{ $supervisor['name'] }}</td>
                            <td class="text-end">
                                <span class="badge bg-primary">{{ $supervisor['count'] }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">No supervisor assignments yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Summary Statistics -->
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">System Summary</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <strong>Overall System Health</strong>
                <div class="progress mt-2" role="progressbar">
                    <div class="progress-bar bg-success" style="width: {{ min(100, ($stats['active_projects'] / max(1, $stats['total_projects']) * 100)) }}%"></div>
                </div>
                <small class="text-muted">{{ $stats['active_projects'] }} / {{ $stats['total_projects'] }} projects published</small>
            </div>
            <div class="col-md-4">
                <strong>Document Review Progress</strong>
                <div class="progress mt-2" role="progressbar">
                    @php
                        $reviewedDocs = $stats['documents_submitted'] - $stats['documents_pending_review'];
                        $reviewPercent = $stats['documents_submitted'] > 0 ? ($reviewedDocs / $stats['documents_submitted'] * 100) : 0;
                    @endphp
                    <div class="progress-bar bg-info" style="width: {{ $reviewPercent }}%"></div>
                </div>
                <small class="text-muted">{{ $reviewedDocs }} / {{ $stats['documents_submitted'] }} documents reviewed</small>
            </div>
            <div class="col-md-4">
                <strong>Proposal Approval Rate</strong>
                <div class="progress mt-2" role="progressbar">
                    @php
                        $approvedProps = $proposalStats['approved'] ?? 0;
                        $totalProps = array_sum($proposalStats);
                        $approvalPercent = $totalProps > 0 ? ($approvedProps / $totalProps * 100) : 0;
                    @endphp
                    <div class="progress-bar bg-warning" style="width: {{ $approvalPercent }}%"></div>
                </div>
                <small class="text-muted">{{ $approvedProps }} / {{ $totalProps }} proposals approved</small>
            </div>
        </div>
    </div>
</div>

@endsection

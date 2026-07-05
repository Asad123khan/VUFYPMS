@extends('layouts.vufypms')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="mb-1">Presentation Schedule</h2>
            <p class="text-muted mb-0">Your project presentation details are published here once assigned.</p>
        </div>
        @if($project)
            <span class="badge bg-primary">{{ $project->title }}</span>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Scheduled Presentation</h6>
                <span class="badge {{ $presentationStatus['class'] }}">{{ $presentationStatus['label'] }}</span>
            </div>
            <div class="card-body">
                @if(!$membership)
                    <div class="alert alert-warning mb-0">Join or create a team first.</div>
                @elseif(!$project)
                    <div class="alert alert-info mb-0">Create and submit your project proposal before presentation scheduling becomes available.</div>
                @else
                    <div class="row g-3 mb-4">
                        <div class="col-md-4"><div class="border rounded p-3"><small class="text-muted d-block">Date</small><strong>{{ $project->presentation_date?->format('d M Y') ?? 'Not scheduled' }}</strong></div></div>
                        <div class="col-md-4"><div class="border rounded p-3"><small class="text-muted d-block">Venue / Link</small><strong>{{ $project->presentation_venue ?: $project->presentation_link ?: 'Not assigned' }}</strong></div></div>
                        <div class="col-md-4"><div class="border rounded p-3"><small class="text-muted d-block">Supervisor</small><strong>{{ $project->supervisor?->name ?? 'Not assigned' }}</strong></div></div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Project</th>
                                    <th>Date</th>
                                    <th>Venue / Link</th>
                                    <th>Supervisor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $project->title }}</td>
                                    <td>{{ $project->presentation_date?->format('d M Y') ?? 'Not scheduled' }}</td>
                                    <td>{{ $project->presentation_venue ?: $project->presentation_link ?: 'Not assigned' }}</td>
                                    <td>{{ $project->supervisor?->name ?? 'Not assigned' }}</td>
                                    <td><span class="badge {{ $presentationStatus['class'] }}">{{ $presentationStatus['label'] }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h6 class="mb-0">Presentation Guidelines</h6></div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">Presentations are 15 minutes.</li>
                    <li class="mb-2">Q&A follows for 5-10 minutes.</li>
                    <li class="mb-2">Prepare slides and handouts in advance.</li>
                    <li class="mb-0">Attendance is mandatory.</li>
                </ul>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0">Important Dates</h6></div>
            <div class="card-body">
                <p class="mb-2"><strong>Proposal Review:</strong> <span class="badge bg-warning text-dark">15 May - 30 May</span></p>
                <p class="mb-2"><strong>Presentation Release:</strong> <span class="badge bg-info text-dark">1 June</span></p>
                <p class="mb-0"><strong>Presentation Period:</strong> <span class="badge bg-secondary">5 June - 20 June</span></p>
            </div>
        </div>
    </div>
</div>
@endsection

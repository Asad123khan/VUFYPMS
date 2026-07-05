@extends('layouts.vufypms')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="mb-1">Meetings / Consultation Schedule</h2>
            <p class="text-muted mb-0">View consultations assigned for your team.</p>
        </div>
        @if($project)
            <span class="badge bg-primary">{{ $project->title }}</span>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Upcoming Meetings</h5>
                <span class="badge bg-primary">{{ $upcomingMeetings->count() }}</span>
            </div>
            <div class="card-body">
                @if(!$membership)
                    <div class="alert alert-warning mb-0">Join or create a team first.</div>
                @elseif(!$project)
                    <div class="alert alert-info mb-0">No project has been assigned yet.</div>
                @elseif($upcomingMeetings->isEmpty())
                    <div class="alert alert-info mb-0">No upcoming consultations scheduled.</div>
                @else
                    @foreach($upcomingMeetings as $meeting)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                <div>
                                    <strong>{{ $meeting->starts_at?->format('d M Y, h:i A') }}</strong>
                                    <div class="small text-muted">{{ $meeting->project?->team?->name ?? 'Your Team' }}</div>
                                </div>
                                <span class="badge bg-info text-dark text-uppercase">{{ $meeting->status }}</span>
                            </div>
                            <div class="small mb-1"><strong>End:</strong> {{ $meeting->ends_at?->format('d M Y, h:i A') }}</div>
                            <div class="small mb-1"><strong>Location / Link:</strong> {{ $meeting->venue_or_link ?: 'N/A' }}</div>
                            <div class="small mb-1"><strong>Agenda:</strong> {{ $meeting->agenda ?: 'N/A' }}</div>
                            <div class="small"><strong>Supervisor Notes:</strong> {{ $meeting->remarks ?: 'N/A' }}</div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Past Meetings</h5>
                <span class="badge bg-secondary">{{ $pastMeetings->count() }}</span>
            </div>
            <div class="card-body">
                @forelse($pastMeetings as $meeting)
                    <div class="border rounded p-3 mb-3">
                        <strong>{{ $meeting->starts_at?->format('d M Y') }}</strong>
                        <div class="small text-muted">{{ $meeting->venue_or_link ?: 'N/A' }}</div>
                        <div class="small text-muted">{{ ucfirst($meeting->status) }}</div>
                    </div>
                @empty
                    <p class="mb-0">No past meetings found.</p>
                @endforelse
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Supervisor</h5></div>
            <div class="card-body">
                <p class="mb-2"><strong>Name:</strong> {{ $project?->supervisor?->name ?? 'Not assigned' }}</p>
                <p class="mb-0"><strong>Email:</strong> {{ $project?->supervisor?->email ?? 'Not assigned' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
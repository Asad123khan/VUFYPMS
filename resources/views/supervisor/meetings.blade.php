@extends('layouts.vufypms')

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">{{ $editMeeting ? 'Edit Consultation' : 'Schedule Consultation' }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $editMeeting ? route('supervisor.meetings.update', $editMeeting) : route('supervisor.meetings.store') }}">
                    @csrf
                    @if($editMeeting)
                        @method('PATCH')
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Team / Project</label>
                        <select name="project_id" class="form-select">
                            <option value="">General consultation</option>
                            @foreach($assignedProjects as $project)
                                <option value="{{ $project->id }}" @selected(($editMeeting?->project_id ?? old('project_id')) == $project->id)>
                                    {{ $project->title }} ({{ $project->team?->name ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Start</label>
                            <input type="datetime-local" name="starts_at" class="form-control" value="{{ $editMeeting?->starts_at?->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End</label>
                            <input type="datetime-local" name="ends_at" class="form-control" value="{{ $editMeeting?->ends_at?->format('Y-m-d\TH:i') }}" required>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label">Location or Link</label>
                        <input type="text" name="venue_or_link" class="form-control" value="{{ $editMeeting?->venue_or_link }}" placeholder="Room, office, or online meeting link">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Agenda</label>
                        <textarea name="agenda" class="form-control" rows="3">{{ $editMeeting?->agenda }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3">{{ $editMeeting?->remarks }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="available" @selected(($editMeeting?->status ?? 'available') === 'available')>Available</option>
                            <option value="booked" @selected(($editMeeting?->status ?? '') === 'booked')>Booked</option>
                            <option value="completed" @selected(($editMeeting?->status ?? '') === 'completed')>Completed</option>
                            <option value="cancelled" @selected(($editMeeting?->status ?? '') === 'cancelled')>Cancelled</option>
                        </select>
                    </div>
                    <button class="btn btn-primary">{{ $editMeeting ? 'Update Meeting' : 'Create Meeting' }}</button>
                    @if($editMeeting)
                        <a href="{{ route('supervisor.meetings.index') }}" class="btn btn-outline-secondary">Cancel Edit</a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Upcoming Meetings</h5>
                <span class="badge bg-primary">{{ $upcomingMeetings->count() }}</span>
            </div>
            <div class="card-body">
                @forelse($upcomingMeetings as $meeting)
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                            <div>
                                <strong>{{ $meeting->project?->title ?? 'General Consultation' }}</strong>
                                <div class="small text-muted">Team: {{ $meeting->project?->team?->name ?? 'N/A' }}</div>
                            </div>
                            <span class="badge bg-info text-dark text-uppercase">{{ $meeting->status }}</span>
                        </div>
                        <div class="small mb-1"><strong>Start:</strong> {{ $meeting->starts_at?->format('d M Y, h:i A') }}</div>
                        <div class="small mb-1"><strong>End:</strong> {{ $meeting->ends_at?->format('d M Y, h:i A') }}</div>
                        <div class="small mb-1"><strong>Venue / Link:</strong> {{ $meeting->venue_or_link ?: 'N/A' }}</div>
                        <div class="small mb-1"><strong>Agenda:</strong> {{ $meeting->agenda ?: 'N/A' }}</div>
                        <div class="small mb-2"><strong>Remarks:</strong> {{ $meeting->remarks ?: 'N/A' }}</div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('supervisor.meetings.index', ['edit' => $meeting->id]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('supervisor.meetings.destroy', $meeting) }}" onsubmit="return confirm('Cancel this meeting?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Cancel</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="mb-0">No upcoming meetings scheduled.</p>
                @endforelse
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Past Meetings</h5>
                <span class="badge bg-secondary">{{ $pastMeetings->count() }}</span>
            </div>
            <div class="card-body">
                @forelse($pastMeetings as $meeting)
                    <div class="border rounded p-3 mb-3">
                        <strong>{{ $meeting->project?->title ?? 'General Consultation' }}</strong>
                        <div class="small text-muted">{{ $meeting->starts_at?->format('d M Y, h:i A') }} - {{ $meeting->ends_at?->format('h:i A') }}</div>
                        <div class="small text-muted">Status: {{ ucfirst($meeting->status) }}</div>
                    </div>
                @empty
                    <p class="mb-0">No past meetings recorded.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
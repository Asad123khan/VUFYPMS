@extends('layouts.vufypms')

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Create Team</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('student.teams.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Team Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <select class="form-select" name="semester_id">
                            <option value="">Select</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary">Create Team</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm mt-4">
            <div class="card-header bg-white"><h5 class="mb-0">Pending Invitations</h5></div>
            <div class="card-body">
                @forelse($pendingInvites as $invite)
                    <div class="border rounded p-2 mb-2">
                        <div><strong>{{ $invite->team->name }}</strong></div>
                        <small class="text-muted">Leader: {{ $invite->team->leader->name }}</small>
                        <form method="POST" action="{{ route('student.teams.respondInvite', $invite) }}" class="mt-2 d-flex gap-2">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-success btn-sm" name="action" value="accepted">Accept</button>
                            <button class="btn btn-outline-danger btn-sm" name="action" value="rejected">Reject</button>
                        </form>
                    </div>
                @empty
                    <p class="mb-0">No pending invitations.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">My Team</h5></div>
            <div class="card-body">
                @if($myMembership && $myMembership->team)
                    <h5>{{ $myMembership->team->name }}</h5>
                    <p class="text-muted">Status: {{ ucfirst($myMembership->team->status) }}</p>
                    <ul class="list-group mb-3">
                        @foreach($myMembership->team->members as $member)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $member->student->name }}</span>
                                <span class="badge bg-secondary">{{ $member->role }} / {{ $member->invite_status }}</span>
                            </li>
                        @endforeach
                    </ul>

                    @if($myMembership->role === 'leader')
                        <h6>Invite Member</h6>
                        <form method="POST" action="{{ route('student.teams.invite') }}" class="row g-2">
                            @csrf
                            <input type="hidden" name="team_id" value="{{ $myMembership->team_id }}">
                            <div class="col-md-8">
                                <select name="student_id" class="form-select" required>
                                    <option value="">Select Student</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary w-100">Send Invite</button>
                            </div>
                        </form>
                    @endif
                @else
                    <p class="mb-0">You are not part of an accepted team yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

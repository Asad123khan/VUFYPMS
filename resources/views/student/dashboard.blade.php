@extends('layouts.vufypms')

@section('content')
<div class="hero-strip p-4 mb-4">
    <h3 class="mb-1">Student Dashboard</h3>
    <p class="mb-0">Track your team, proposal, supervisor, and notifications from one place.</p>
</div>

<div class="row g-3">
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><small class="text-muted">My Team</small><h4 class="mb-0">View Team</h4></div></div></div>
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><small class="text-muted">Proposal Status</small><h4 class="mb-0">Check Status</h4></div></div></div>
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><small class="text-muted">Supervisor</small><h4 class="mb-0">Assigned</h4></div></div></div>
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><small class="text-muted">Notifications</small><h4 class="mb-0">Updates</h4></div></div></div>
</div>

<div class="card shadow-sm mt-4">
    <div class="card-body">
        <a class="btn btn-primary me-2" href="{{ route('student.teams.index') }}">Manage Team</a>
        <a class="btn btn-outline-primary me-2" href="{{ route('student.project.index') }}">Proposal</a>
        <a class="btn btn-outline-primary" href="{{ route('student.documents.index') }}">Documents</a>
    </div>
</div>
@endsection

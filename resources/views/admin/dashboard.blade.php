@extends('layouts.vufypms')

@section('content')
<div class="hero-strip p-4 mb-4">
    <h3 class="mb-1">Admin Dashboard</h3>
    <p class="mb-0">Monitor users, projects, domains, and semester activity.</p>
</div>

<div class="row g-3">
    <div class="col-md-4"><div class="card shadow-sm"><div class="card-body"><small class="text-muted">Total Students</small><h4 class="mb-0">Manage</h4></div></div></div>
    <div class="col-md-4"><div class="card shadow-sm"><div class="card-body"><small class="text-muted">Total Supervisors</small><h4 class="mb-0">Manage</h4></div></div></div>
    <div class="col-md-4"><div class="card shadow-sm"><div class="card-body"><small class="text-muted">Total Projects</small><h4 class="mb-0">Monitor</h4></div></div></div>
</div>

<div class="card shadow-sm mt-4">
    <div class="card-body d-flex flex-wrap gap-2">
        <a class="btn btn-primary" href="{{ route('admin.users.index') }}">Users</a>
        <a class="btn btn-outline-primary" href="{{ route('admin.domains.index') }}">Domains</a>
        <a class="btn btn-outline-primary" href="{{ route('admin.semesters.index') }}">Semesters</a>
        <a class="btn btn-outline-primary" href="{{ route('admin.projects.index') }}">Projects</a>
    </div>
</div>
@endsection

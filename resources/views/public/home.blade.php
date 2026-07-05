@extends('layouts.vufypms')

@section('content')
<div class="p-4 p-md-5 mb-4 hero-strip hero-strip--landing">
   <div>
     <h1 class="display-6 fw-bold">Virtual University Final Year Project Management System</h1>
    <p class="mb-0">Centralized portal for teams, supervisors, and administrators to manage complete FYP lifecycle.</p>
   </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Published Projects</h5>
                <form method="GET" action="{{ route('home') }}" class="d-flex gap-2">
                    <input type="text" class="form-control" name="q" value="{{ $search }}" placeholder="Search by title">
                    <button class="btn btn-primary">Search</button>
                </form>
            </div>
            <div class="card-body">
                @forelse($projects as $project)
                    <div class="border rounded p-3 mb-3">
                        <h6 class="fw-bold mb-1">{{ $project->title }}</h6>
                        <p class="mb-1 text-muted">Domain: {{ $project->domain->name ?? 'N/A' }} | Team: {{ $project->team->name ?? 'N/A' }}</p>
                        <p class="mb-0">{{ \Illuminate\Support\Str::limit($project->abstract, 180) }}</p>
                    </div>
                @empty
                    <p class="mb-0">No published projects found.</p>
                @endforelse
                <div>{{ $projects->links() }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Latest Announcements</h5></div>
            <div class="card-body">
                @forelse($announcements as $announcement)
                    <div class="mb-3 pb-2 border-bottom">
                        <h6 class="mb-1">{{ $announcement->title }}</h6>
                        <small class="text-muted">{{ optional($announcement->publish_date)->format('M d, Y') ?? $announcement->created_at->format('M d, Y') }}</small>
                    </div>
                @empty
                    <p class="mb-0">No announcements yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

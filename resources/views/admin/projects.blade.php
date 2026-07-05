@extends('layouts.vufypms')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">Team & Supervisor Allocation</h5></div>
    <div class="card-body">
        @forelse($projects as $project)
            <div class="border rounded p-3 mb-3">
                <h5 class="mb-1">{{ $project->title }}</h5>
                <small class="text-muted">Team: {{ $project->team->name ?? 'N/A' }} | Current Supervisor: {{ $project->supervisor->name ?? 'Not Assigned' }}</small>

                <form method="POST" action="{{ route('admin.projects.update', $project) }}" class="row g-2 mt-2">
                    @csrf
                    @method('PATCH')
                    <div class="col-md-3">
                        <select class="form-select" name="supervisor_id">
                            <option value="">Assign Supervisor</option>
                            @foreach($supervisors as $supervisor)
                                <option value="{{ $supervisor->id }}" @selected($project->supervisor_id === $supervisor->id)>{{ $supervisor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" name="presentation_date" value="{{ $project->presentation_date?->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3"><input class="form-control" name="presentation_venue" placeholder="Venue" value="{{ $project->presentation_venue }}"></div>
                    <div class="col-md-3"><input class="form-control" name="presentation_link" placeholder="Online Link" value="{{ $project->presentation_link }}"></div>
                    <div class="col-md-1 d-flex align-items-center"><input class="form-check-input" type="checkbox" name="is_published" value="1" @checked($project->is_published)></div>
                    <div class="col-12"><button class="btn btn-primary btn-sm">Update</button></div>
                </form>
            </div>
        @empty
            <p class="mb-0">No projects yet.</p>
        @endforelse

        {{ $projects->links() }}
    </div>
</div>
@endsection

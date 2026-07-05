@extends('layouts.vufypms')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Project Proposal</h5>
        @if($project)
            <span class="badge bg-info text-dark">Status: {{ $project->proposal_status }}</span>
        @endif
    </div>
    <div class="card-body">
        @if(!$membership)
            <p class="mb-0">Join or create a team first.</p>
        @else
            <form method="POST" action="{{ route('student.project.storeOrUpdate') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Project Title</label>
                        <input class="form-control" name="title" value="{{ old('title', $project->title ?? '') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Domain</label>
                        <select class="form-select" name="domain_id">
                            <option value="">Select</option>
                            @foreach($domains as $domain)
                                <option value="{{ $domain->id }}" @selected(($project->domain_id ?? null) == $domain->id)>{{ $domain->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Preferred Supervisor</label>
                        <select class="form-select" name="supervisor_id">
                            <option value="">Select</option>
                            @foreach($supervisors as $supervisor)
                                <option value="{{ $supervisor->id }}" @selected(($project->supervisor_id ?? null) == $supervisor->id)>{{ $supervisor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Abstract</label>
                        <textarea class="form-control" name="abstract" rows="4" required>{{ old('abstract', $project->abstract ?? '') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Tools & Technologies</label>
                        <textarea class="form-control" name="tools_technologies" rows="3">{{ old('tools_technologies', $project->tools_technologies ?? '') }}</textarea>
                    </div>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-primary">Save Proposal</button>
                </div>
            </form>

            @if($project && in_array($project->proposal_status, ['draft', 'revision_required'], true))
                <form method="POST" action="{{ route('student.project.submit', $project) }}" class="mt-3">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-success">Submit For Review</button>
                </form>
            @endif

            @if($project?->supervisor_remarks)
                <div class="alert alert-warning mt-3 mb-0">
                    <strong>Supervisor Remarks:</strong> {{ $project->supervisor_remarks }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

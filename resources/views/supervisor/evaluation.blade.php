@extends('layouts.vufypms')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Evaluation</h5>
    </div>

    <div class="card-body">

        @forelse($projects as $project)
            <div class="border p-3 mb-3">
                <strong>{{ $project->title }}</strong>
                <p class="mb-0">Project ID: {{ $project->id }}</p>
            </div>
        @empty
            <p>No projects found.</p>
        @endforelse

    </div>
</div>
@endsection
@extends('layouts.vufypms')

@section('content')
<div class="hero-strip p-4 mb-4">
    <h3 class="mb-1">Evaluation Records</h3>
    <p class="mb-0">Manage and track all project evaluations including proposal defense, progress reviews, and final defenses.</p>
</div>

<!-- Add New Evaluation -->
<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Add Evaluation Record</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.evaluations.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Project</label>
                        <select name="project_id" class="form-select" required>
                            <option value="">-- Select Project --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->title }} (Team: {{ $project->team->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Evaluator</label>
                        <input type="text" class="form-control" placeholder="Evaluator name (manual entry)" name="evaluator_name" disabled>
                        <small class="text-muted">Note: Evaluator management to be added</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Evaluation Type</label>
                        <select name="evaluation_type" class="form-select" required>
                            <option value="">-- Select Type --</option>
                            @foreach($evaluationTypes as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Marks (0-100)</label>
                        <input type="number" class="form-control" name="marks" min="0" max="100" step="0.5">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea class="form-control" name="remarks" rows="3"></textarea>
                    </div>
                    <button class="btn btn-primary">Add Evaluation</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Filters</h5></div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.evaluations.index') }}" class="row g-2">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Search project or team..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-6">
                        <select name="evaluation_type" class="form-select">
                            <option value="">All Evaluation Types</option>
                            @foreach($evaluationTypes as $key => $label)
                                <option value="{{ $key }}" @selected(request('evaluation_type') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        <a href="{{ route('admin.evaluations.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Evaluations List -->
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">All Evaluations ({{ $evaluations->total() }})</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                <tr>
                    <th>Project Title</th>
                    <th>Team</th>
                    <th>Evaluation Type</th>
                    <th>Marks</th>
                    <th>Evaluator</th>
                    <th>Remarks</th>
                    <th width="150">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($evaluations as $evaluation)
                    <tr>
                        <td>
                            <div class="small fw-bold">{{ $evaluation->project->title }}</div>
                            <div class="small text-muted">{{ $evaluation->project->semester?->name ?? 'N/A' }}</div>
                        </td>
                        <td>{{ $evaluation->project->team->name ?? 'N/A' }}</td>
                        <td>
                            @if($evaluation->evaluation_type === 'proposal_defense')
                                <span class="badge bg-info">Proposal Defense</span>
                            @elseif($evaluation->evaluation_type === 'progress_review')
                                <span class="badge bg-warning text-dark">Progress Review</span>
                            @elseif($evaluation->evaluation_type === 'final_defense')
                                <span class="badge bg-success">Final Defense</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold">
                                @if($evaluation->marks !== null)
                                    {{ $evaluation->marks }} / 100
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </td>
                        <td>{{ $evaluation->evaluator->name ?? 'N/A' }}</td>
                        <td>
                            <small>{{ Str::limit($evaluation->remarks, 50) }}</small>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $evaluation->id }}">
                                Edit
                            </button>
                            <form method="POST" action="{{ route('admin.evaluations.destroy', $evaluation) }}" class="d-inline" onsubmit="return confirm('Delete this evaluation?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $evaluation->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Evaluation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.evaluations.update', $evaluation) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Marks (0-100)</label>
                                            <input type="number" class="form-control" name="marks" min="0" max="100" step="0.5" value="{{ $evaluation->marks }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Remarks</label>
                                            <textarea class="form-control" name="remarks" rows="3">{{ $evaluation->remarks }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No evaluations found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $evaluations->links() }}
    </div>
</div>
@endsection

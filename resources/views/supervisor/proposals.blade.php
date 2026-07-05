@extends('layouts.vufypms')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">Assigned Proposals</h5></div>
    <div class="card-body">
        @forelse($projects as $project)
            <div class="border rounded p-3 mb-3">
                <h5 class="mb-1">{{ $project->title }}</h5>
                <p class="text-muted mb-2">Team: {{ $project->team->name ?? 'N/A' }} | Domain: {{ $project->domain->name ?? 'N/A' }}</p>
                <p class="mb-2">{{ \Illuminate\Support\Str::limit($project->abstract, 200) }}</p>

                <form method="POST" action="{{ route('supervisor.proposals.updateStatus', $project) }}" class="row g-2 mb-2">
                    @csrf
                    @method('PATCH')
                    <div class="col-md-3">
                        <select name="proposal_status" class="form-select" required>
                            <option value="approved">Approve</option>
                            <option value="revision_required">Revision Required</option>
                            <option value="rejected">Reject</option>
                        </select>
                    </div>
                    <div class="col-md-7">
                        <input class="form-control" name="supervisor_remarks" placeholder="Remarks" value="{{ $project->supervisor_remarks }}">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Update</button>
                    </div>
                </form>

                <form method="POST" action="{{ route('supervisor.proposals.evaluation.store', $project) }}" class="row g-2">
                    @csrf
                    <div class="col-md-3">
                        <select name="evaluation_type" class="form-select" required>
                            <option value="proposal_defense">Proposal Defense</option>
                            <option value="progress_review">Progress Review</option>
                            <option value="final_defense">Final Defense</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" min="0" max="100" name="marks" class="form-control" placeholder="Marks">
                    </div>
                    <div class="col-md-5">
                        <input name="remarks" class="form-control" placeholder="Evaluation remarks">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-success w-100">Save Eval</button>
                    </div>
                </form>
            </div>
        @empty
            <p class="mb-0">No assigned proposals yet.</p>
        @endforelse

        {{ $projects->links() }}
    </div>
</div>
@endsection

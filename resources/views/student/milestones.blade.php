@extends('layouts.vufypms')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="mb-1">Milestones & Progress</h2>
            <p class="text-muted mb-0">Track deliverables for your current project.</p>
        </div>
        @if($project)
            <span class="badge bg-primary">{{ $project->title }}</span>
        @endif
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><small class="text-muted">Total</small><h4 class="mb-0">{{ $summary['total'] }}</h4></div></div></div>
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><small class="text-muted">In Progress</small><h4 class="mb-0">{{ $summary['in_progress'] }}</h4></div></div></div>
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><small class="text-muted">Completed</small><h4 class="mb-0">{{ $summary['completed'] }}</h4></div></div></div>
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body"><small class="text-muted">Overdue</small><h4 class="mb-0">{{ $summary['overdue'] }}</h4></div></div></div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h6 class="mb-0">Add Milestone</h6></div>
            <div class="card-body">
                @if(!$project)
                    <div class="alert alert-warning mb-0">Create your project proposal first.</div>
                @else
                    <form method="POST" action="{{ route('student.milestones.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" maxlength="255" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Due Date</label>
                                <input type="date" name="due_date" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-primary mt-3">Save Milestone</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Project Milestones</h6>
                <span class="badge bg-secondary">{{ $milestones->count() }} items</span>
            </div>
            <div class="card-body">
                @if(!$project)
                    <p class="mb-0">No project found for your account.</p>
                @elseif($milestones->isEmpty())
                    <div class="alert alert-info mb-0">No milestones have been added yet.</div>
                @else
                    @foreach($milestones as $milestone)
                        @php
                            $isOverdue = $milestone->isOverdue();
                            $statusClass = match($milestone->status) {
                                'completed' => 'bg-success',
                                'in_progress' => 'bg-warning text-dark',
                                'overdue' => 'bg-danger',
                                default => 'bg-secondary',
                            };
                        @endphp
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                <div>
                                    <h6 class="mb-1">{{ $milestone->title }}</h6>
                                    <p class="text-muted mb-0">{{ $milestone->description ?: 'No description provided.' }}</p>
                                </div>
                                <span class="badge {{ $statusClass }} text-uppercase">{{ $milestone->status }}</span>
                            </div>
                            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                                @if($milestone->due_date)
                                    <span class="badge {{ $isOverdue ? 'bg-danger' : 'bg-light text-dark' }}">Due {{ $milestone->due_date->format('d M Y') }}</span>
                                @endif
                                @if($isOverdue && $milestone->status !== 'overdue')
                                    <span class="badge bg-danger">Overdue</span>
                                @endif
                            </div>

                            <form method="POST" action="{{ route('student.milestones.update', $milestone) }}" class="row g-2 mb-2">
                                @csrf
                                @method('PATCH')
                                <div class="col-md-4">
                                    <input type="text" name="title" class="form-control" value="{{ $milestone->title }}" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="date" name="due_date" class="form-control" value="{{ $milestone->due_date?->format('Y-m-d') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="status" class="form-select" required>
                                        <option value="pending" @selected($milestone->status === 'pending')>Pending</option>
                                        <option value="in_progress" @selected($milestone->status === 'in_progress')>In Progress</option>
                                        <option value="completed" @selected($milestone->status === 'completed')>Completed</option>
                                        <option value="overdue" @selected($milestone->status === 'overdue')>Overdue</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <textarea name="description" class="form-control" rows="2">{{ $milestone->description }}</textarea>
                                </div>
                                <div class="col-md-6 d-grid">
                                    <button class="btn btn-outline-primary">Update</button>
                                </div>
                            </form>

                            <form method="POST" action="{{ route('student.milestones.destroy', $milestone) }}" onsubmit="return confirm('Delete this milestone?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

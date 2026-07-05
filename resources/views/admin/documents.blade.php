@extends('layouts.vufypms')

@section('content')
<div class="hero-strip p-4 mb-4">
    <h3 class="mb-1">Document Management</h3>
    <p class="mb-0">Review, approve, and manage all student document submissions across projects.</p>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.documents.index') }}" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search project or team..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="document_type" class="form-select form-select-sm">
                    <option value="">All Document Types</option>
                    @foreach($documentTypes as $type)
                        <option value="{{ $type }}" @selected(request('document_type') === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="review_status" class="form-select form-select-sm">
                    <option value="">All Review Status</option>
                    <option value="pending" @selected(request('review_status') === 'pending')>Pending</option>
                    <option value="accepted" @selected(request('review_status') === 'accepted')>Accepted</option>
                    <option value="revision_required" @selected(request('review_status') === 'revision_required')>Revision Required</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
            </div>
            <div class="col-md-12">
                <a href="{{ route('admin.documents.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Documents List -->
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Document Submissions ({{ $documents->total() }})</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                <tr>
                    <th>Project</th>
                    <th>Team</th>
                    <th>Supervisor</th>
                    <th>Document Type</th>
                    <th>Uploaded By</th>
                    <th>Version</th>
                    <th>Status</th>
                    <th width="180">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($documents as $document)
                    <tr>
                        <td>
                            <div class="fw-bold small">{{ $document->project->title }}</div>
                            <div class="small text-muted">{{ $document->project->semester?->name ?? 'N/A' }}</div>
                        </td>
                        <td>{{ $document->project->team->name ?? 'N/A' }}</td>
                        <td>
                            <small>{{ $document->project->supervisor->name ?? 'Not Assigned' }}</small>
                        </td>
                        <td>{{ $document->document_type }}</td>
                        <td>{{ $document->uploader->name }}</td>
                        <td class="text-center">{{ $document->version }}</td>
                        <td>
                            @if($document->review_status === 'pending')
                                <span class="badge bg-warning text-dark">Pending Review</span>
                            @elseif($document->review_status === 'accepted')
                                <span class="badge bg-success">Accepted</span>
                            @elseif($document->review_status === 'revision_required')
                                <span class="badge bg-danger">Revision Required</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.documents.download', $document) }}" class="btn btn-info" title="Download">
                                    ⬇️
                                </a>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $document->id }}" title="Review">
                                    ✏️
                                </button>
                                <form method="POST" action="{{ route('admin.documents.destroy', $document) }}" class="d-inline" onsubmit="return confirm('Delete this document?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Review Modal -->
                    <div class="modal fade" id="reviewModal{{ $document->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Review Document</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.documents.updateReview', $document) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Review Status</label>
                                            <select name="review_status" class="form-select" required>
                                                <option value="pending" @selected($document->review_status === 'pending')>Pending Review</option>
                                                <option value="accepted" @selected($document->review_status === 'accepted')>Accepted</option>
                                                <option value="revision_required" @selected($document->review_status === 'revision_required')>Revision Required</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Review Comments</label>
                                            <textarea class="form-control" name="review_comments" rows="4" placeholder="Add comments for the student...">{{ $document->review_comments }}</textarea>
                                        </div>

                                        <!-- Document Info -->
                                        <div class="alert alert-info mb-0">
                                            <small>
                                                <strong>Project:</strong> {{ $document->project->title }}<br>
                                                <strong>Team:</strong> {{ $document->project->team->name }}<br>
                                                <strong>Supervisor:</strong> {{ $document->project->supervisor->name ?? 'Not Assigned' }}<br>
                                                <strong>Domain:</strong> {{ $document->project->domain->name ?? 'N/A' }}<br>
                                                <strong>Document Type:</strong> {{ $document->document_type }}<br>
                                                <strong>Version:</strong> {{ $document->version }}<br>
                                                <strong>Uploaded:</strong> {{ $document->created_at->format('M d, Y H:i') }}<br>
                                                <strong>Uploaded By:</strong> {{ $document->uploader->name }}<br>
                                                @if($document->notes)
                                                    <strong>Student Notes:</strong> {{ $document->notes }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update Review</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            No documents submitted yet.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $documents->links() }}
    </div>
</div>
@endsection

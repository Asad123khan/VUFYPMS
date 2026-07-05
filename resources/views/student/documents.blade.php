@extends('layouts.vufypms')

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Upload Document</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('student.documents.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Document Type</label>
                        <select class="form-select" name="document_type" required>
                            <option value="">Select</option>
                            <option>Proposal</option>
                            <option>SRS</option>
                            <option>Design Document</option>
                            <option>Progress Report</option>
                            <option>Final Report</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File</label>
                        <input class="form-control" type="file" name="file" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                    <button class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Submission History</h5></div>
            <div class="card-body">
                @if(!$project)
                    <p class="mb-0">Create your project proposal first.</p>
                @else
                    @forelse($documents as $doc)
                        <div class="border rounded p-3 mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>{{ $doc->document_type }} (v{{ $doc->version }})</strong>
                                <span class="badge bg-secondary">{{ $doc->review_status }}</span>
                            </div>
                            <small class="text-muted">Uploaded by {{ $doc->uploader->name }} on {{ $doc->created_at->format('M d, Y H:i') }}</small>
                            @if($doc->review_comments)
                                <p class="mb-2 mt-2"><strong>Feedback:</strong> {{ $doc->review_comments }}</p>
                            @endif
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('student.documents.download', $doc) }}">Download</a>
                        </div>
                    @empty
                        <p class="mb-0">No document submitted yet.</p>
                    @endforelse
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

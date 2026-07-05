@extends('layouts.vufypms')

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Publish Announcement</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.announcements.store') }}">
                    @csrf
                    <div class="mb-3"><label class="form-label">Title</label><input class="form-control" name="title" required></div>
                    <div class="mb-3"><label class="form-label">Content</label><textarea class="form-control" name="content" rows="4" required></textarea></div>
                    <div class="row g-2 mb-3">
                        <div class="col"><label class="form-label">Publish Date</label><input type="date" class="form-control" name="publish_date"></div>
                        <div class="col"><label class="form-label">Expiry Date</label><input type="date" class="form-control" name="expiry_date"></div>
                    </div>
                    <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="is_public" value="1" checked> <label class="form-check-label">Public</label></div>
                    <button class="btn btn-primary">Publish</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Existing Announcements</h5></div>
            <div class="card-body">
                @foreach($announcements as $announcement)
                    <div class="border rounded p-3 mb-2">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $announcement->title }}</strong>
                            <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </div>
                        <small class="text-muted">By {{ $announcement->creator->name ?? 'N/A' }}</small>
                        <p class="mb-0 mt-2">{{ $announcement->content }}</p>
                    </div>
                @endforeach

                {{ $announcements->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.vufypms')

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Create Semester</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.semesters.store') }}">
                    @csrf
                    <div class="mb-3"><label class="form-label">Name</label><input class="form-control" name="name" placeholder="Spring 2026" required></div>
                    <div class="row g-2 mb-3">
                        <div class="col"><label class="form-label">Start Date</label><input type="date" class="form-control" name="start_date" required></div>
                        <div class="col"><label class="form-label">End Date</label><input type="date" class="form-control" name="end_date" required></div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col"><label class="form-label">Proposal Deadline</label><input type="date" class="form-control" name="proposal_deadline"></div>
                        <div class="col"><label class="form-label">Final Deadline</label><input type="date" class="form-control" name="final_deadline"></div>
                    </div>
                    <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="is_active" value="1" checked> <label class="form-check-label">Active</label></div>
                    <button class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Semesters</h5></div>
            <div class="card-body">
                @foreach($semesters as $semester)
                    <div class="border rounded p-3 mb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $semester->name }}</strong>
                            <div class="small text-muted">{{ $semester->start_date->format('M d, Y') }} - {{ $semester->end_date->format('M d, Y') }}</div>
                        </div>
                        <form method="POST" action="{{ route('admin.semesters.destroy', $semester) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </div>
                @endforeach

                {{ $semesters->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

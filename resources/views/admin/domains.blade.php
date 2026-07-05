@extends('layouts.vufypms')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Add Domain</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.domains.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                        <label class="form-check-label">Active</label>
                    </div>
                    <button class="btn btn-primary">Save Domain</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">All Domains</h5></div>
            <div class="card-body">
                @foreach($domains as $domain)
                    <div class="border rounded p-3 mb-2">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-10">
                                <form method="POST" action="{{ route('admin.domains.update', $domain) }}" class="row g-2">
                                    @csrf
                                    @method('PATCH')
                                    <div class="col-md-4"><input class="form-control" name="name" value="{{ $domain->name }}" required></div>
                                    <div class="col-md-4"><input class="form-control" name="description" value="{{ $domain->description }}"></div>
                                    <div class="col-md-2 d-flex align-items-center">
                                        <div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" value="1" @checked($domain->is_active)> <label class="form-check-label">Active</label></div>
                                    </div>
                                    <div class="col-md-2"><button class="btn btn-sm btn-primary w-100">Update</button></div>
                                </form>
                            </div>
                            <div class="col-md-2 d-grid">
                                <form method="POST" action="{{ route('admin.domains.destroy', $domain) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{ $domains->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

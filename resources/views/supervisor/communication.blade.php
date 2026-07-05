@extends('layouts.vufypms')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Communication</h5>
    </div>

    <div class="card-body">
        @forelse($messages as $msg)
            <div class="border rounded p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>{{ $msg->student?->name ?? 'Student' }}</strong>
                    <span class="badge bg-secondary text-uppercase">{{ $msg->status }}</span>
                </div>
                <p class="mb-2">{{ $msg->message }}</p>

                @if($msg->reply)
                    <div class="alert alert-success mb-3">
                        <strong>Reply:</strong> {{ $msg->reply }}
                    </div>
                @endif

                <form method="POST" action="{{ route('supervisor.communication.reply', $msg) }}" class="row g-2">
                    @csrf
                    <div class="col-md-10">
                        <input type="text" name="reply" class="form-control" placeholder="Write a reply" value="{{ old('reply', $msg->reply) }}" required>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Reply</button>
                    </div>
                </form>
            </div>
        @empty
            <p class="mb-0">No messages found.</p>
        @endforelse
    </div>
</div>
@endsection
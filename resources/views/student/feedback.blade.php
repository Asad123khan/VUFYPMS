@extends('layouts.vufypms')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-3">Supervisor Feedback</h2>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Messages</h6>
                <span class="badge bg-secondary">{{ $messages->count() }} total</span>
            </div>
            <div class="card-body">
                @if(!$membership)
                    <div class="alert alert-warning mb-0">Join or create a team first, then assign a supervisor to your project.</div>
                @elseif($messages->isEmpty())
                    <div class="alert alert-info mb-0">No feedback has been sent or received yet.</div>
                @else
                    @foreach($messages as $message)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>{{ $message->supervisor?->name ?? 'Supervisor' }}</strong>
                                <span class="badge bg-secondary text-uppercase">{{ $message->status }}</span>
                            </div>
                            <p class="mb-2">{{ $message->message }}</p>
                            @if($message->reply)
                                <div class="alert alert-success mb-0">
                                    <strong>Reply:</strong> {{ $message->reply }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0">Supervisor Info</h6>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Name:</strong> <span class="text-muted">{{ $supervisor?->name ?? 'Not Assigned' }}</span></p>
                <p class="mb-2"><strong>Email:</strong> <span class="text-muted">{{ $supervisor?->email ?? 'Not Assigned' }}</span></p>
                <p class="mb-0"><strong>Office Hours:</strong> <span class="text-muted">Not Assigned</span></p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0">Send Message</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('student.feedback.send') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="message" class="form-control" rows="5" placeholder="Write your message to supervisor..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" @disabled(!$supervisor)>Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

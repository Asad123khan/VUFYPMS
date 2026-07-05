@extends('layouts.vufypms')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white"><h4 class="mb-0">Public Announcements</h4></div>
    <div class="card-body">
        @forelse($announcements as $announcement)
            <div class="border rounded p-3 mb-3">
                <h5 class="mb-1">{{ $announcement->title }}</h5>
                <small class="text-muted">{{ $announcement->created_at->format('M d, Y') }}</small>
                <p class="mt-2 mb-0">{{ $announcement->content }}</p>
            </div>
        @empty
            <p class="mb-0">No announcements available.</p>
        @endforelse

        {{ $announcements->links() }}
    </div>
</div>
@endsection

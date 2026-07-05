@extends('layouts.vufypms')

@section('content')

<div class="card">
    <div class="card-header">
        <h5>Assigned Teams</h5>
    </div>

    <div class="card-body">

        @forelse($teams as $team)
            <div class="border p-3 mb-3">

                <strong>{{ $team->name }}</strong>
                <p class="mb-1">Status: {{ $team->status }}</p>

                <hr>

                <h6>Team Members:</h6>

                <ul class="mb-0">
                    @foreach($team->members as $member)
                        <li>
                            {{ $member->student->name }} ({{ $member->role }})
                        </li>
                    @endforeach
                </ul>

            </div>

        @empty
            <p>No teams assigned yet.</p>
        @endforelse

    </div>
</div>

@endsection
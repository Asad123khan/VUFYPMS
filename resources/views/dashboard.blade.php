@extends('layouts.vufypms')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="hero-strip p-4">
                <h3 class="mb-2">Welcome, {{ Auth::user()->name }}! 👋</h3>
                <p class="mb-0">You are logged in as: <strong>{{ ucfirst(Auth::user()->role) }}</strong></p>
            </div>
        </div>
    </div>

    {{-- STUDENT DASHBOARD --}}
    @if(Auth::user()->role === 'student')
        <div class="row">
            <!-- Quick Stats -->
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5>👥 My Team</h5>
                        <p class="text-muted mb-0">View team members</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5>📄 Proposal</h5>
                        <p class="text-muted mb-0">Project proposal status</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5>📁 Documents</h5>
                        <p class="text-muted mb-0">Upload & track files</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5>📊 Milestones</h5>
                        <p class="text-muted mb-0">Track progress</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-6 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">💬 Recent Supervisor Feedback</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-0">No feedback yet</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">🗓️ Upcoming Events</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-0">No upcoming events</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- SUPERVISOR DASHBOARD --}}
    @if(Auth::user()->role === 'supervisor')
        <div class="row">
            <!-- Quick Stats -->
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5>👥 Assigned Teams</h5>
                        <p class="text-muted mb-0">View your teams</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5>📄 Proposals</h5>
                        <p class="text-muted mb-0">Pending reviews</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5>📝 Evaluations</h5>
                        <p class="text-muted mb-0">Mark submissions</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5>💬 Messages</h5>
                        <p class="text-muted mb-0">Communication</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-12 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">📋 Teams Under Your Supervision</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-0">No teams assigned yet</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ADMIN DASHBOARD --}}
    @if(Auth::user()->role === 'admin')
        <div class="row">
            <!-- Quick Stats -->
            <div class="col-md-2 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5>👥 Users</h5>
                        <p class="text-muted mb-0">Total users</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5>📚 Domains</h5>
                        <p class="text-muted mb-0">Project domains</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5>👥 Teams</h5>
                        <p class="text-muted mb-0">Active teams</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5>📄 Proposals</h5>
                        <p class="text-muted mb-0">All proposals</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5>📝 Evaluations</h5>
                        <p class="text-muted mb-0">Submitted</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5>📊 Reports</h5>
                        <p class="text-muted mb-0">Analytics</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-6 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">🗓️ System Overview</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Current Semester:</strong> Manage semesters and deadlines</p>
                        <p class="text-muted mb-0">Set phase dates and evaluation windows</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">📢 Recent Activity</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-0">System activity log</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
 
<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Project;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            $stats = [
                'students' => User::where('role', 'student')->count(),
                'supervisors' => User::where('role', 'supervisor')->count(),
                'projects' => Project::count(),
                'pending_proposals' => Project::where('proposal_status', 'submitted')->count(),
            ];
        } elseif ($user->role === 'supervisor') {
            $stats = [
                'assigned_projects' => Project::where('supervisor_id', $user->id)->count(),
                'pending_reviews' => Project::where('supervisor_id', $user->id)
                    ->whereIn('proposal_status', ['submitted', 'revision_required'])
                    ->count(),
                'approved' => Project::where('supervisor_id', $user->id)
                    ->where('proposal_status', 'approved')
                    ->count(),
            ];
        } else {
            $teamMember = TeamMember::where('student_id', $user->id)
                ->where('invite_status', 'accepted')
                ->with('team.project')
                ->first();

            $project = $teamMember?->team?->project;

            $stats = [
                'team_name' => $teamMember?->team?->name,
                'proposal_status' => $project?->proposal_status,
                'milestones' => $project ? $project->milestones()->count() : 0,
                'documents' => $project ? $project->documents()->count() : 0,
            ];
        }

        $latestAnnouncements = Announcement::latest()->take(5)->get();

        return view('dashboard', compact('stats', 'latestAnnouncements'));
    }
}

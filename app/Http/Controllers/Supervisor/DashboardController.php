<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\DocumentSubmission;
use App\Models\Evaluation;
use App\Models\FeedbackMessage;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $supervisorId = $request->user()->id;

        $assignedTeams = Team::query()
            ->where('supervisor_id', $supervisorId)
            ->with(['leader', 'members.student', 'project'])
            ->latest()
            ->get();

        $supervisedProjects = Project::query()
            ->where('supervisor_id', $supervisorId)
            ->with(['team', 'domain', 'documents', 'evaluations'])
            ->latest()
            ->get();

        $projectIds = $supervisedProjects->pluck('id');

        $stats = [
            'teams' => $assignedTeams->count(),
            'projects' => $supervisedProjects->count(),
            'pending_proposals' => $supervisedProjects->where('proposal_status', 'submitted')->count(),
            'documents' => DocumentSubmission::query()->whereIn('project_id', $projectIds)->count(),
            'feedback' => FeedbackMessage::query()->where('supervisor_id', $supervisorId)->count(),
            'evaluations' => Evaluation::query()->whereIn('project_id', $projectIds)->count(),
            'published_presentations' => $supervisedProjects->where('is_published', true)->whereNotNull('presentation_date')->count(),
        ];

        $recentProjects = $supervisedProjects->take(5);
        $recentTeams = $assignedTeams->take(5);

        return view('supervisor.dashboard', compact('stats', 'recentProjects', 'recentTeams'));
    }
}
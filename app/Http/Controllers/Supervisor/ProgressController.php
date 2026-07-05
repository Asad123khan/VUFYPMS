<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProgressController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'team_id' => ['nullable', 'integer', Rule::exists('teams', 'id')->where('supervisor_id', $request->user()->id)],
            'project_id' => ['nullable', 'integer', Rule::exists('projects', 'id')->where('supervisor_id', $request->user()->id)],
        ]);

        $projectsQuery = Project::query()
            ->where('supervisor_id', $request->user()->id)
            ->with([
                'team.members.student',
                'milestones',
                'documents.uploader',
                'evaluations.evaluator',
                'domain',
                'semester',
            ])
            ->latest();

        if (!empty($filters['team_id'])) {
            $projectsQuery->where('team_id', $filters['team_id']);
        }

        if (!empty($filters['project_id'])) {
            $projectsQuery->whereKey($filters['project_id']);
        }

        $projects = $projectsQuery->get();

        $teams = $projects
            ->pluck('team')
            ->filter()
            ->unique('id')
            ->values();

        $overall = [
            'projects' => $projects->count(),
            'teams' => $teams->count(),
            'milestones_total' => $projects->sum(fn (Project $project) => $project->milestones->count()),
            'milestones_completed' => $projects->sum(fn (Project $project) => $project->milestones->where('status', 'completed')->count()),
            'milestones_overdue' => $projects->sum(fn (Project $project) => $project->milestones->filter(fn ($milestone) => $milestone->due_date && $milestone->due_date->isPast() && $milestone->status !== 'completed')->count()),
            'documents' => $projects->sum(fn (Project $project) => $project->documents->count()),
            'pending_documents' => $projects->sum(fn (Project $project) => $project->documents->where('review_status', 'pending')->count()),
        ];

        return view('supervisor.progress', compact('projects', 'teams', 'overall', 'filters'));
    }
}
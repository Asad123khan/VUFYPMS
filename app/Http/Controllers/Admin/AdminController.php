<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Team;
use App\Models\Project;
use App\Models\Evaluation;
use App\Models\ProjectDomain;
use App\Models\DocumentSubmission;
use App\Models\Semester;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function reports(Request $request)
    {
        // Statistics
        $stats = [
            'total_students' => User::where('role', 'student')->count(),
            'total_supervisors' => User::where('role', 'supervisor')->count(),
            'total_teams' => Team::count(),
            'total_projects' => Project::count(),
            'active_projects' => Project::where('is_published', true)->count(),
            'teams_active' => Team::where('status', 'active')->count(),
            'documents_submitted' => DocumentSubmission::count(),
            'documents_pending_review' => DocumentSubmission::where('review_status', 'pending')->count(),
            'total_evaluations' => Evaluation::count(),
        ];

        // Proposal Status Statistics
        $proposalStats = Project::selectRaw('proposal_status, COUNT(*) as count')
            ->groupBy('proposal_status')
            ->get()
            ->pluck('count', 'proposal_status')
            ->toArray();

        // Domain Statistics
        $domainStats = Project::with('domain')
            ->selectRaw('domain_id, COUNT(*) as count')
            ->groupBy('domain_id')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->domain->name ?? 'Unassigned',
                    'count' => $item->count
                ];
            });

        // Document Type Statistics
        $documentStats = DocumentSubmission::selectRaw('document_type, COUNT(*) as count')
            ->groupBy('document_type')
            ->get()
            ->pluck('count', 'document_type')
            ->toArray();

        // Evaluation Type Statistics
        $evaluationStats = Evaluation::selectRaw('evaluation_type, COUNT(*) as count')
            ->groupBy('evaluation_type')
            ->get()
            ->pluck('count', 'evaluation_type')
            ->toArray();

        // Document Review Status Statistics
        $reviewStatusStats = DocumentSubmission::selectRaw('review_status, COUNT(*) as count')
            ->groupBy('review_status')
            ->get()
            ->pluck('count', 'review_status')
            ->toArray();

        // Projects per Semester
        $semesterStats = Project::with('semester')
            ->selectRaw('semester_id, COUNT(*) as count')
            ->groupBy('semester_id')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->semester->name ?? 'No Semester',
                    'count' => $item->count
                ];
            });

        // Supervisor Workload (projects per supervisor)
        $supervisorWorkload = Project::with('supervisor')
            ->whereNotNull('supervisor_id')
            ->selectRaw('supervisor_id, COUNT(*) as project_count')
            ->groupBy('supervisor_id')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->supervisor->name,
                    'count' => $item->project_count
                ];
            });

        // Get active semester
        $activeSemester = Semester::where('is_active', true)->first();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            // Can be extended for specific report searches
        }

        return view('admin.reports', compact(
            'stats',
            'proposalStats',
            'domainStats',
            'documentStats',
            'evaluationStats',
            'reviewStatusStats',
            'semesterStats',
            'supervisorWorkload',
            'activeSemester'
        ));
    }

    public function archive()
    {
        return view('admin.archive');
    }
}
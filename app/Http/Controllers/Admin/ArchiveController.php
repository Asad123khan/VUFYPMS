<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Team;
use App\Models\Semester;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['team', 'supervisor', 'semester', 'domain']);

        // Filter by semester
        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }

        // Filter by archive status (archived vs active)
        if ($request->filled('archive_status')) {
            if ($request->archive_status === 'archived') {
                // Get projects that are completed (marked for archival)
                $query->whereIn('proposal_status', ['approved', 'rejected'])
                      ->where('is_published', false);
            } elseif ($request->archive_status === 'active') {
                // Active projects
                $query->where(function ($q) {
                    $q->whereIn('proposal_status', ['draft', 'submitted', 'revision_required'])
                      ->orWhere('is_published', true);
                });
            }
        }

        // Search by project title or team name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                  ->orWhereHas('team', function ($q2) use ($search) {
                      $q2->where('name', 'ilike', "%{$search}%");
                  });
            });
        }

        $projects = $query->latest()->paginate(20);
        $semesters = Semester::orderBy('start_date', 'desc')->get();

        return view('admin.archive', compact('projects', 'semesters'));
    }

    public function archive(Request $request, Project $project)
    {
        // Mark project as unpublished (archived state)
        $project->update([
            'is_published' => false,
        ]);

        // Also mark team as archived if all its projects are archived
        $teamProjects = Project::where('team_id', $project->team_id)->count();
        $archivedTeamProjects = Project::where('team_id', $project->team_id)
            ->where('is_published', false)
            ->count();

        if ($teamProjects === $archivedTeamProjects) {
            $project->team->update(['status' => 'archived']);
        }

        return redirect()->route('admin.archive.index')->with('success', 'Project archived successfully.');
    }

    public function restore(Request $request, Project $project)
    {
        // Restore project (mark as published)
        $project->update([
            'is_published' => true,
        ]);

        // Restore team to active if it was archived
        if ($project->team->status === 'archived') {
            $project->team->update(['status' => 'active']);
        }

        return redirect()->route('admin.archive.index')->with('success', 'Project restored successfully.');
    }

    public function viewArchiveDetails(Project $project)
    {
        $project->load(['team.members', 'supervisor', 'evaluations', 'documents', 'milestones']);

        return view('admin.archive-details', compact('project'));
    }

    public function bulkArchive(Request $request)
    {
        $validated = $request->validate([
            'semester_id' => ['required', 'exists:semesters,id'],
        ]);

        // Archive all projects in the selected semester
        $projects = Project::where('semester_id', $validated['semester_id'])->get();
        
        foreach ($projects as $project) {
            $project->update(['is_published' => false]);
        }

        // Archive teams associated with these projects
        $teamIds = $projects->pluck('team_id')->unique();
        Team::whereIn('id', $teamIds)->update(['status' => 'archived']);

        return redirect()->route('admin.archive.index')
            ->with('success', 'All projects from selected semester have been archived.');
    }
}

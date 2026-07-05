<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectDomain;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $membership = TeamMember::query()
            ->where('student_id', $request->user()->id)
            ->where('invite_status', 'accepted')
            ->with('team.project')
            ->first();

        $project = $membership?->team?->project;
        $domains = ProjectDomain::where('is_active', true)->orderBy('name')->get();
        $supervisors = User::where('role', 'supervisor')->orderBy('name')->get();

        return view('student.project', compact('membership', 'project', 'domains', 'supervisors'));
    }

    public function storeOrUpdate(Request $request)
    {
        $membership = TeamMember::query()
            ->where('student_id', $request->user()->id)
            ->where('invite_status', 'accepted')
            ->with('team')
            ->firstOrFail();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'abstract' => ['required', 'string'],
            'domain_id' => ['nullable', 'exists:project_domains,id'],
            'supervisor_id' => ['nullable', Rule::exists('users', 'id')->where('role', 'supervisor')],
            'tools_technologies' => ['nullable', 'string'],
        ]);

        $project = Project::updateOrCreate(
            ['team_id' => $membership->team->id],
            [
                'title' => $validated['title'],
                'abstract' => $validated['abstract'],
                'domain_id' => $validated['domain_id'] ?? null,
                'supervisor_id' => $validated['supervisor_id'] ?? null,
                'semester_id' => $membership->team->semester_id,
                'tools_technologies' => $validated['tools_technologies'] ?? null,
            ]
        );

        return redirect()->route('student.project.index')->with('success', 'Project proposal saved.');
    }

    public function submit(Request $request, Project $project)
    {
        $membership = TeamMember::query()
            ->where('student_id', $request->user()->id)
            ->where('invite_status', 'accepted')
            ->first();

        if (!$membership || $membership->team_id !== $project->team_id) {
            abort(403);
        }

        $project->update(['proposal_status' => 'submitted']);

        return redirect()->route('student.project.index')->with('success', 'Proposal submitted for supervisor review.');
    }
}

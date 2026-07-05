<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Milestone;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MilestoneController extends Controller
{
    public function index(Request $request)
    {
        $membership = $this->currentMembership($request);

        $project = $membership?->team?->project;

        if ($project) {
            $this->syncOverdueMilestones($project->id);
        }

        $milestones = $project
            ? $project->milestones()->latest()->get()
            : collect();

        $summary = [
            'total' => $milestones->count(),
            'completed' => $milestones->where('status', 'completed')->count(),
            'overdue' => $milestones->where('status', 'overdue')->count(),
            'in_progress' => $milestones->where('status', 'in_progress')->count(),
        ];

        return view('student.milestones', compact('membership', 'project', 'milestones', 'summary'));
    }

    public function store(Request $request)
    {
        $membership = $this->currentMembership($request);

        if (!$membership?->team?->project) {
            return redirect()->route('student.milestones.index')->with('error', 'Create your project proposal first.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed'])],
        ]);

        Milestone::create([
            'project_id' => $membership->team->project->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
            'status' => $validated['status'],
        ]);

        $this->syncOverdueMilestones($membership->team->project->id);

        return redirect()->route('student.milestones.index')->with('success', 'Milestone added successfully.');
    }

    public function update(Request $request, Milestone $milestone)
    {
        $membership = $this->currentMembership($request);

        if (!$membership?->team?->project || $membership->team->project->id !== $milestone->project_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed', 'overdue'])],
        ]);

        $milestone->update($validated);

        $this->syncOverdueMilestones($milestone->project_id);

        return redirect()->route('student.milestones.index')->with('success', 'Milestone updated successfully.');
    }

    public function destroy(Request $request, Milestone $milestone)
    {
        $membership = $this->currentMembership($request);

        if (!$membership?->team?->project || $membership->team->project->id !== $milestone->project_id) {
            abort(403);
        }

        $milestone->delete();

        return redirect()->route('student.milestones.index')->with('success', 'Milestone deleted successfully.');
    }

    private function currentMembership(Request $request): ?TeamMember
    {
        return TeamMember::query()
            ->where('student_id', $request->user()->id)
            ->where('invite_status', 'accepted')
            ->with('team.project.milestones')
            ->first();
    }

    private function syncOverdueMilestones(int $projectId): void
    {
        Milestone::query()
            ->where('project_id', $projectId)
            ->whereDate('due_date', '<', now()->toDateString())
            ->whereIn('status', ['pending', 'in_progress'])
            ->update(['status' => 'overdue']);
    }
}
<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::query()
            ->with(['team', 'domain'])
            ->where('supervisor_id', $request->user()->id)
            ->latest()
            ->paginate(12);

        return view('supervisor.proposals', compact('projects'));
    }

    public function updateStatus(Request $request, Project $project)
    {
        if ($project->supervisor_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'proposal_status' => ['required', 'in:approved,rejected,revision_required'],
            'supervisor_remarks' => ['nullable', 'string'],
        ]);

        $project->update($validated);

        return redirect()->route('supervisor.proposals.index')->with('success', 'Proposal decision updated.');
    }
}

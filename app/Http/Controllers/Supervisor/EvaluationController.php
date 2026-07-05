<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Project;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function store(Request $request, Project $project)
    {
        if ($project->supervisor_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'evaluation_type' => ['required', 'in:proposal_defense,progress_review,final_defense'],
            'marks' => ['nullable', 'numeric', 'between:0,100'],
            'remarks' => ['nullable', 'string'],
        ]);

        Evaluation::create([
            'project_id' => $project->id,
            'evaluator_id' => $request->user()->id,
            'evaluation_type' => $validated['evaluation_type'],
            'marks' => $validated['marks'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return redirect()->route('supervisor.proposals.index')->with('success', 'Evaluation record saved.');
    }
    public function index()
{
    $projects = Project::where('supervisor_id', auth()->id())->get();

    return view('supervisor.evaluation', compact('projects'));
}
}

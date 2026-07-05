<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Project;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
        $query = Evaluation::with(['project.team', 'evaluator']);

        // Filter by evaluation type
        if ($request->filled('evaluation_type')) {
            $query->where('evaluation_type', $request->evaluation_type);
        }

        // Filter by project
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Search by project title or team name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('project', function ($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                  ->orWhereHas('team', function ($q2) use ($search) {
                      $q2->where('name', 'ilike', "%{$search}%");
                  });
            });
        }

        $evaluations = $query->latest()->paginate(20);
        $projects = Project::with('team')->orderBy('title')->get();
        $evaluationTypes = [
            'proposal_defense' => 'Proposal Defense',
            'progress_review' => 'Progress Review',
            'final_defense' => 'Final Defense'
        ];

        return view('admin.evaluations', compact('evaluations', 'projects', 'evaluationTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'evaluator_id' => ['required', 'exists:users,id'],
            'evaluation_type' => ['required', 'in:proposal_defense,progress_review,final_defense'],
            'marks' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'remarks' => ['nullable', 'string'],
        ]);

        Evaluation::create($validated);

        return redirect()->route('admin.evaluations.index')->with('success', 'Evaluation record added.');
    }

    public function update(Request $request, Evaluation $evaluation)
    {
        $validated = $request->validate([
            'marks' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'remarks' => ['nullable', 'string'],
        ]);

        $evaluation->update($validated);

        return redirect()->route('admin.evaluations.index')->with('success', 'Evaluation updated.');
    }

    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();

        return redirect()->route('admin.evaluations.index')->with('success', 'Evaluation deleted.');
    }
}

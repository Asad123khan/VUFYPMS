<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['team', 'supervisor', 'domain'])->latest()->paginate(15);
        $supervisors = User::where('role', 'supervisor')->orderBy('name')->get();

        return view('admin.projects', compact('projects', 'supervisors'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'supervisor_id' => ['nullable', Rule::exists('users', 'id')->where('role', 'supervisor')],
            'is_published' => ['nullable', 'boolean'],
            'presentation_date' => ['nullable', 'date'],
            'presentation_venue' => ['nullable', 'string', 'max:255'],
            'presentation_link' => ['nullable', 'url', 'max:255'],
        ]);

        $project->update([
            'supervisor_id' => $validated['supervisor_id'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'presentation_date' => $validated['presentation_date'] ?? null,
            'presentation_venue' => $validated['presentation_venue'] ?? null,
            'presentation_link' => $validated['presentation_link'] ?? null,
        ]);

        return redirect()->route('admin.projects.index')->with('success', 'Project allocation updated.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $query = Team::with(['leader', 'supervisor', 'members', 'project']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by semester
        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }

        // Search by team name or leader name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhereHas('leader', function ($q2) use ($search) {
                      $q2->where('name', 'ilike', "%{$search}%");
                  });
            });
        }

        $teams = $query->latest()->paginate(15);
        $supervisors = User::where('role', 'supervisor')->orderBy('name')->get();

        return view('admin.teams', compact('teams', 'supervisors'));
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:forming,active,archived'],
            'supervisor_id' => ['nullable', 'exists:users,id'],
        ]);

        $team->update($validated);

        return redirect()->route('admin.teams.index')->with('success', 'Team updated successfully.');
    }

    public function assignSupervisor(Request $request, Team $team)
    {
        $validated = $request->validate([
            'supervisor_id' => ['nullable', 'exists:users,id'],
        ]);

        // Assign supervisor to team
        $team->update(['supervisor_id' => $validated['supervisor_id']]);

        // Also update the related project supervisor if it exists
        if ($team->project) {
            $team->project->update(['supervisor_id' => $validated['supervisor_id']]);
        }

        return redirect()->route('admin.teams.index')->with('success', 'Supervisor assigned to team.');
    }
}

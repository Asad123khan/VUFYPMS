<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $myMembership = TeamMember::query()
            ->where('student_id', $user->id)
            ->with(['team.members.student'])
            ->latest()
            ->first();

        $pendingInvites = TeamMember::query()
            ->where('student_id', $user->id)
            ->where('invite_status', 'pending')
            ->with('team.leader')
            ->get();

        $students = User::where('role', 'student')->where('id', '!=', $user->id)->orderBy('name')->get();
        $semesters = Semester::orderByDesc('start_date')->get();

        return view('student.teams', compact('myMembership', 'pendingInvites', 'students', 'semesters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'semester_id' => ['nullable', 'exists:semesters,id'],
        ]);

        $team = Team::create([
            'name' => $validated['name'],
            'leader_id' => $request->user()->id,
            'semester_id' => $validated['semester_id'] ?? null,
            'status' => 'forming',
        ]);

        TeamMember::create([
            'team_id' => $team->id,
            'student_id' => $request->user()->id,
            'role' => 'leader',
            'invite_status' => 'accepted',
        ]);

        return redirect()->route('student.teams.index')->with('success', 'Team created successfully.');
    }

    public function invite(Request $request)
    {
        $validated = $request->validate([
            'team_id' => ['required', 'exists:teams,id'],
            'student_id' => ['required', Rule::exists('users', 'id')->where('role', 'student')],
        ]);

        $team = Team::findOrFail($validated['team_id']);

        if ($team->leader_id !== $request->user()->id) {
            abort(403, 'Only team leader can invite members.');
        }

        TeamMember::firstOrCreate(
            [
                'team_id' => $team->id,
                'student_id' => $validated['student_id'],
            ],
            [
                'role' => 'member',
                'invite_status' => 'pending',
            ]
        );

        return redirect()->route('student.teams.index')->with('success', 'Invitation sent.');
    }

    public function respondInvite(Request $request, TeamMember $teamMember)
    {
        if ($teamMember->student_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'action' => ['required', 'in:accepted,rejected'],
        ]);

        $teamMember->update(['invite_status' => $validated['action']]);

        return redirect()->route('student.teams.index')->with('success', 'Invitation response saved.');
    }
}

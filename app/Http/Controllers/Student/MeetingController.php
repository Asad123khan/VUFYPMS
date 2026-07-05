<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ConsultationSlot;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function index(Request $request)
    {
        $membership = TeamMember::query()
            ->where('student_id', $request->user()->id)
            ->where('invite_status', 'accepted')
            ->with('team.project.supervisor')
            ->first();

        $project = $membership?->team?->project;

        $meetings = $project
            ? ConsultationSlot::query()
                ->where('project_id', $project->id)
                ->with(['supervisor', 'project.team'])
                ->orderBy('starts_at')
                ->get()
            : collect();

        $upcomingMeetings = $meetings->filter(fn (ConsultationSlot $meeting) => $meeting->starts_at?->isFuture());
        $pastMeetings = $meetings->filter(fn (ConsultationSlot $meeting) => $meeting->ends_at?->isPast());

        return view('student.meetings', compact('membership', 'project', 'meetings', 'upcomingMeetings', 'pastMeetings'));
    }
}
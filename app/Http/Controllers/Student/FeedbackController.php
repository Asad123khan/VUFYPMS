<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\FeedbackMessage;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $membership = TeamMember::query()
            ->where('student_id', $request->user()->id)
            ->where('invite_status', 'accepted')
            ->with('team.project.supervisor')
            ->first();

        $messages = FeedbackMessage::query()
            ->where('student_id', $request->user()->id)
            ->with('supervisor')
            ->latest()
            ->get();

        $supervisor = $membership?->team?->project?->supervisor;

        return view('student.feedback', compact('membership', 'messages', 'supervisor'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $membership = TeamMember::query()
            ->where('student_id', $request->user()->id)
            ->where('invite_status', 'accepted')
            ->with('team.project.supervisor')
            ->first();

        $supervisorId = $membership?->team?->project?->supervisor_id;

        if (!$supervisorId) {
            return back()->with('error', 'Assign a supervisor to your project before sending feedback.');
        }

        FeedbackMessage::create([
            'student_id' => $request->user()->id,
            'supervisor_id' => $supervisorId,
            'message' => $validated['message'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Message sent to supervisor successfully!');
    }
}

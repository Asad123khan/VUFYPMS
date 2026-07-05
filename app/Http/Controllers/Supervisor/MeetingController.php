<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\ConsultationSlot;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MeetingController extends Controller
{
    public function index(Request $request)
    {
        $meetings = ConsultationSlot::query()
            ->where('supervisor_id', $request->user()->id)
            ->with(['project.team'])
            ->orderBy('starts_at')
            ->get();

        $upcomingMeetings = $meetings->filter(fn (ConsultationSlot $meeting) => $meeting->starts_at?->isFuture());
        $pastMeetings = $meetings->filter(fn (ConsultationSlot $meeting) => $meeting->ends_at?->isPast());

        $assignedProjects = Project::query()
            ->where('supervisor_id', $request->user()->id)
            ->with('team')
            ->orderBy('title')
            ->get();

        $editMeeting = null;

        if ($request->filled('edit')) {
            $editMeeting = ConsultationSlot::query()
                ->where('supervisor_id', $request->user()->id)
                ->with(['project.team'])
                ->findOrFail($request->query('edit'));
        }

        return view('supervisor.meetings', compact('meetings', 'upcomingMeetings', 'pastMeetings', 'assignedProjects', 'editMeeting'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateMeeting($request);

        $project = $this->authorizedProject($request, $validated['project_id']);

        ConsultationSlot::create([
            'supervisor_id' => $request->user()->id,
            'project_id' => $project?->id,
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'venue_or_link' => $validated['venue_or_link'] ?? null,
            'agenda' => $validated['agenda'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('supervisor.meetings.index')->with('success', 'Consultation meeting created successfully.');
    }

    public function update(Request $request, ConsultationSlot $meeting)
    {
        $this->authorizeMeeting($request, $meeting);
        $validated = $this->validateMeeting($request, true);

        $project = $this->authorizedProject($request, $validated['project_id']);

        $meeting->update([
            'project_id' => $project?->id,
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'venue_or_link' => $validated['venue_or_link'] ?? null,
            'agenda' => $validated['agenda'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('supervisor.meetings.index')->with('success', 'Consultation meeting updated successfully.');
    }

    public function destroy(Request $request, ConsultationSlot $meeting)
    {
        $this->authorizeMeeting($request, $meeting);

        $meeting->update(['status' => 'cancelled']);

        return redirect()->route('supervisor.meetings.index')->with('success', 'Consultation meeting cancelled successfully.');
    }

    private function validateMeeting(Request $request, bool $forUpdate = false): array
    {
        return $request->validate([
            'project_id' => ['nullable', Rule::exists('projects', 'id')->where('supervisor_id', $request->user()->id)],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'venue_or_link' => ['nullable', 'string', 'max:255'],
            'agenda' => ['nullable', 'string', 'max:1000'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::in(['available', 'booked', 'completed', 'cancelled'])],
        ]);
    }

    private function authorizeMeeting(Request $request, ConsultationSlot $meeting): void
    {
        if ($meeting->supervisor_id !== $request->user()->id) {
            abort(403);
        }
    }

    private function authorizedProject(Request $request, mixed $projectId): ?Project
    {
        if (!$projectId) {
            return null;
        }

        return Project::query()
            ->whereKey($projectId)
            ->where('supervisor_id', $request->user()->id)
            ->firstOrFail();
    }
}

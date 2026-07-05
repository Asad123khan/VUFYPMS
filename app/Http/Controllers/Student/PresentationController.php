<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class PresentationController extends Controller
{
    public function index(Request $request)
    {
        $membership = TeamMember::query()
            ->where('student_id', $request->user()->id)
            ->where('invite_status', 'accepted')
            ->with(['team.project.supervisor', 'team.project.domain', 'team.project.semester'])
            ->first();

        $project = $membership?->team?->project;
        $presentationStatus = $this->resolvePresentationStatus($project?->presentation_date, $project?->is_published ?? false);

        return view('student.presentations', compact('membership', 'project', 'presentationStatus'));
    }

    private function resolvePresentationStatus(?\Illuminate\Support\Carbon $presentationDate, bool $isPublished): array
    {
        if (!$presentationDate) {
            return [
                'label' => 'Not Scheduled',
                'class' => 'bg-secondary',
            ];
        }

        if (!$isPublished) {
            return [
                'label' => 'Draft',
                'class' => 'bg-warning text-dark',
            ];
        }

        if ($presentationDate->isPast()) {
            return [
                'label' => 'Completed',
                'class' => 'bg-success',
            ];
        }

        return [
            'label' => 'Scheduled',
            'class' => 'bg-primary',
        ];
    }
}
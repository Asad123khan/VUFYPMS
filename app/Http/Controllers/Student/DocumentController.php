<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\DocumentSubmission;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $membership = TeamMember::query()
            ->where('student_id', $request->user()->id)
            ->where('invite_status', 'accepted')
            ->with('team.project.documents.uploader')
            ->first();

        $project = $membership?->team?->project;
        $documents = $project ? $project->documents()->latest()->get() : collect();

        return view('student.documents', compact('membership', 'project', 'documents'));
    }

    public function store(Request $request)
    {
        $membership = TeamMember::query()
            ->where('student_id', $request->user()->id)
            ->where('invite_status', 'accepted')
            ->with('team.project')
            ->firstOrFail();

        $project = $membership->team->project;

        if (!$project) {
            return redirect()->route('student.documents.index')->with('error', 'Create your project proposal first.');
        }

        $validated = $request->validate([
            'document_type' => ['required', 'string', 'max:100'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,zip', 'max:10240'],
            'notes' => ['nullable', 'string'],
        ]);

        $latestVersion = DocumentSubmission::where('project_id', $project->id)
            ->where('document_type', $validated['document_type'])
            ->max('version') ?? 0;

        $path = $request->file('file')->store('project_documents', 'public');

        DocumentSubmission::create([
            'project_id' => $project->id,
            'uploaded_by' => $request->user()->id,
            'document_type' => $validated['document_type'],
            'file_path' => $path,
            'notes' => $validated['notes'] ?? null,
            'version' => $latestVersion + 1,
            'review_status' => 'pending',
        ]);

        return redirect()->route('student.documents.index')->with('success', 'Document uploaded successfully.');
    }

    public function download(DocumentSubmission $documentSubmission)
    {
        $allowed = DB::table('team_members')
            ->join('projects', 'projects.team_id', '=', 'team_members.team_id')
            ->where('team_members.student_id', auth()->id())
            ->where('team_members.invite_status', 'accepted')
            ->where('projects.id', $documentSubmission->project_id)
            ->exists();

        if (!$allowed) {
            abort(403);
        }

        return response()->download(storage_path('app/public/' . $documentSubmission->file_path));
    }
}

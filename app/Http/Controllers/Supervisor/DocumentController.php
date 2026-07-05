<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\DocumentSubmission;
use App\Models\Project;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::query()
            ->with(['team', 'documents.uploader'])
            ->where('supervisor_id', $request->user()->id)
            ->latest()
            ->get();

        return view('supervisor.documents', compact('projects'));
    }

    public function download(Request $request, DocumentSubmission $documentSubmission)
    {
        $allowed = Project::query()
            ->whereKey($documentSubmission->project_id)
            ->where('supervisor_id', $request->user()->id)
            ->exists();

        if (!$allowed) {
            abort(403);
        }

        return response()->download(storage_path('app/public/' . $documentSubmission->file_path));
    }
}
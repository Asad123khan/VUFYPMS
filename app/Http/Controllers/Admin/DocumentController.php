<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentSubmission;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = DocumentSubmission::with(['project.team', 'project.supervisor', 'uploader']);

        // Filter by document type
        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        // Filter by review status
        if ($request->filled('review_status')) {
            $query->where('review_status', $request->review_status);
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

        $documents = $query->latest()->paginate(20);
        $documentTypes = ['Proposal', 'Final Report', 'Presentation Slides', 'Code', 'Documentation'];
        $reviewStatuses = ['pending', 'accepted', 'revision_required'];

        return view('admin.documents', compact('documents', 'documentTypes', 'reviewStatuses'));
    }

    public function download(DocumentSubmission $document)
    {
        // Check if file exists
        if (!file_exists(storage_path('app/' . $document->file_path))) {
            abort(404, 'File not found');
        }

        return response()->download(storage_path('app/' . $document->file_path));
    }

    public function updateReview(Request $request, DocumentSubmission $document)
    {
        $validated = $request->validate([
            'review_status' => ['required', 'in:pending,accepted,revision_required'],
            'review_comments' => ['nullable', 'string'],
        ]);

        $document->update($validated);

        return redirect()->route('admin.documents.index')->with('success', 'Document review status updated.');
    }

    public function delete(DocumentSubmission $document)
    {
        // Delete file if it exists
        if (file_exists(storage_path('app/' . $document->file_path))) {
            unlink(storage_path('app/' . $document->file_path));
        }

        $document->delete();

        return redirect()->route('admin.documents.index')->with('success', 'Document deleted.');
    }
}

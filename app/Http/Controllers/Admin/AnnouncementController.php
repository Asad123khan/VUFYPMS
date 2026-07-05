<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('creator')->latest()->paginate(12);

        return view('admin.announcements', compact('announcements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'content' => ['required', 'string'],
            'publish_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date', 'after_or_equal:publish_date'],
            'is_public' => ['nullable', 'boolean'],
        ]);

        Announcement::create([
            'created_by' => $request->user()->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'publish_date' => $validated['publish_date'] ?? null,
            'expiry_date' => $validated['expiry_date'] ?? null,
            'is_public' => $request->boolean('is_public', true),
        ]);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement published.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement deleted.');
    }
}

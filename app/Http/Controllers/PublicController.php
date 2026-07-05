<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Project;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home(Request $request)
    {
        $search = $request->string('q')->toString();

        $announcements = Announcement::query()
            ->where('is_public', true)
            ->where(function ($query) {
                $query->whereNull('expiry_date')->orWhereDate('expiry_date', '>=', now()->toDateString());
            })
            ->latest()
            ->take(5)
            ->get();

        $projects = Project::query()
            ->with(['domain', 'team'])
            ->where('is_published', true)
            ->when($search, function ($query, $searchValue) {
                $query->where('title', 'like', '%' . $searchValue . '%');
            })
            ->latest()
            ->paginate(10);

        if ($search !== '') {
            $projects->appends(['q' => $search]);
        }

        return view('public.home', compact('announcements', 'projects', 'search'));
    }

  

    public function announcements()
    {
        $announcements = Announcement::query()
            ->where('is_public', true)
            ->latest()
            ->paginate(12);

        return view('public.announcements', compact('announcements'));
    }
}

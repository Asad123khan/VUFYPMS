<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::latest()->paginate(12);

        return view('admin.semesters', compact('semesters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:60', 'unique:semesters,name'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'proposal_deadline' => ['nullable', 'date'],
            'final_deadline' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Semester::create([
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'proposal_deadline' => $validated['proposal_deadline'] ?? null,
            'final_deadline' => $validated['final_deadline'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.semesters.index')->with('success', 'Semester created.');
    }

    public function destroy(Semester $semester)
    {
        $semester->delete();

        return redirect()->route('admin.semesters.index')->with('success', 'Semester deleted.');
    }
}

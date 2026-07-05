<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectDomain;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function index()
    {
        $domains = ProjectDomain::latest()->paginate(12);

        return view('admin.domains', compact('domains'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:project_domains,name'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        ProjectDomain::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.domains.index')->with('success', 'Domain added.');
    }

    public function update(Request $request, ProjectDomain $domain)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:project_domains,name,' . $domain->id],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $domain->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.domains.index')->with('success', 'Domain updated.');
    }

    public function destroy(ProjectDomain $domain)
    {
        $domain->delete();

        return redirect()->route('admin.domains.index')->with('success', 'Domain deleted.');
    }
}

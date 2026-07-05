<?php
namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Team;

class TeamController extends Controller
{
public function index()
    {
        $teams = Team::where('supervisor_id', Auth::id())
            ->with('members.student') // IMPORTANT
            ->get();

        return view('supervisor.assigned-teams', compact('teams'));
    }
}

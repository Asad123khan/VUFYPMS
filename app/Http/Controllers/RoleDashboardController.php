<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleDashboardController extends Controller
{
    public function show(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        return redirect()->route($user->dashboardRoute());
    }
}

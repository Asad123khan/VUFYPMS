<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\FeedbackMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunicationController extends Controller
{
    public function index()
    {
        $messages = FeedbackMessage::where('supervisor_id', Auth::id())
            ->with('student')
            ->latest()
            ->get();

        return view('supervisor.communication', compact('messages'));
    }

    public function reply(Request $request, FeedbackMessage $feedbackMessage)
    {
        if ($feedbackMessage->supervisor_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'reply' => ['required', 'string', 'max:1000'],
        ]);

        $feedbackMessage->update([
            'reply' => $validated['reply'],
            'status' => 'replied',
        ]);

        return redirect()->route('supervisor.communication.index')->with('success', 'Reply sent to student.');
    }
}

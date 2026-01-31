<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Support\Facades\Auth;

class PollController extends Controller
{
    // Show polls page
    public function index()
    {
        return view('polls.index');
    }

    // Get active polls as JSON
    public function getPolls()
    {
        $polls = Poll::where('status', 'active')
                    ->with('creator:id,name')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return response()->json([
            'success' => true,
            'polls' => $polls
        ]);
    }

    // Get single poll with options
    public function show($id)
    {
        $poll = Poll::with(['options', 'creator:id,name'])
                   ->findOrFail($id);

        if (!$poll->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'This poll is closed'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'poll' => $poll
        ]);
    }

    // Create new poll (admin only for now)
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
        ]);

        $poll = Poll::create([
            'question' => $request->question,
            'status' => 'active',
            'created_by' => Auth::id(),
        ]);

        // Create options
        foreach ($request->options as $optionText) {
            PollOption::create([
                'poll_id' => $poll->id,
                'option_text' => $optionText,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Poll created successfully',
            'poll' => $poll->load('options')
        ]);
    }
}

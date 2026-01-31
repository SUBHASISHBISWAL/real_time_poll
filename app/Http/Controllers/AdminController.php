<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\Vote;
use App\Services\VotingService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $votingService;

    public function __construct(VotingService $votingService)
    {
        $this->votingService = $votingService;
    }

    /**
     * Show the admin dashboard with all polls.
     */
    public function dashboard()
    {
        $polls = Poll::withCount('votes')->orderBy('created_at', 'desc')->get();
        return view('admin.dashboard', compact('polls'));
    }

    /**
     * View votes and IPs for a specific poll.
     */
    public function viewVotes($id)
    {
        $poll = Poll::findOrFail($id);
        $votes = Vote::where('poll_id', $id)
                    ->with('option')
                    ->orderBy('voted_at', 'desc')
                    ->get();

        return view('admin.votes', compact('poll', 'votes'));
    }

    /**
     * Release a vote based on IP and Poll ID.
     */
    public function releaseIp(Request $request, $pollId, $ip)
    {
        $success = $this->votingService->releaseVote($pollId, $ip);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => "Vote from IP {$ip} has been released."
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "Failed to release vote. IP might not have voted."
        ], 400);
    }

    /**
     * View audit trail for a specific IP and Poll.
     */
    public function auditTrail($pollId, $ip)
    {
        $history = $this->votingService->getAuditTrail($pollId, $ip);

        return response()->json([
            'success' => true,
            'history' => $history
        ]);
    }
}

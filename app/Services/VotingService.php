<?php

namespace App\Services;

use App\Models\Vote;
use App\Models\VoteHistory;
use Illuminate\Support\Facades\DB;

class VotingService
{
    /**
     * Check if an IP has already voted for a specific poll.
     *
     * @param int $pollId
     * @param string $ip
     * @return bool
     */
    public function hasVoted($pollId, $ip)
    {
        // Simple, human-readable check
        $exists = Vote::where('poll_id', $pollId)
                      ->where('ip_address', $ip)
                      ->exists();

        return $exists;
    }

    /**
     * Record a vote for a poll option.
     *
     * @param int $pollId
     * @param int $optionId
     * @param string $ip
     * @return \App\Models\Vote
     * @throws \Exception
     */
    public function recordVote($pollId, $optionId, $ip)
    {
        // Prevent race conditions with a transaction
        // Not using 'lockForUpdate' to keep it simple for now, can optimize later if high traffic
        return DB::transaction(function () use ($pollId, $optionId, $ip) {
            
            // Double check inside transaction
            if ($this->hasVoted($pollId, $ip)) {
                throw new \Exception('This IP address has already voted for this poll.');
            }

            // Create the vote record
            $vote = new Vote();
            $vote->poll_id = $pollId;
            $vote->option_id = $optionId;
            $vote->ip_address = $ip;
            $vote->voted_at = now();
            $vote->save();

            // Log to history (for Module 4 audit trail)
            VoteHistory::create([
                'poll_id' => $pollId,
                'option_id' => $optionId,
                'ip_address' => $ip,
                'action' => 'voted',
                'created_at' => now(),
            ]);

            return $vote;
        });
    }
}

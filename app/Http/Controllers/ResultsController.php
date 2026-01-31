<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultsController extends Controller
{
    /**
     * Get the results for a specific poll.
     * Returns JSON with vote counts and percentages.
     */
    public function show($id)
    {
        $poll = Poll::with('options')->findOrFail($id);

        // Calculate vote counts for each option
        $results = DB::table('votes')
            ->where('poll_id', $id)
            ->select('option_id', DB::raw('count(*) as count'))
            ->groupBy('option_id')
            ->get()
            ->pluck('count', 'option_id')
            ->toArray();

        $totalVotes = array_sum($results);

        // Fill in missing options with 0 votes
        $formattedResults = $poll->options->map(function ($option) use ($results, $totalVotes) {
            $count = $results[$option->id] ?? 0;
            $percentage = $totalVotes > 0 ? round(($count / $totalVotes) * 100, 1) : 0;

            return [
                'id' => $option->id,
                'option_text' => $option->option_text,
                'votes' => $count,
                'percentage' => $percentage
            ];
        });

        return response()->json([
            'success' => true,
            'poll_question' => $poll->question,
            'total_votes' => $totalVotes,
            'results' => $formattedResults
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResultsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_retrieve_poll_results()
    {
        $user = User::factory()->create();
        $poll = Poll::create(['question' => 'Flavor Poll', 'status' => 'active', 'created_by' => $user->id]);
        $optionA = PollOption::create(['poll_id' => $poll->id, 'option_text' => 'Vanilla']);
        $optionB = PollOption::create(['poll_id' => $poll->id, 'option_text' => 'Chocolate']);

        // Cast some votes
        Vote::create(['poll_id' => $poll->id, 'option_id' => $optionA->id, 'ip_address' => '1.1.1.1']);
        Vote::create(['poll_id' => $poll->id, 'option_id' => $optionA->id, 'ip_address' => '2.2.2.2']);
        Vote::create(['poll_id' => $poll->id, 'option_id' => $optionB->id, 'ip_address' => '3.3.3.3']);

        $response = $this->actingAs($user)
                         ->getJson("/polls/{$poll->id}/results");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'total_votes' => 3,
                     'results' => [
                         ['option_text' => 'Vanilla', 'votes' => 2, 'percentage' => 66.7],
                         ['option_text' => 'Chocolate', 'votes' => 1, 'percentage' => 33.3]
                     ]
                 ]);
    }

    public function test_results_show_zero_votes_for_unvoted_options()
    {
        $user = User::factory()->create();
        $poll = Poll::create(['question' => 'Empty Poll', 'status' => 'active', 'created_by' => $user->id]);
        $option = PollOption::create(['poll_id' => $poll->id, 'option_text' => 'No One Cares']);

        $response = $this->actingAs($user)
                         ->getJson("/polls/{$poll->id}/results");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'total_votes' => 0,
                     'results' => [
                         ['option_text' => 'No One Cares', 'votes' => 0, 'percentage' => 0]
                     ]
                 ]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VotingTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_vote()
    {
        $user = User::factory()->create();
        $poll = Poll::create(['question' => 'Test Poll', 'status' => 'active', 'created_by' => $user->id]);
        $option = PollOption::create(['poll_id' => $poll->id, 'option_text' => 'Option A']);

        $response = $this->actingAs($user)
                         ->postJson("/polls/{$poll->id}/vote", [
                             'option_id' => $option->id
                         ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('votes', [
            'poll_id' => $poll->id,
            'option_id' => $option->id,
            'ip_address' => '127.0.0.1' // Default testing IP
        ]);
    }

    public function test_user_cannot_vote_twice_for_same_poll()
    {
        $user = User::factory()->create();
        $poll = Poll::create(['question' => 'Test Poll', 'status' => 'active', 'created_by' => $user->id]);
        $option = PollOption::create(['poll_id' => $poll->id, 'option_text' => 'Option A']);

        // First vote
        $this->actingAs($user)
             ->postJson("/polls/{$poll->id}/vote", ['option_id' => $option->id])
             ->assertStatus(200);

        // Second vote (same IP effectively in test env unless mocked)
        $response = $this->actingAs($user)
                         ->postJson("/polls/{$poll->id}/vote", ['option_id' => $option->id]);

        $response->assertStatus(400)
                 ->assertJson(['success' => false]);
    }

    public function test_cannot_vote_for_invalid_option()
    {
        $user = User::factory()->create();
        $poll = Poll::create(['question' => 'Test Poll', 'status' => 'active', 'created_by' => $user->id]);

        $response = $this->actingAs($user)
                         ->postJson("/polls/{$poll->id}/vote", [
                             'option_id' => 9999 // Invalid ID
                         ]);

        $response->assertStatus(422); // Validation error
    }
}

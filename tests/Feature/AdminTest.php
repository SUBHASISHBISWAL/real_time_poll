<?php

namespace Tests\Feature;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_dashboard()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertRedirect('/polls');
    }

    public function test_admin_can_access_dashboard()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200)
                 ->assertSee('Admin Dashboard');
    }

    public function test_admin_can_release_vote()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $poll = Poll::create(['question' => 'Flavor Poll', 'status' => 'active', 'created_by' => $admin->id]);
        $option = PollOption::create(['poll_id' => $poll->id, 'option_text' => 'Vanilla']);
        
        // Create a vote
        Vote::create([
            'poll_id' => $poll->id, 
            'option_id' => $option->id, 
            'ip_address' => '1.2.3.4', 
            'voted_at' => now()
        ]);

        $this->assertDatabaseHas('votes', ['ip_address' => '1.2.3.4']);

        $response = $this->actingAs($admin)->post("/admin/polls/{$poll->id}/release/1.2.3.4");

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('votes', ['ip_address' => '1.2.3.4']);
        
        // Verify history record for release
        $this->assertDatabaseHas('vote_history', [
            'ip_address' => '1.2.3.4',
            'action' => 'released'
        ]);
    }
}

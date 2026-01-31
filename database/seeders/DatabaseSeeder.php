<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Poll;
use App\Models\PollOption;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@poll.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        // Create regular user
        $user = User::updateOrCreate(
            ['email' => 'user@poll.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );

        // Create sample polls
        $poll1 = Poll::create([
            'question' => 'What is your favorite programming language?',
            'status' => 'active',
            'created_by' => $admin->id,
        ]);

        PollOption::create(['poll_id' => $poll1->id, 'option_text' => 'PHP']);
        PollOption::create(['poll_id' => $poll1->id, 'option_text' => 'JavaScript']);
        PollOption::create(['poll_id' => $poll1->id, 'option_text' => 'Python']);
        PollOption::create(['poll_id' => $poll1->id, 'option_text' => 'Java']);

        $poll2 = Poll::create([
            'question' => 'Which framework do you prefer for web development?',
            'status' => 'active',
            'created_by' => $user->id,
        ]);

        PollOption::create(['poll_id' => $poll2->id, 'option_text' => 'Laravel']);
        PollOption::create(['poll_id' => $poll2->id, 'option_text' => 'React']);
        PollOption::create(['poll_id' => $poll2->id, 'option_text' => 'Vue.js']);
        PollOption::create(['poll_id' => $poll2->id, 'option_text' => 'Angular']);

        $poll3 = Poll::create([
            'question' => 'How many hours do you code per day?',
            'status' => 'active',
            'created_by' => $admin->id,
        ]);

        PollOption::create(['poll_id' => $poll3->id, 'option_text' => 'Less than 2 hours']);
        PollOption::create(['poll_id' => $poll3->id, 'option_text' => '2-4 hours']);
        PollOption::create(['poll_id' => $poll3->id, 'option_text' => '4-6 hours']);
        PollOption::create(['poll_id' => $poll3->id, 'option_text' => 'More than 6 hours']);
    }
}

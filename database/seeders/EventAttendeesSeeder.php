<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Attendence;
use App\Models\User;
use Illuminate\Support\Arr;

class EventAttendeesSeeder extends Seeder
{
    public function run()
    {
        // Available statuses
        $statuses =  ['Pending', 'Confirmed', 'Attended'];

        // Get the first event and a few users to use as attendees
        $event = Event::first(); // Assuming you have at least one event
        $users = User::take(5)->get(); // Get 5 users

        // Loop through each user and create attendance records with a random status
        foreach ($users as $user) {
            Attendence::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'status' => Arr::random($statuses), // Randomize the status
                // Add additional fields if necessary
            ]);
        }
    }
}

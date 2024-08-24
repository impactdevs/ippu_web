<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cpd;
use App\Models\Attendence;
use App\Models\User;
use Illuminate\Support\Arr;

class CpdAttendeesSeeder extends Seeder
{
    public function run()
    {
        // Available statuses
        $statuses = Attendence::$status; // ['Pending', 'Confirmed', 'Attended']

        // Get the first CPD and a few users to use as attendees
        $cpd = Cpd::first(); // Assuming you have at least one CPD
        $users = User::take(5)->get(); // Get 5 users

        // Loop through each user and create attendance records with a random status
        foreach ($users as $user) {
            Attendence::create([
                'cpd_id' => $cpd->id,
                'user_id' => $user->id,
                'status' => Arr::random($statuses), // Randomize the status
                // Add additional fields if necessary
            ]);
        }
    }
}

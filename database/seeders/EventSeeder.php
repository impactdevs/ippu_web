<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define the data to be inserted
        $events = [
            [
                'name' => 'Event 1',
                'start_date' => '2024-05-20',
                'end_date' => '2024-05-22',
                'rate' => '50',
                'member_rate' => '40',
                'attachment_name' => 'Attachment 1',
                'banner_name' => 'Banner 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more event data as needed
        ];

        // Insert data into the events table
        DB::table('events')->insert($events);
    }
}

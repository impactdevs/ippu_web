<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CpdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define the data to be inserted
        $cpds = [
            [
                'code' => 'CPD001',
                'topic' => 'Topic 1',
                'content' => 'Content for Topic 1',
                'hours' => '3',
                'target_group' => 'Target group for Topic 1',
                'location' => 'Location 1',
                'start_date' => '2024-05-19',
                'end_date' => '2024-05-21',
                'normal_rate' => '100',
                'members_rate' => '80',
                'resource' => 'Resource 1',
                'status' => 'Active',
                'type' => 'Paid',
                'points' => '3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more CPD data as needed
        ];

        // Insert data into the cpds table
        DB::table('cpds')->insert($cpds);
    }
}

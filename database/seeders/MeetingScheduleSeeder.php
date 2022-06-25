<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MeetingScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schedules = [
            [
                "from" => "9:00",
                "to" => "10:00"
            ]
        ];
    }
}

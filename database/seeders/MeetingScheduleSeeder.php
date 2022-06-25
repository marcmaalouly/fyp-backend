<?php

namespace Database\Seeders;

use App\Models\MeetingSchedule;
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
            ],
            [
                "from" => "10:00",
                "to" => "11:00"
            ],
            [
                "from" => "12:00",
                "to" => "13:00"
            ],
            [
                "from" => "13:00",
                "to" => "14:00"
            ],
            [
                "from" => "14:00",
                "to" => "15:00"
            ],
            [
                "from" => "16:00",
                "to" => "17:00"
            ],
            [
                "from" => "17:00",
                "to" => "18:00"
            ]
        ];

        foreach ($schedules as $schedule) {
            MeetingSchedule::create($schedule);
        }
    }
}

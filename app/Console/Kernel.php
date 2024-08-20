<?php

namespace App\Console;

use App\Notifications\BirthdayWish;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $today = Carbon::now();
            $users = User::whereMonth('dob', $today->month)
                          ->whereDay('dob', $today->day)
                          ->get();

            Notification::send($users, new BirthdayWish());

        })->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

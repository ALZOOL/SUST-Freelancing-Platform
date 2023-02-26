<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Student;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
    // protected function schedule(Schedule $schedule)
    // {
    //     $schedule->call(function () {
    //         $threshold = now()->subHours(12);
    //         $users = Student::where('last_login_at', '<=', $threshold)
    //                      ->whereNotNull('Authorization')
    //                      ->update(['Authorization' => null]);
    //     })->twiceDaily();
    // }


    // protected function schedule(Schedule $schedule)
    // {
    //     $schedule->call(function () {
    //         $threshold = now()->subMinutes(1);
    //         $users = Student::where('last_login_at', '<=', $threshold)
    //                      ->whereNotNull('Authorization')
    //                      ->update(['Authorization' => null]);
    //     })->everyFiveMinutes();
    // }
}

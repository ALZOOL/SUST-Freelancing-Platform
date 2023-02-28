<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Student;
use App\Models\Manager;
use App\Models\Client;



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
    //         $threshold = now()->subHours(8);
    //         $student = Student::where('last_login_at', '<=', $threshold)
    //                      ->whereNotNull('Authorization')
    //                      ->update(['Authorization' => null]);
    //         $manager = Manager::where('last_login_at', '<=', $threshold)
    //                      ->whereNotNull('Authorization')
    //                      ->update(['Authorization' => null]);
    //         $client = Client::where('last_login_at', '<=', $threshold)
    //                      ->whereNotNull('Authorization')
    //                      ->update(['Authorization' => null]);
    //     })->everyEightHours();
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

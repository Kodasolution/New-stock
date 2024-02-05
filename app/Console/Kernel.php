<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\VersementSalaireCommand::class,
        Commands\BackCommand::class,
    ];

    /**
     * Define the application's command schedule.
     */

    protected function schedule(Schedule $schedule): void
    {
        $outputOnFailure = ["migos.fugo@gmail.com"];
        // $schedule->command('verserment:salaire')->monthlyOn(14,"21:49")->name('versement')->withoutOverlapping()->runInBackground()->emailOutputOnFailure($outputOnFailure);   
        $schedule->command('verserment:salaire')->monthlyOn(15,"09:55")->name('versement')->withoutOverlapping()->runInBackground()->emailOutputOnFailure($outputOnFailure);   
        $schedule->command('backup:clean')->dailyAt("09:55")->withoutOverlapping()->runInBackground()->emailOutputOnFailure($outputOnFailure);
        $schedule->command('backup:run')->dailyAt("10:00")->withoutOverlapping()->runInBackground()->emailOutputOnFailure($outputOnFailure);
        $schedule->command('db:backup')->dailyAt("10:05")->withoutOverlapping()->runInBackground()->emailOutputOnFailure($outputOnFailure);
        $schedule->command('backup:clean')->dailyAt("21:55")->withoutOverlapping()->runInBackground()->emailOutputOnFailure($outputOnFailure);
        $schedule->command('backup:run')->dailyAt("22:00")->withoutOverlapping()->runInBackground()->emailOutputOnFailure($outputOnFailure);
        $schedule->command('db:backup')->dailyAt("22:05")->withoutOverlapping()->runInBackground()->emailOutputOnFailure($outputOnFailure);

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

<?php

namespace App\Console;

use App\Exports\CategoryExport;
use App\Exports\QuestionExport;
use App\Exports\SentenceExport;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function() {
            (new QuestionExport())->queue('question_' . Carbon::today()->toDateString(). '.xlsx');
            (new CategoryExport())->queue('category_' . Carbon::today()->toDateString(). '.xlsx');
            (new SentenceExport())->queue('sentence_' . Carbon::today()->toDateString(). '.xlsx');
        })->everyMinute();
    }

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
}

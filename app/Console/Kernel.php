<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\DiarioController;
use Illuminate\Support\Facades\Log;

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
        // $schedule->command('inspire')->hourly();

        // $schedule->call(function () {
        //     $teleCrontrol = new DiarioController();
        //     $teleCrontrol->enviarPesquisa();
        //     Log::info("rodou a cron receber dados");
        // })->everyMinute();

        $schedule->call(function () {
            Log::info("rodou a cron!!");
        })->everyMinute();

        $schedule->call(function () {
            $teleCrontrol = new DiarioController();
            $teleCrontrol->enviarPesquisa();
            Log::info("rodou a cron: enviar pesquisa");
        })->dailyAt('23:00');

        $schedule->call(function () {
            $teleCrontrol = new DiarioController();
            $teleCrontrol->receberDados();
            Log::info("rodou a cron receber dados");
        })->dailyAt('23:30');

        $schedule->call(function () {
            $teleCrontrol = new DiarioController();
            $teleCrontrol->receberDados();
            Log::info("rodou a cron receber dados");
        })->dailyAt('11:30');
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

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\DiarioController;

class EnviarPerguntas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enviarperguntas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este Comando serve para enviar as perguntas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info("chamou o comando EnviarPergunta");
        $teleCrontrol = new DiarioController();
        $teleCrontrol->enviarPesquisa();
        Log::info("rodou a cron: enviar pesquisa");
    }
}

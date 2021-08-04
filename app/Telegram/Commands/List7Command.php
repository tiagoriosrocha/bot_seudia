<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;
use App\Models\Diario;
use Carbon\Carbon;
use App\Http\Controllers\DiarioController;

/**
 * Class HelpCommand.
 */
class List7Command extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'list7';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['listar7'];

    /**
     * @var string Command Description
     */
    protected $description = 'Comando list7, exibe as respostas dos últimos 7 dias.';

    /**
     * {@inheritdoc}
     */
    public function handle()
    
    {
        $response = $this->getUpdate();
        $user_id = $response['message']['chat']['id'];
        $diario = new DiarioController;
        $text = $diario->geraRelatorio($user_id,7);
        $this->replyWithMessage(compact('text'));
    }
}
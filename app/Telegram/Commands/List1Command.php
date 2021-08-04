<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;
use App\Http\Controllers\DiarioController;

/**
 * Class HelpCommand.
 */
class List1Command extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'list1';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['listar1'];

    /**
     * @var string Command Description
     */
    protected $description = 'Comando list1, exibirá o último resultado cadastrado.';

    /**
     * {@inheritdoc}
     */
    public function handle()
    
    {
        $response = $this->getUpdate();
        $user_id = $response['message']['chat']['id'];
        $diario = new DiarioController;
        $diario->geraRelatorio($user_id,1);
        //$this->replyWithMessage(compact('text'));
    }
}
<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;

/**
 * Class HelpCommand.
 */
class List30Command extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'list30';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['listar30'];

    /**
     * @var string Command Description
     */
    protected $description = 'Comando list30, exibirá os resultados dos últimos 30 dias.';

    /**
     * {@inheritdoc}
     */
    public function handle()
    
    {
        $response = $this->getUpdate();

        $text = "Comando ainda não implementado";
                
        $this->replyWithMessage(compact('text'));

    }
}
<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;

/**
 * Class HelpCommand.
 */
class List15Command extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'list15';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['listar15'];

    /**
     * @var string Command Description
     */
    protected $description = 'Comando list15, exibirá os resultados dos últimos 15 dias.';

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
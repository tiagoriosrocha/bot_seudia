<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;

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

        $text = "Comando ainda não implementado";
                
        $this->replyWithMessage(compact('text'));

    }
}
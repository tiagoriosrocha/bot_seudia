<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;

/**
 * Class HelpCommand.
 */
class DeleteCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'delete';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['deletar'];

    /**
     * @var string Command Description
     */
    protected $description = 'Comando Delete, este comando apagará todos seus dados e irá desregistrar do sistema para não receber mais perguntas.';

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
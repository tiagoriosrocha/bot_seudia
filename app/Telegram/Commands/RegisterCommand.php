<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;

/**
 * Class HelpCommand.
 */
class RegisterCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'register';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['registro'];

    /**
     * @var string Command Description
     */
    protected $description = 'Comando Register: este comando registra um novo cliente.';

    /**
     * {@inheritdoc}
     */
    public function handle()
    
    {
        $response = $this->getUpdate();
        
        $text = "Olá!".chr(10);
        $text.= "Você foi registrado com sucesso".chr(10).chr(10);
        
        
        $this->replyWithMessage(compact('text'));

    }
}
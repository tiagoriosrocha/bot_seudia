<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;

/**
 * Class HelpCommand.
 */
class HelpCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'help';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['listaDeComandos'];

    /**
     * @var string Command Description
     */
    protected $description = 'Comando Help, este comando explica o que este bot faz.';

    /**
     * {@inheritdoc}
     */
    public function handle()
    
    {
        $response = $this->getUpdate();
        
        //$text = 'Hey stranger, thanks for visiting me.'.chr(10).chr(10);
        //$text .= 'I am a bot and working for'.chr(10);
        //$text .= env('APP_URL').chr(10).chr(10);
        //$text .= 'Please come and visit me there.'.chr(10);

        $text = "Olá!".chr(10).chr(10);
        $text.= "Seja bem vindo ao Bot - DayNotes!".chr(10).chr(10);
        $text.= "Este bot tem a intenção de fazer o levantamento de como foi o seu dia a partir de algumas perguntas simples que você irá enumerar de 1 a 5.".chr(10).chr(10);
        $text.= "As perguntas serão enviadas no final da noite, às 22h".chr(10).chr(10);
        $text.= "Caso você tenha interesse de se inscrever, por favor, envie o comando /register".chr(10);
        
        $this->replyWithMessage(compact('text'));

    }
}
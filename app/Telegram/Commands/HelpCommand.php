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

        $text = "Olá!".chr(10).chr(10);
        $text.= "Seja bem vindo ao Bot - SeuDia!".chr(10).chr(10);
        $text.= "Este bot tem a intenção de fazer o levantamento de como foi o seu dia a partir de algumas perguntas simples que você irá enumerar de 1 a 5, ou 0 caso não se aplique.".chr(10).chr(10);
        $text.= "As perguntas serão enviadas no final da noite, entre as 21h e as 23h".chr(10).chr(10);
        $text.= "Estes são os comandos aceitos:".chr(10);
        $text.= "/help - receba a lista de comandos.".chr(10);
        $text.= "/register - use para se registrar e começar a receber as perguntas diárias.".chr(10);
        $text.= "/unregister - use para se desregistrar e não receber mais perguntas diárias.".chr(10);
        $text.= "/delete - use para desregistrar e deletar todos seus dados.".chr(10);
        $text.= "/list1 - receba a relação de respostas do último dia.".chr(10);
        $text.= "/list7 - receba a relação dos últimos 7 dias.".chr(10);
        $text.= "/list15 - receba a relação dos últimos 15 dias.".chr(10);
        $text.= "/list30 - receba a relação dos últimos 30 dias.".chr(10);
        $text.= "/questions - receba as perguntas diárias agora.".chr(10);

        
        $this->replyWithMessage(compact('text'));

    }
}
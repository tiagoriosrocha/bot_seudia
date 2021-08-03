<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;
use App\models\Cliente;

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
        $chat_id = $mensagem['message']['chat']['id'];

        // $cliente = Cliente::where('user_id',)
        //                     ->where('chat_id',$mensagem['chat_id'])
        //                     ->first();

        // if( !isset($diario) ){
        //     $diario = new Diario;
        //     $diario->chat_id = $mensagem['chat_id'];
        //     $diario->dia = $mensagem['pergunta_data'];
        // }

        
        $text = "Olá!" . $chat_id .chr(10);
        $text.= "Você foi registrado com sucesso".chr(10).chr(10);
        
        
        $this->replyWithMessage(compact('text'));

    }
}
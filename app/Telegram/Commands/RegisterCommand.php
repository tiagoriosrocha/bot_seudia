<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;
use App\Models\Cliente;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Http\Controllers\ClienteController;

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
        $user_id = $response['message']['chat']['id'];
        
        $clienteController = new ClienteController;
        $result = $clienteController->cadastrar($user_id);
        
        if($result == 1){
            $text = "Olá!".chr(10);
            $text.= "Você foi registrado com sucesso".chr(10).chr(10);     
         }elseif($result==2){
            $text = "Olá!".chr(10);
            $text.= "Seu cadastro foi ativado novamente".chr(10).chr(10);     
         }

        $this->replyWithMessage(compact('text'));

    }
}
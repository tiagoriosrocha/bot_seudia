<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;
use App\Http\Controllers\ClienteController;

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
        $user_id = $response['message']['chat']['id'];
        
        $clienteController = new ClienteController;
        $result = $clienteController->deletar($user_id);
        
        if($result == 1){
            $text = "Olá!".chr(10);
            $text.= "Você foi desregistrado e seus dados foram completamente deletados".chr(10).chr(10);     
         }elseif($result==0){
            $text = "Olá!".chr(10);
            $text.= "Houve um problema ao deletar".chr(10).chr(10);     
         }
                
        $this->replyWithMessage(compact('text'));

    }
}
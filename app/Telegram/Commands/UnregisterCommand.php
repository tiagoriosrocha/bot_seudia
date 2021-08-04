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
class UnregisterCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'unregister';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['desregistro'];

    /**
     * @var string Command Description
     */
    protected $description = 'Comando Rnregister: este comando descadastra um cliente.';

    /**
     * {@inheritdoc}
     */
    public function handle()
    
    {
        $response = $this->getUpdate();
        $user_id = $response['message']['chat']['id'];
        
        $clienteController = new ClienteController;
        $result = $clienteController->descadastrar($user_id);
        
        if($result == 1){
            $text = "Olá!".chr(10);
            $text.= "Você foi desregistrado com sucesso".chr(10).chr(10);     
         }elseif($result==0){
            $text = "Olá!".chr(10);
            $text.= "Houve um problema ao desregistrar".chr(10).chr(10);     
         }

        $this->replyWithMessage(compact('text'));

    }
}
<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;
use App\Models\Cliente;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
        
        Log::info($response);

        $cliente = Cliente::where('user_id',$user_id)->first();

        if( !isset($cliente) ){
             $cliente = new Cliente;
             $cliente->user_id = $user_id;
             $cliente->dataCadastro = Carbon::now();
             $cliente->isActive = false;

             $cliente->save();

             $text = "Olá!".chr(10);
             $text.= "Você foi descadastrado com sucesso".chr(10).chr(10);     
         }else{
            $cliente->isActive = false;
            $cliente->save();

            $text = "Olá!".chr(10);
            $text.= "Você foi descadastrado com sucesso".chr(10).chr(10);     
         }

        $this->replyWithMessage(compact('text'));

    }
}
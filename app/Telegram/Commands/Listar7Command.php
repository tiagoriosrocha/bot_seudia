<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;
use App\Models\Diario;
use Carbon\Carbon;

/**
 * Class HelpCommand.
 */
class Listar7Command extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'listar7';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['listarsete'];

    /**
     * @var string Command Description
     */
    protected $description = 'Comando listar7, exibe as respostas dos últimos 7 dias.';

    /**
     * {@inheritdoc}
     */
    public function handle()
    
    {
        $response = $this->getUpdate();
        $user_id = $response['message']['chat']['id'];

        $listaDiario = Diario::where('chat_id',$user_id)->take(7)->orderBy('dia','desc')->get();

        $text = "Olá!".chr(10).chr(10);
        $text.= "Serão exibidos os resultados dos últimos 7 dias:".chr(10);
        
        foreach($listaDiario as $diario){
            $text.= "***********".chr(10);
            $text.= "Dia: " . Carbon::createFromFormat('Y-m-d', $diario->dia)->format('d/m/Y') .chr(10);    
            $text.= "\U00002b50 Alimentação: " . $diario->alimentacao .chr(10);
            $text.= "\U00002b50 Sono: " . $diario->sono .chr(10);
            $text.= "\U00002b50 Filhos: " . $diario->filhos .chr(10);
            $text.= "\U00002b50 Casal: " . $diario->casal .chr(10);
            $text.= "\U00002b50 Trabalho: " . $diario->trabalho .chr(10);
            $text.= "\U00002b50 Estudos: " . $diario->estudo .chr(10);
        }
        
        
        $this->replyWithMessage(compact('text'));

    }
}
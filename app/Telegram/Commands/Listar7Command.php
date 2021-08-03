<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;
use App\Models\Diario;

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
            $text.= "***********".ch(10);
            $text.= "Dia: " . Carbon::parse($diario->dia)->format('d/m/Y') .ch(10);    
            $text.= "Alimentação: " . $diario->alimentacao .ch(10);
            $text.= "Sono: " . $diario->sono .ch(10);
            $text.= "Filhos: " . $diario->filhos .ch(10);
            $text.= "Casal: " . $diario->casal .ch(10);
            $text.= "Trabalho: " . $diario->trabalho .ch(10);
            $text.= "Estudos: " . $diario->estudos .ch(10);
        }
        
        
        $this->replyWithMessage(compact('text'));

    }
}
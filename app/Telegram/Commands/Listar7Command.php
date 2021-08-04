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

        $star = "\xE2\xAD\x90";

        $text = "Olá!".chr(10).chr(10);
        $text.= "Serão exibidos os resultados dos últimos 7 dias:".chr(10);
        
        foreach($listaDiario as $diario){
            $text.= "***********".chr(10);
            $text.= "Dia: " . Carbon::createFromFormat('Y-m-d', $diario->dia)->format('d/m/Y') .chr(10);    
            
            $text.= "Alimentação: " . $diario->alimentacao . "   ";
            for($i=0;$i<$diario->alimentacao;$i++){
              $text.= json_decode('"'.$star.'"') . "";  
            } 
            $text.= "".chr(10);

            $text.= "Sono: " . $diario->sono . "          ";
            for($i=0;$i<$diario->sono;$i++){
              $text.= json_decode('"'.$star.'"') . "";  
            } 
            $text.= "".chr(10);

            $text.= "Filhos: " . $diario->filhos . "     ";
            for($i=0;$i<$diario->filhos;$i++){
              $text.= json_decode('"'.$star.'"') . "";  
            } 
            $text.= "".chr(10);

            $text.= "Casal: " . $diario->casal . "          ";
            for($i=0;$i<$diario->casal;$i++){
              $text.= json_decode('"'.$star.'"') . "";  
            } 
            $text.= "".chr(10);

            $text.= "Trabalho: " . $diario->trabalho . "      ";
            for($i=0;$i<$diario->trabalho;$i++){
              $text.= json_decode('"'.$star.'"') . "";  
            } 
            $text.= "".chr(10);

            $text.= "Estudos: " . $diario->estudo . "       ";
            for($i=0;$i<$diario->estudo;$i++){
              $text.= json_decode('"'.$star.'"') . "";  
            } 
            $text.= "".chr(10);
        }
        
        
        $this->replyWithMessage(compact('text'));

    }
}
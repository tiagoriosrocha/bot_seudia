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
        $calendar = "\xF0\x9F\x93\x86";
        $food = "\xF0\x9F\x8D\xB4";
        $sleep = "\xF0\x9F\x92\xA4";
        $love = "\xF0\x9F\x92\x98";
        $kids = "\xF0\x9F\x91\xAB";
        $work = "\xF0\x9F\x8F\xA2";
        $study = "\xE2\x9C\x8F";

        $text = "Olá!".chr(10).chr(10);
        $text.= "Serão exibidos os resultados dos últimos 7 dias:".chr(10);
        
        foreach($listaDiario as $diario){
            $text.= " ".chr(10);
            $text.= json_decode('"'.$calendar.'"') . "Dia: " . Carbon::createFromFormat('Y-m-d', $diario->dia)->format('d/m/Y') .chr(10);    
            
            $text.= "Alimentação: " . $diario->alimentacao . "   ";
            for($i=0;$i<$diario->alimentacao;$i++){
              $text.= json_decode('"'.$food.'"') . "";  
            } 
            $text.= "".chr(10);

            $text.= "Sono: " . $diario->sono . "                ";
            for($i=0;$i<$diario->sono;$i++){
              $text.= json_decode('"'.$sleep.'"') . "";  
            } 
            $text.= "".chr(10);

            $text.= "Filhos: " . $diario->filhos . "               ";
            for($i=0;$i<$diario->filhos;$i++){
              $text.= json_decode('"'.$kids.'"') . "";  
            } 
            $text.= "".chr(10);

            $text.= "Casal: " . $diario->casal . "               ";
            for($i=0;$i<$diario->casal;$i++){
              $text.= json_decode('"'.$love.'"') . "";  
            } 
            $text.= "".chr(10);

            $text.= "Trabalho: " . $diario->trabalho . "          ";
            for($i=0;$i<$diario->trabalho;$i++){
              $text.= json_decode('"'.$work.'"') . "";  
            } 
            $text.= "".chr(10);

            $text.= "Estudos: " . $diario->estudo . "           ";
            for($i=0;$i<$diario->estudo;$i++){
              $text.= json_decode('"'.$study.'"') . "";  
            } 
            $text.= "".chr(10);
        }
        
        
        $this->replyWithMessage(compact('text'));

    }
}
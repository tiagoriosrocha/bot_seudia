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
    protected $name = 'list7';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['listar7'];

    /**
     * @var string Command Description
     */
    protected $description = 'Comando list7, exibe as respostas dos últimos 7 dias.';

    /**
     * {@inheritdoc}
     */
    public function handle()
    
    {
        $response = $this->getUpdate();
        $user_id = $response['message']['chat']['id'];

        $listaDiario = Diario::where('chat_id',$user_id)->take(7)->orderBy('dia','desc')->get();

        //emotions -> https://apps.timwhitlock.info/emoji/tables/unicode
        $star = "\xE2\xAD\x90";
        $calendar = "\xF0\x9F\x93\x86";
        $food = "\xF0\x9F\x8D\xB4";
        $sleep = "\xF0\x9F\x92\xA4";
        $love = "\xF0\x9F\x92\x96";
        $kids = "\xF0\x9F\x91\xAB";
        $work = "\xF0\x9F\x8F\xA2";
        $study = "\xE2\x9C\x8F";

        $text = "Olá!".chr(10).chr(10);
        $text.= "Serão exibidos os resultados dos últimos 7 dias:".chr(10);
        
        foreach($listaDiario as $diario){
            $text.= " ".chr(10);
            $text.= json_decode('"'.$calendar.'"') . "    Dia: " . Carbon::createFromFormat('Y-m-d', $diario->dia)->format('d/m/Y') .chr(10);    
            
            $text.= json_decode('"'.$food.'"') . "    Alimentação: " . $diario->alimentacao . "   ";
            for($i=0;$i<$diario->alimentacao;$i++){
              $text.= json_decode('"'.$star.'"') . "";  
            } 
            $text.= "".chr(10);

            $text.= json_decode('"'.$sleep.'"') . "    Sono: " . $diario->sono . "                ";
            for($i=0;$i<$diario->sono;$i++){
              $text.= json_decode('"'.$star.'"') . "";  
            } 
            $text.= "".chr(10);

            $text.= json_decode('"'.$kids.'"') . "    Filhos: " . $diario->filhos . "               ";
            for($i=0;$i<$diario->filhos;$i++){
              $text.= json_decode('"'.$star.'"') . "";  
            } 
            $text.= "".chr(10);

            $text.= json_decode('"'.$love.'"') . "    Casal: " . $diario->casal . "               ";
            for($i=0;$i<$diario->casal;$i++){
              $text.= json_decode('"'.$star.'"') . "";  
            } 
            $text.= "".chr(10);

            $text.= json_decode('"'.$work.'"') . "    Trabalho: " . $diario->trabalho . "          ";
            for($i=0;$i<$diario->trabalho;$i++){
              $text.= json_decode('"'.$star.'"') . "";  
            } 
            $text.= "".chr(10);

            $text.= json_decode('"'.$study.'"') . "    Estudos: " . $diario->estudo . "           ";
            for($i=0;$i<$diario->estudo;$i++){
              $text.= json_decode('"'.$star.'"') . "";  
            } 
            $text.= "".chr(10);
        }
        
        
        $this->replyWithMessage(compact('text'));

    }
}
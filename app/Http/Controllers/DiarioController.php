<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\TelegramBotController;
use Carbon\Carbon;
use App\Models\Diario;
use App\Models\Cliente;
use Illuminate\Support\Facades\Log;

class DiarioController extends Controller
{
    
    const perguntas = array(
        "Como foi o seu dia em relação a sua alimentação?",
        "Como foi o seu sono?",
        "Como foi a relação sua com seus filhos?",
        "Como foi o seu relacionamento amoroso?",
        "Como foi a sua produtividade no trabalho?",
        "Como foi o seu dia em relação aos estudos/capacitação?"
    );
        

    public function enviarPesquisa(){
        $telegram = new TelegramBotController();
        
        $listaClientes = Cliente::where('isActive',1)->get();

        foreach($listaClientes as $cliente){
            Log::info("Enviando perguntas para o cliente " . $cliente->user_id);
            
            $text  = "Boa noite!" . chr(10);
            $text .= "Como foi o seu dia " . Carbon::now()->format("d/m") . "?" . chr(10);
            $text .= "Responda as questões abaixo." . chr(10);
            $text .= "0 - Não se aplica" . chr(10);
            $text .= "1 - Péssimo" . chr(10);
            $text .= "2 - Ruim" . chr(10);
            $text .= "3 - Normal/médio" . chr(10);
            $text .= "4 - Muito bom" . chr(10);
            $text .= "5 - Ótimo" . chr(10) . chr(10);

            $telegram->enviarMensagem($text,$cliente->user_id);
            for($i=0; $i<count(DiarioController::perguntas); $i++){
                $telegram->enviarMensagem(DiarioController::perguntas[$i],$cliente->user_id);    
            }
        }

        return "Pesquisa Enviada";
    }

    public function enviarPesquisaAgora($user_id){
        Log::info("Enviando perguntas para o cliente " . $user_id);
            
        $text  = "Boa noite!" . chr(10);
        $text .= "Como foi o seu dia " . Carbon::now()->format("d/m") . "?" . chr(10);
        $text .= "Responda as questões abaixo." . chr(10);
        $text .= "0 - Não se aplica" . chr(10);
        $text .= "1 - Péssimo" . chr(10);
        $text .= "2 - Ruim" . chr(10);
        $text .= "3 - Normal/médio" . chr(10);
        $text .= "4 - Muito bom" . chr(10);
        $text .= "5 - Ótimo" . chr(10) . chr(10);

        $telegram->enviarMensagem($text,$user_id);
        for($i=0; $i<count(DiarioController::perguntas); $i++){
            $telegram->enviarMensagem(DiarioController::perguntas[$i],$user_id);    
        }
    }


    public function receberDados(){
        $telegram = new TelegramBotController();
        $response = $telegram->updatedActivity();

        //dd($response);
        
        $listaMensagens = [];
        foreach($response as $mensagem){
            if(isset($mensagem['message']['reply_to_message'])){
                $pergunta = $mensagem['message']['reply_to_message']['text'];

                if( in_array($pergunta, DiarioController::perguntas) ){
                    $objMensagem['chat_id'] = $mensagem['message']['chat']['id'];
                    $objMensagem['pergunta'] = array_search($pergunta,DiarioController::perguntas);
                    $objMensagem['pergunta_data'] = Carbon::createFromTimestamp($mensagem['message']['reply_to_message']['date'])->format('Y-m-d');
                    $objMensagem['resposta'] = $mensagem['message']['text'];
                    $objMensagem['resposta_data'] = Carbon::createFromTimestamp($mensagem['message']['date'])->format('Y-m-d');
                    
                    $listaMensagens[] = $objMensagem;
                }
            }
        }

        //dd($listaMensagens);

        $this->armazenaOuAtualiza($listaMensagens);
    }

    /*
    * Retorna  1 se deu certo
    * Retorna  0 se não deu pra cadastrar
    * Retorna -1 se o texto não for no padrão (1-5)
    */
    public function processarMensagem($mensagem){
        
        if(isset($mensagem['message']['reply_to_message'])){
            $pergunta = $mensagem['message']['reply_to_message']['text'];
            $resposta = $mensagem['message']['text'];

            if($resposta >= 0 && $resposta <= 5){
               if( in_array($pergunta, DiarioController::perguntas) ){
                    $objMensagem['chat_id'] = $mensagem['message']['chat']['id'];
                    $objMensagem['pergunta'] = array_search($pergunta,DiarioController::perguntas);
                    $objMensagem['pergunta_data'] = Carbon::createFromTimestamp($mensagem['message']['reply_to_message']['date'])->format('Y-m-d');
                    $objMensagem['resposta'] = $mensagem['message']['text'];
                    $objMensagem['resposta_data'] = Carbon::createFromTimestamp($mensagem['message']['date'])->format('Y-m-d');
                    $this->armazenaOuAtualizaMensagem($objMensagem);
                    return 1;
                }else{
                    return 0;
                }
            }else{
                return -1;
            }
            
        }
    }

    public function armazenaOuAtualizaMensagem($mensagem){
        $diario = Diario::where('dia',$mensagem['pergunta_data'])
                            ->where('chat_id',$mensagem['chat_id'])
                            ->first();

            if( !isset($diario) ){
                $diario = new Diario;
                $diario->chat_id = $mensagem['chat_id'];
                $diario->dia = $mensagem['pergunta_data'];
            }

            switch($mensagem['pergunta']){
                case 0:
                    $diario->alimentacao = $mensagem['resposta'];
                    break;
                case 1:
                    $diario->sono = $mensagem['resposta'];
                    break;
                case 2:
                    $diario->filhos = $mensagem['resposta'];
                    break;
                case 3:
                    $diario->casal = $mensagem['resposta'];
                    break;
                case 4:
                    $diario->trabalho = $mensagem['resposta'];
                    break;
                case 5:
                    $diario->estudo = $mensagem['resposta'];
                    break;
            }

            $diario->save();

            $this->verificaConclusaoDia($diario);
    }

    public function armazenaOuAtualiza($listaMensagens){
        foreach($listaMensagens as $mensagem){
            $diario = Diario::where('dia',$mensagem['pergunta_data'])
                            ->where('chat_id',$mensagem['chat_id'])
                            ->first();

            if( !isset($diario) ){
                $diario = new Diario;
                $diario->chat_id = $mensagem['chat_id'];
                $diario->dia = $mensagem['pergunta_data'];
            }

            switch($mensagem['pergunta']){
                case 0:
                    $diario->alimentacao = $mensagem['resposta'];
                    break;
                case 1:
                    $diario->sono = $mensagem['resposta'];
                    break;
                case 2:
                    $diario->filhos = $mensagem['resposta'];
                    break;
                case 3:
                    $diario->casal = $mensagem['resposta'];
                    break;
                case 4:
                    $diario->trabalho = $mensagem['resposta'];
                    break;
                case 5:
                    $diario->estudo = $mensagem['resposta'];
                    break;
            }

            $diario->save();

            $this->verificaConclusaoDia($diario);
        }
    }

    public function verificaConclusaoDia($diario){
        
        if( isset($diario->alimentacao)
            && isset($diario->sono)
            && isset($diario->filhos)
            && isset($diario->casal)
            && isset($diario->trabalho)
            && isset($diario->estudo)
            && $diario->confirmado == false){
            Log::info("entrou");
            $telegram = new TelegramBotController();
            $dia = Carbon::parse($diario->dia);
            
            $check = "\xE2\x9C\x85";
            $calendar = "\xF0\x9F\x93\x86";
            $food = "\xF0\x9F\x8D\xB4";
            $sleep = "\xF0\x9F\x92\xA4";
            $love = "\xF0\x9F\x92\x96";
            $kids = "\xF0\x9F\x91\xAB";
            $work = "\xF0\x9F\x8F\xA2";
            $study = "\xE2\x9C\x8F";

            $mensagemFeedback = json_decode('"'.$check.'"') . " Todos os registros do dia " . $dia->format('d/m/Y') . " foram cadastrados com sucesso!";
            $telegram->enviarMensagem($mensagemFeedback,$diario->chat_id);

            $mensagemFeedback = json_decode('"'.$food.'"') . " Alimentação: " . $diario->alimentacao . chr(10);
            $mensagemFeedback .= json_decode('"'.$sleep.'"') . " Sono: " . $diario->sono . chr(10);
            $mensagemFeedback .= json_decode('"'.$kids.'"') . " Filhos: " . $diario->filhos . chr(10);
            $mensagemFeedback .= json_decode('"'.$love.'"') . " Casal: " . $diario->casal . chr(10);
            $mensagemFeedback .= json_decode('"'.$work.'"') . " Trabalho: " . $diario->trabalho . chr(10);
            $mensagemFeedback .= json_decode('"'.$study.'"') . " Estudo: " . $diario->estudo . chr(10);

            $telegram->enviarMensagem($mensagemFeedback,$diario->chat_id);

            $diario->confirmado = true;
            $diario->save();
        }
    }

    public function geraRelatorio($user_id, $qtd){
        $listaDiario = Diario::where('chat_id',$user_id)->take($qtd)->orderBy('dia','desc')->get();

        //emotions -> https://apps.timwhitlock.info/emoji/tables/unicode
        $star = "\xE2\xAD\x90";
        $calendar = "\xF0\x9F\x93\x86";
        $food = "\xF0\x9F\x8D\xB4";
        $sleep = "\xF0\x9F\x92\xA4";
        $love = "\xF0\x9F\x92\x96";
        $kids = "\xF0\x9F\x91\xAB";
        $work = "\xF0\x9F\x8F\xA2";
        $study = "\xE2\x9C\x8F";
        $ampola = "\xE2\x8C\x9B";

        if($listaDiario->count() >= 1){
            $text = "Olá!".chr(10).chr(10);
            
            if($qtd == 1){
                $text.= "Será exibido último registro cadastrado:".chr(10);
            }else{
                $text.= "Serão exibidos os resultados dos últimos ". $qtd ." dias:".chr(10);    
            }
            
            
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
        }else{
            $text = json_decode('"'.$ampola.'"') . " Infelizmente você ainda não possui registros!";
        }

        return $text;
    }

}

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
        "Como foi o seu dia em relação a sua alimentação [1-5]?",
        "Como foi o seu sono [1-5]?",
        "Como foi a relação sua com seus filhos [1-5]?",
        "Como foi o seu relacionamento amoroso [1-5]?",
        "Como foi a sua produtividade no trabalho [1-5]?",
        "Como foi o seu dia em relação aos estudos/capacitação [1-5]?"
    );
        

    public function enviarPesquisa(){
        $telegram = new TelegramBotController();
        
        $listaClientes = Cliente::where('isActive',1)->get();

        foreach($listaClientes as $cliente){
            Log::info("Enviando perguntas para o cliente " . $cliente->user_id);
            for($i=0; $i<count(DiarioController::perguntas); $i++){
                $telegram->enviarMensagem(DiarioController::perguntas[$i],$cliente->user_id);    
            }
        }

        return "Pesquisa Enviada";
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


    public function processarMensagem($mensagem){
        
        if(isset($mensagem['message']['reply_to_message'])){
            $pergunta = $mensagem['message']['reply_to_message']['text'];

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
            
            $mensagemFeedback = "Todos os registros do dia " . $dia->format('d/m/Y') . " foram cadastrados com sucesso!";
            $telegram->enviarMensagem($mensagemFeedback,$diario->chat_id);

            $mensagemFeedback = "Alimentação: " . $diario->alimentacao 
                              . "; Sono: " . $diario->sono
                              . "; Filhos: " . $diario->filhos 
                              . "; Casal: " . $diario->casal 
                              . "; Trabalho: " . $diario->trabalho 
                              . "; Estudo: " . $diario->estudo;
            $telegram->enviarMensagem($mensagemFeedback,$diario->chat_id);

            $diario->confirmado = true;
            $diario->save();
        }
    }

}

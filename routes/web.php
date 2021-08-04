<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiarioController;
use App\Http\Controllers\TelegramBotController;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/receber_atualizacao', [TelegramBotController::class, 'updatedActivity']);
//Route::get('/enviar_mensagem/{mensagem}', [TelegramBotController::class, 'enviarMensagem']);
//Route::get('/testar_bot', [TelegramBotController::class, 'getMe']);
//Route::get('/enviarPesquisa', [DiarioController::class, 'enviarPesquisa']);
//Route::get('/receber', [DiarioController::class, 'receberDados']);




Route::post('/JWLDXyegFhXmYrCW74MHvEkvX3O0ZhVWJbqpLweBfPNmgPDHvt/webhook', function () {
        $mensagem = Telegram::commandsHandler(true); 
        $textomsg = $mensagem['message']['text'];
        
        if( !in_array($textomsg,TelegramBotController::comandos) ){

            $diario = new DiarioController;
            $resultado = $diario->processarMensagem($mensagem);
            
            if($resultado == 1){
                $check = "\xE2\x9C\x85";
                Telegram::sendMessage([
                            'chat_id' => $mensagem['message']['chat']['id'], 
                            'text' => json_decode('"'.$check.'"') . " Recebido!",
                            'parse_mode' => 'HTML',
                ]);
            }elseif($resultado == -1){
                $error = "\xE2\x9A\xA0";
                Telegram::sendMessage([
                            'chat_id' => $mensagem['message']['chat']['id'], 
                            'text' => json_decode('"'.$error.'"') . " Responda a mensagem com valores entre 0 e 5.",
                            'parse_mode' => 'HTML',
                ]);
            }elseif($resultado == 0){
                $question = "\xE2\x9D\x93";
                $text = json_decode('"'.$question.'"') . "Desculpe!" . chr(10) . chr(10);
                $text .= "Este comando não existe." . chr(10);
                $text .= "Apenas responda as perguntas ou use os comandos: ".chr(10);
                foreach(TelegramBotController::comandos as $c){
                    $text .= $c . chr(10);
                }

                Telegram::sendMessage([
                            'chat_id' => $mensagem['message']['chat']['id'], 
                            'text' => $text,
                            'parse_mode' => 'HTML',
                ]);
            }

        }
    }
);

Route::get('/setwebhook', function () {
    $response = Telegram::setWebhook(['url' => 'https://daynotes.herokuapp.com/JWLDXyegFhXmYrCW74MHvEkvX3O0ZhVWJbqpLweBfPNmgPDHvt/webhook']);
    dd($response);
});


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
        
        if(    $mensagem['message']['text'] != '/help' 
            && $mensagem['message']['text'] != '/register'
            && $mensagem['message']['text'] != '/unregister'
            && $mensagem['message']['text'] != '/start'
            && $mensagem['message']['text'] != '/list7'){

            $diario = new DiarioController;
            $resultado = $diario->processarMensagem($mensagem);
            
            if($resultado == 1){
                Telegram::sendMessage([
                            'chat_id' => $mensagem['message']['chat']['id'], 
                            'text' => "Recebido!",
                            'parse_mode' => 'HTML',
                ]);
            }elseif($resultado == -1){
                Telegram::sendMessage([
                            'chat_id' => $mensagem['message']['chat']['id'], 
                            'text' => "Responda a mensagem com valores entre 0 e 5",
                            'parse_mode' => 'HTML',
                ]);
            }elseif($resultado == 0){
                Telegram::sendMessage([
                            'chat_id' => $mensagem['message']['chat']['id'], 
                            'text' => "Desculpe, este comando não existe. Apenas responda as perguntas ou use os comandos: /help /register /unregister /list7",
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


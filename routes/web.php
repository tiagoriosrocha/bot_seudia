<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiarioController;
use App\Http\Controllers\TelegramBotController;

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

Route::get('/receber_atualizacao', [TelegramBotController::class, 'updatedActivity']);
Route::get('/enviar_mensagem/{mensagem}', [TelegramBotController::class, 'enviarMensagem']);
Route::get('/testar_bot', [TelegramBotController::class, 'getMe']);
Route::get('/enviarPesquisa', [DiarioController::class, 'enviarPesquisa']);
Route::get('/receber', [DiarioController::class, 'receberDados']);

Route::post('/JWLDXyegFhXmYrCW74MHvEkvX3O0ZhVWJbqpLweBfPNmgPDHvt/webhook', function () {
    $update = Telegram::commandsHandler(true); 
});

Route::get('/setwebhook', function () {
    $response = Telegram::setWebhook(['url' => 'https://daynotes.herokuapp.com/JWLDXyegFhXmYrCW74MHvEkvX3O0ZhVWJbqpLweBfPNmgPDHvt/webhook']);
    dd($response);
});


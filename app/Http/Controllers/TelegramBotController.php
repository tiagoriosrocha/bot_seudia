<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Api;
use Carbon\Carbon;

class TelegramBotController extends Controller
{
    public function updatedActivity()
    {
        $activity = Telegram::getUpdates();
        return $activity;
    }

    public function enviarMensagem($mensagem){

        if(!isset($mensagem)){
            $mensagem = "vazio";
        }

        Telegram::sendMessage([
            'chat_id' => '743350983',
            'parse_mode' => 'HTML',
            'text' => $mensagem
        ]);
    }

    public function getMe(){
        //$response = $telegram->getMe();
        $response = Telegram::getMe();

        $botId = $response->getId();
        $firstName = $response->getFirstName();
        $username = $response->getUsername();

        return $botId . " - " . $firstName;
    }

}

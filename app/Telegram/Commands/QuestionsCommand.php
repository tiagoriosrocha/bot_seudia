<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;
use App\Http\Controllers\DiarioController;

/**
 * Class HelpCommand.
 */
class QuestionsCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'questions';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['perguntas'];

    /**
     * @var string Command Description
     */
    protected $description = 'Comando Questions, envia as perguntas do dia agora.';

    /**
     * {@inheritdoc}
     */
    public function handle()
    
    {
        $response = $this->getUpdate();
        $user_id = $response['message']['chat']['id'];
        $diario = new DiarioController;
        $text = $diario->enviarPesquisaAgora($user_id);
    }
}
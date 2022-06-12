<?php

namespace App\Http\Controllers;

use App\BotMan\Middleware\RegisterChatMiddleware;
use BotMan\BotMan\BotMan;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        require base_path('routes/botman.php');
        /** @var BotMan $botman */
        $botman = app('botman');

        $botman->middleware->received(new RegisterChatMiddleware());
        $botman->listen();
    }
}

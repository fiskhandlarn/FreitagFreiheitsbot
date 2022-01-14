<?php

/**
 * Broadcast command
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class BroadcastCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'broadcast';

    /**
     * @var string
     */
    protected $description = 'Broadcast command';

    /**
     * @var string
     */
    protected $usage = '/broadcast';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * @var bool
     */
    protected $private_only = true;

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        // https://github.com/php-telegram-bot/core#send-message-to-all-active-chats
        $results = Request::sendToActiveChats(
            'sendMessage', // Callback function to execute (see Request.php methods)
            ['text' => 'Hey! Check out the new features!!'], // Param to evaluate the request
            [
                'groups'      => true,
                'supergroups' => true,
                'channels'    => true,
                'users'       => true,
            ]
        );
    }
}

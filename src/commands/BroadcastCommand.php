<?php

/**
 * Broadcast command
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\DB;
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
        date_default_timezone_set('Europe/Stockholm');

        $message = false;

        switch (intval(date('w'))) {
            case 4: // thursday
                $message = 'Morgen ist Freitag' . PHP_EOL .
                    'https://www.youtube.com/watch?v=qcYTzV4HCyk';
                break;
            case 5: // friday
                $message = 'Heute ist Freitag!' . PHP_EOL .
                    'https://www.youtube.com/watch?v=kfVsfOSbJY0';
                break;
            case 6: // saturday
            case 0: // sunday
                $message = 'Heute ist Wochenende!' . PHP_EOL .
                    'https://www.youtube.com/watch?v=cjgldht4PKw';
                break;
        }

        if ($message) {
            // https://github.com/php-telegram-bot/core#send-message-to-all-active-chats
            $results = Request::sendToActiveChats(
                'sendMessage', // Callback function to execute (see Request.php methods)
                ['text' => $message], // Param to evaluate the request
                [
                    'groups'      => true,
                    'supergroups' => true,
                    'channels'    => true,
                    'users'       => true,
                ]
            );
        }

        $chats = DB::selectChats([
            'groups'      => true,
            'supergroups' => true,
            'channels'    => true,
            'users'       => true,
        ]);

        if (is_array($chats)) {
            foreach ($chats as $row) {
                Request::sendAnimation([
                    'chat_id' => $row['chat_id'],
                    'caption' => 'Ja, saufen!',
                    'animation'   => 'https://c.tenor.com/8QtN1_MFXaIAAAAC/wochenende-saufen.gif',
                ]);
            }
        }
    }
}

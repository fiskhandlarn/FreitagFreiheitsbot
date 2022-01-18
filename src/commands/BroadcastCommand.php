<?php

/**
 * Broadcast command
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\DB;
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
        $result = new ServerResponse(['ok' => false]);

        date_default_timezone_set('Europe/Stockholm');

        $caption = false;
        $animation = false;

        switch (intval(date('w'))) {
            case 4: // thursday
                $caption = 'Morgen ist Freitag';
                $animation = 'https://c.tenor.com/H1kZm2ogXaUAAAAC/hallihallo-jodelo.gif';
                break;
            case 5: // friday
                $caption = 'Heute ist Freitag!';
                $animation = 'https://c.tenor.com/Kz-9sDk-zKMAAAAC/wochenende-hoch-die-h%C3%A4nde.gif';
                break;
            case 6: // saturday
            case 0: // sunday
                $caption = 'Heute ist Wochenende!';
                $animation = 'https://c.tenor.com/xMpoWNu-4VIAAAAM/weekend-finally-weekend.gif';
                break;
        }

        if ($caption && $animation) {
            $chats = DB::selectChats([
                'groups'      => true,
                'supergroups' => true,
                'channels'    => true,
                'users'       => true,
            ]);

            if (is_array($chats)) {
                foreach ($chats as $row) {
                    // https://github.com/php-telegram-bot/core/issues/568#issuecomment-316715045
                    if ($this->telegram->getBotId() != $chat_id) {
                        $result = Request::sendAnimation([
                            'chat_id' => $row['chat_id'],
                            'caption' => $caption,
                            'animation' => $animation,
                        ]);
                    }
                }
            }
        }

        return $result;
    }
}

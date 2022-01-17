<?php

/**
 * Wochenende command
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class WochenendeCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'wochenende';

    /**
     * @var string
     */
    protected $description = 'Wochenende command';

    /**
     * @var string
     */
    protected $usage = '/wochenende';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * @var bool
     */
    protected $private_only = false;

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        date_default_timezone_set('Europe/Stockholm');

        $weekday = intval(date('w'));
        $hour = intval(date('H'));

        if (
            $message = $this->getMessage() ?:
            $this->getEditedMessage() ?:
            $this->getChannelPost() ?:
            $this->getEditedChannelPost()
        ) {
            return Request::sendAnimation([
                'chat_id' => $message->getFrom()->getId(),
                'caption' => 'Ja, saufen!',
                'animation'   => Request::encodeFile(__DIR__ . '/../../images/wochenende.gif'),
            ]);
        } else {
            return $this->replyToChat(
                'Test!'
            );
        }

        if ((5 === $weekday && $hour >= 17) || 6 === $weekday || 0 === $weekday) {
            // return $this->replyToChat(
            //     'Ja, saufen!' . PHP_EOL .
            //     'https://www.youtube.com/watch?v=3aGf0t69_xk'
            // );
        } else {
            return $this->replyToChat(
                'Nein.'
            );
        }
    }
}

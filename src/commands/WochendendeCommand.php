<?php

/**
 * Wochenende command
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

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
    protected $private_only = true;

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        return $this->replyToChat(
            'Ja, saufen!' . PHP_EOL . PHP_EOL .
            'https://www.youtube.com/watch?v=3aGf0t69_xk'
        );
    }
}

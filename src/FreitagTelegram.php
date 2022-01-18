<?php

declare(strict_types=1);

namespace Fiskhandlarn\FreitagFreiheitsbot;

use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\TelegramLog;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class FreitagTelegram extends Telegram
{

    public function __construct(string $api_key, string $bot_username = '')
    {
        parent::__construct($api_key, $bot_username);

        $config = require __DIR__ . '/../src/config.php';

        // Add commands paths containing your custom commands
        $this->addCommandsPaths($config['commands']['paths']);

        // Enable MySQL if required
        $this->enableMySql($config['mysql']);

        // Logging (Error, Debug and Raw Updates)
        // https://github.com/php-telegram-bot/core/blob/master/doc/01-utils.md#logging
        //
        // (this example requires Monolog: composer require monolog/monolog)
        TelegramLog::initialize(
            new Logger('telegram_bot', [
                (
                    new StreamHandler(
                        $config['logging']['debug'],
                        Logger::DEBUG
                    )
                )
                    ->setFormatter(new LineFormatter(null, null, true)),
                (
                    new StreamHandler(
                        $config['logging']['error'],
                        Logger::ERROR
                    )
                )
                    ->setFormatter(new LineFormatter(null, null, true)),
            ]),
            new Logger('telegram_bot_updates', [
                (
                    new StreamHandler(
                        $config['logging']['update'],
                        Logger::INFO
                    )
                )
                    ->setFormatter(new LineFormatter('%message%' . PHP_EOL)),
            ])
        );

        // Requests Limiter (tries to prevent reaching Telegram API limits)
        $this->enableLimiter($config['limiter']);
    }
}

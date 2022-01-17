<?php

// Load composer
require_once __DIR__ . '/../vendor/autoload.php';

// Load all configuration options
/** @var array $config */
$config = require __DIR__ . '/../src/config.php';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($config['api_key'], $config['bot_username']);

    // Add commands paths containing your custom commands
    $telegram->addCommandsPaths($config['commands']['paths']);

    // Enable MySQL if required
    $telegram->enableMySql($config['mysql']);

    // Logging (Error, Debug and Raw Updates)
    // https://github.com/php-telegram-bot/core/blob/master/doc/01-utils.md#logging
    //
    // (this example requires Monolog: composer require monolog/monolog)
    Longman\TelegramBot\TelegramLog::initialize(
        new Monolog\Logger('telegram_bot', [
            (
                new Monolog\Handler\StreamHandler(
                    $config['logging']['debug'],
                    Monolog\Logger::DEBUG
                )
            )
                ->setFormatter(new Monolog\Formatter\LineFormatter(null, null, true)),
            (
                new Monolog\Handler\StreamHandler(
                    $config['logging']['error'],
                    Monolog\Logger::ERROR
                )
            )
                ->setFormatter(new Monolog\Formatter\LineFormatter(null, null, true)),
        ]),
        new Monolog\Logger('telegram_bot_updates', [
            (
                new Monolog\Handler\StreamHandler(
                    $config['logging']['update'],
                    Monolog\Logger::INFO
                )
            )
                ->setFormatter(new Monolog\Formatter\LineFormatter('%message%' . PHP_EOL)),
        ])
    );

    // Requests Limiter (tries to prevent reaching Telegram API limits)
    $telegram->enableLimiter($config['limiter']);

    // Handle telegram webhook request
    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Log telegram errors
    Longman\TelegramBot\TelegramLog::error($e);

    // Uncomment this to output any errors (ONLY FOR DEVELOPMENT!)
    // echo $e;
} catch (Longman\TelegramBot\Exception\TelegramLogException $e) {
    // Uncomment this to output log initialisation errors (ONLY FOR DEVELOPMENT!)
    // echo $e;
}

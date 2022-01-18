<?php

// Load composer
require_once __DIR__ . '/../vendor/autoload.php';

// Load all configuration options
/** @var array $config */
$config = require __DIR__ . '/../src/config.php';

use Fiskhandlarn\FreitagFreiheitsbot\FreitagTelegram;

try {
    // Create Telegram API object
    $telegram = new FreitagTelegram($config['api_key'], $config['bot_username']);

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

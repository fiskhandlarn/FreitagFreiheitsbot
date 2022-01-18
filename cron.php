<?php

/**
 * This file is used to run a list of commands with crontab.
 */

// Your command(s) to run, pass them just like in a message (arguments supported)
$commands = [
    '/broadcast',
];

// Load composer
require_once __DIR__ . '/vendor/autoload.php';

use Fiskhandlarn\FreitagFreiheitsbot\FreitagTelegram;

try {
    // Create Telegram API object
    $telegram = new FreitagTelegram();

    // Run user selected commands
    $result = $telegram->runCommands($commands);

    if (isset($result[0])) {
        echo $result[0]->toJson();
        Longman\TelegramBot\TelegramLog::info($result[0]->toJson());
    } else {
        echo 'failure' . "\n";
        Longman\TelegramBot\TelegramLog::error('cron failure');
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Log telegram errors
    Longman\TelegramBot\TelegramLog::error($e);

    // Uncomment this to output any errors (ONLY FOR DEVELOPMENT!)
    // echo $e;
} catch (Longman\TelegramBot\Exception\TelegramLogException $e) {
    // Uncomment this to output log initialisation errors (ONLY FOR DEVELOPMENT!)
    // echo $e;
}

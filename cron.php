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

// Load all configuration options
/** @var array $config */
$config = require __DIR__ . '/src/config.php';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($config['api_key'], $config['bot_username']);

    /**
     * Check `hook.php` for configuration code to be added here.
     */

    // Run user selected commands
    $result = $telegram->runCommands($commands);

    echo (isset($result[0]) ? $result[0]->toJson() : 'failure') . "\n";
    Longman\TelegramBot\TelegramLog::info($result);


    $results = Request::sendToActiveChats(
        'sendMessage', // Callback function to execute (see Request.php methods)
        ['text' => 'Test from cron'], // Param to evaluate the request
        [
            'groups'      => true,
            'supergroups' => true,
            'channels'    => true,
            'users'       => true,
        ]
    );

    Longman\TelegramBot\TelegramLog::info($results);
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Log telegram errors
    Longman\TelegramBot\TelegramLog::error($e);

    // Uncomment this to output any errors (ONLY FOR DEVELOPMENT!)
    // echo $e;
} catch (Longman\TelegramBot\Exception\TelegramLogException $e) {
    // Uncomment this to output log initialisation errors (ONLY FOR DEVELOPMENT!)
    // echo $e;
}

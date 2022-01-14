<?php

/**
 * This file is used to set the webhook.
 */

// Load composer
require_once __DIR__ . '/../vendor/autoload.php';

// Load all configuration options
/** @var array $config */
$config = require __DIR__ . '/../src/config.php';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($config['api_key'], $config['bot_username']);

    // Set the webhook
    $result = $telegram->setWebhook($config['webhook']['url']);

    // To use a self-signed certificate, use this line instead
    // $result = $telegram->setWebhook($config['webhook']['url'], ['certificate' => $config['webhook']['certificate']]);

    echo $result->getDescription();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Log telegram errors
    Longman\TelegramBot\TelegramLog::error($e);

    // echo $e->getMessage();
}

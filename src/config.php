<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

return [
    // Add you bot's API key and name
    'api_key'      => $_ENV['TELEGRAM_API_TOKEN'],
    'bot_username' => 'FreitagFreiheitsbot', // Without "@"

    // When using the getUpdates method, this can be commented out
    'webhook'      => [
        'url' => ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http") .
        '://' . $_SERVER['HTTP_HOST'] . '/hook.php',
        // Use self-signed certificate
        'certificate' => ('local' === $_ENV['ENV']) ? __DIR__ . '../.docker/ssl/server.key' : false,
    ],

    // All command related configs go here
    'commands'     => [
        // Define all paths for your custom commands
        'paths'   => [
            __DIR__ . '/commands',
        ],
    ],

    // Requests Limiter (tries to prevent reaching Telegram API limits)
    'limiter'      => [
        'enabled' => true,
    ],
];
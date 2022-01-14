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
        'url' => $_ENV['BASE_URL'] . '/hook.php',
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

    // Enter your MySQL database credentials
    'mysql'        => [
        'host'     => $_ENV['DB_HOST'],
        'user'     => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
        'database' => $_ENV['DB_NAME'],
    ],

    // Requests Limiter (tries to prevent reaching Telegram API limits)
    'limiter'      => [
        'enabled' => true,
    ],
];

<?php

declare(strict_types=1);

return [

    // Discord Bot Tokens can be retrieved from the Bot page of your Discord application.
    // See https://discord.com/developers/applications
    'token' => env('CAROLER_TOKEN'),

    // Character(s) Commands meant for your Discord bot must be prefixed with.
    // It is recommended to use one or more special characters that aren't already in use by another bot in your server.
    // Default: !
    'command_prefix' => '!',

    // Directories containing Command classes. The Commands in these directories will always be automatically loaded.
    'command_dirs' => [
        app_path('Caroler/Commands'),
    ],

    // Id of the channel used for system events.
    // Default: <empty>
    'system_channel' => '',

    // Whether or not debugging mode is enabled. Debugging mode will show additional info in the console.
    // Options: true|false
    // Default: false
    'debug' => false,
];

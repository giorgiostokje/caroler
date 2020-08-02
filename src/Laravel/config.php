<?php

declare(strict_types=1);

use App\Caroler\Commands\Dice;

return [

    // =========================================================================
    // Discord Bot Tokens can be retrieved from the Bot page of your Discord
    // application. See https://discord.com/developers/applications.
    // -------------------------------------------------------------------------
    // Accepts: string
    // =========================================================================
    'token' => env('CAROLER_TOKEN'),

    // =========================================================================
    // Id of the bot administrator. Used for restricting command usage. In
    // Discord, right click the admin user and select Copy ID.
    // -------------------------------------------------------------------------
    // Accepts: string
    // Default: ''
    // =========================================================================
    'admin_id' => env('CAROLER_ADMIN_ID', ''),

    // =========================================================================
    // Id of the channel to write system events to. In Discord, go to Settings >
    // Appearance and enable Developer Mode, then right click a channel and
    // select Copy ID.
    // -------------------------------------------------------------------------
    // Accepts: string
    // Default: ''
    // =========================================================================
    'system_channel' => env('CAROLER_SYSTEM_CHANNEL', ''),

    // =========================================================================
    // One or more characters used as prefix for your bot commands. It is
    // recommended to use one or more special characters that aren't already in
    // use by another bot in your server.
    // -------------------------------------------------------------------------
    // Accepts: string
    // Default: '!'
    // =========================================================================
    'command_prefix' => '!',

    // =========================================================================
    // Commands to register automatically when the application boots. A command
    // may be registered as either a key/value pair (as 'signature' => 'class'),
    // or as a class name only. A signature defined below overwrites its
    // corresponding class' signature, in order to prevent duplicate commands.
    // -------------------------------------------------------------------------
    // Accepts: array
    // Default: []
    // =========================================================================
    'commands' => [
        'dice' => \App\Caroler\Commands\Dice::class,
    ],

    // =========================================================================
    // Whether or not debugging mode is enabled. Debugging mode will show
    // additional info in the enabled output writers.
    // -------------------------------------------------------------------------
    // Accepts: boolean
    // Default: false
    // =========================================================================
    'debug' => false,
];

#!/usr/bin/php -q
<?php
/**
 * Simple Twitch Bot
 * based on the work of Kai Neuwerth
 * @author      Kai Neuwerth <github.com/Crease29>
 *
 * Copyright (C) 2017 by Mario Lubenka
 * @author      Mario Lubenka <github.com/saitho>
 * @link        https://github.com/saitho/simple-twitch-bot/
 */
define('BASE_PATH', '../../../../');
require_once BASE_PATH . 'vendor/autoload.php';

/**
 * In panic situations, you can always kill you daemon by typing
 *
 * killall -9 currency.php
 * OR:
 * killall -9 php§
 *
 * Check if process is running: ps uf -C currency.php
 *
 */
$daemon = new \saitho\TwitchBot\Features\Currency\Daemon\CurrencyDaemon('twitchbot_currency');
$daemon->call();
?>
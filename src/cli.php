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

define('BASE_PATH', realpath( dirname( __FILE__ ) . '/../' ) . '/');
require_once BASE_PATH.'vendor/autoload.php';

echo PHP_EOL;

$oConfig = \saitho\TwitchBot\Core\Config::getInstance();

define('COMMAND_PREFIX', $oConfig->get('app.commandPrefix'));

$oIRCBot = new \saitho\TwitchBot\Core\IRCBot(
	$oConfig->get( 'irc.server' ),
	$oConfig->get( 'irc.port' ),
	$oConfig->get( 'bot.nick' ),
	#explode( ',', $oConfig->get( 'irc.channels' ) ),
	[ '#'.$oConfig->get('app.channelName') ],
	$oConfig->get( 'bot.oauth' )
);

echo PHP_EOL.PHP_EOL;
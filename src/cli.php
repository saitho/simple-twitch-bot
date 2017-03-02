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
require_once('_init.php');

$oIRCBot = new \saitho\TwitchBot\Core\IRCBot(
	$oConfig->get( 'irc.server' ),
	$oConfig->get( 'irc.port' ),
	$oConfig->get( 'bot.nick' ),
	#explode( ',', $oConfig->get( 'irc.channels' ) ),
	[ '#'.$oConfig->get('app.channelName') ],
	$oConfig->get( 'bot.oauth' )
);
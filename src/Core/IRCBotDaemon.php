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

namespace saitho\TwitchBot\Core;

class IRCBotDaemon extends Daemon {
	public function process(Daemon $daemon, &$cnt) {
		if(empty($this->commander)) {
			throw new \Exception('Daemon is missing the Commander.');
		}
		$oConfig = Config::getInstance();
		$oIRCBot = new IRCBot(
			$oConfig->get( 'irc.server' ),
			$oConfig->get( 'irc.port' ),
			$oConfig->get( 'bot.nick' ),
			#explode( ',', $oConfig->get( 'irc.channels' ) ),
			[ '#'.$oConfig->get('app.channelName') ],
			$oConfig->get( 'bot.oauth' ),
			$this->commander
		);
	}
}
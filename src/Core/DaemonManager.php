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

class DaemonManager {
	static private $daemons = [];
	static public function registerDaemon($daemonName, $daemonClassName) {
		self::$daemons[$daemonName] = $daemonClassName;
	}
	
	static public function getDaemon($daemonName) {
		if(!array_key_exists($daemonName, self::$daemons)) {
			return null;
		}
		return self::$daemons[$daemonName];
	}
}
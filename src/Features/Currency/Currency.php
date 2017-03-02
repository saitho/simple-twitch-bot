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

namespace saitho\TwitchBot\Features\Currency;

use saitho\TwitchBot\Core\Feature;
use saitho\TwitchBot\Core\ViewerManager;

class Currency extends Feature {
	
	static public function userLeave($userName, $joinTime) {
		echo 'User '.$userName.' leaves - joined on '.$joinTime;
	}
	
	static public function init() {
		ViewerManager::registerHook(ViewerManager::ACTION_LEAVE, __CLASS__.'::userLeave');
	}
}
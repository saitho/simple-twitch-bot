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

class ViewerManager {
	const ACTION_JOIN = 1;
	const ACTION_LEAVE = 2;
	const ACTION_CLEAR = 3;
	
	static public $viewers = [];
	static public $hooks = [
		self::ACTION_JOIN => [],
		self::ACTION_LEAVE => [],
		self::ACTION_CLEAR => [],
	];
	
	/**
	 * @param int $action
	 * @param callable $function
	 */
	static public function registerHook($action, callable $function) {
		self::$hooks[$action][] = $function;
	}
	
	static public function userJoins($userName, $forced=false) {
		self::$viewers[$userName] = time();
		
		// Call registered hooks
		foreach(self::$hooks[self::ACTION_JOIN] AS $hook) {
			$hook($userName, self::$viewers[$userName]);
		}
		Logger::userLog($userName, 'JOIN');
	}
	static public function userLeaves($userName, $forced=false) {
		// Call registered hooks
		foreach(self::$hooks[self::ACTION_LEAVE] AS $hook) {
			$hook($userName, self::$viewers[$userName]);
		}
		
		unset(self::$viewers[$userName]);
		Logger::userLog($userName, 'LEAVE');
	}
	static public function clear() {
		// Call registered hooks
		foreach(self::$hooks[self::ACTION_CLEAR] AS $hook) {
			$hook();
		}
		
		foreach(self::$viewers AS $userName => $joinTime) {
			self::userLeaves($userName, true);
		}
	}
}
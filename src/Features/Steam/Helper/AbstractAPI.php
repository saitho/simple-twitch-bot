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

namespace saitho\TwitchBot\Features\Steam\Helper;
abstract class AbstractAPI {
	protected $games;
	
	public function __construct($games) {
		$this->games = $games;
	}
	
	/**
	 * @param string $profileId
	 * @param int $appId
	 * @return array
	 */
	abstract public function loadData($profileId, $appId);
	
	public function fetchData(array $configuration) {
		$game = $configuration['game'];
		if(empty($game) || !array_key_exists($game, $this->games)) {
			throw new \Exception('Spiel nicht in Whitelist. Bitte kontaktiere den Administrator.');
		}
		
		$appId = $this->games[$game];
		return $this->loadData($configuration['profileId'], $appId);
	}
}
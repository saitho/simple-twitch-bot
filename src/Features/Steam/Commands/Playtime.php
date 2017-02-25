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

namespace saitho\TwitchBot\Features\Steam\Commands;
use saitho\TwitchBot\Core\Command;
use saitho\TwitchBot\Core\Logger;
use saitho\TwitchBot\Core\Translator;
use saitho\TwitchBot\Core\Twitch;
use saitho\TwitchBot\Features\Steam\Helper\AbstractAPI;
use saitho\TwitchBot\Features\Steam\Helper\Config;

class Playtime extends Command {
	protected $_commandName = 'playtime';
	protected $_commandDescription = 'Gets playtime from STEAM';
	
    /**
     * Can be used as hook when a command is *really* executed.
     *
     * @return void
     */
    public function doExecute() {
    	$game = Twitch::getPlayedGame();
    	if(empty($game)) {
			Logger::cliLog( 'No game found: executing !'.$this->_commandName.' aborted. Stream offline?', 'COMMANDER' );
    		return;
		}
    	
		/** @var AbstractAPI $api */
		$config = Config::getFeatureConfig();
		
		$api = new \saitho\TwitchBot\Features\Steam\Helper\Playtime($config['games']);
	
		try {
			$data = $api->fetchData(['profileId' => $config['settings']['streamer_steamId'], 'game' => $game]);
			
			if(!array_key_exists('playtime_forever', $data)) {
				return;
			}
			
			$message = 'STEAM_PLAYTIME';
			$params = [
				'%timeTotalHours%' => floor($data['playtime_forever']/60),
				'%timeTotalMinutes%' => ($data['playtime_forever']%60),
			];
						
			$this->setReturnMessage( Translator::getInstance()->trans($message, $params) );
		} catch(\Exception $e) {
			if(empty($_GET['hideErrors'])) {
				$this->setReturnMessage( $e->getMessage() );
			}
		};
    }
}
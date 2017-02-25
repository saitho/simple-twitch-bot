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
use saitho\TwitchBot\Core\GeneralUtility;
use saitho\TwitchBot\Core\Translator;
use saitho\TwitchBot\Core\Twitch;
use saitho\TwitchBot\Features\Steam\Helper\AbstractAPI;
use saitho\TwitchBot\Features\Steam\Helper\Config;

class Achievements extends Command {
	protected $_commandName = 'achievements';
	protected $_commandDescription = 'Displays Steam achievements';
	
    /**
     * Can be used as hook when a command is *really* executed.
     *
     * @return void
     */
    public function doExecute() {
    	$game = Twitch::getPlayedGame();
    	if(empty($game)) {
			GeneralUtility::cliLog( 'No game found: executing !'.$this->_commandName.' aborted. Stream offline?', 'COMMANDER' );
    		return;
		}
    	
		/** @var AbstractAPI $api */
		$config = Config::getConfig();
		
		$api = new \saitho\TwitchBot\Features\Steam\Helper\Achievements($config['games']);
	
		try {
			$data = $api->fetchData(['profileId' => $config['settings']['streamer_steamId'], 'game' => $game]);
			
			$unlockedAchievements = $data['unlockedAchievements'];
			$allAchievements = $data['allAchievements'];
			
			$message = 'STEAM_ACHIEVEMENTS_UNLOCKED';
			$params = [
				'%unlocked%' => count($unlockedAchievements),
				'%total%' => count($allAchievements),
			];
			
			if(count($data)) {
				$latest = $unlockedAchievements[count($data)-1];
				if(!empty($latest['unlockTimestamp'])) {
					$params['%latestName%'] = $latest['name'];
					$params['%latestDate%'] = date('d.m.Y', $latest['unlockTimestamp']);
					$message = 'STEAM_ACHIEVEMENTS_UNLOCKED_LATEST';
				}
			}
			
			$this->setReturnMessage( Translator::getInstance()->trans($message, $params) );
		} catch(\Exception $e) {
			if(empty($_GET['hideErrors'])) {
				$this->setReturnMessage( $e->getMessage() );
			}
		};
    }
}
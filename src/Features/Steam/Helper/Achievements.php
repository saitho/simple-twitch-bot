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

class Achievements extends AbstractAPI  {
	const STEAM_PLAYER_ACHIEVEMENTS_URL = 'http://steamcommunity.com/profiles/%s/stats/%d/achievements/?xml=1';
	const STEAM_PLAYER_ACHIEVEMENTS_DETAILS_URL = 'http://api.steampowered.com/ISteamUserStats/GetPlayerAchievements/v0001/?key=2A5272BC88D9B77C4E80AA69DDC7B5C2&steamid=%s&appid=%s&l=german';
	
	public function loadData($profileId, $appId) {
		// 1) Get all achievement data for titles
		$url_details = sprintf(self::STEAM_PLAYER_ACHIEVEMENTS_DETAILS_URL, $profileId, $appId);
		$allAchievements = [];
		try {
			$file = file_get_contents($url_details);
			$detailResponse = json_decode($file);
			
			if($detailResponse->playerstats->success) {
				if(empty($detailResponse->playerstats->achievements)) {
					return [
						'unlockedAchievements' => [],
						'allAchievements' => []
					];
				}
				foreach($detailResponse->playerstats->achievements AS $achievement) {
					$allAchievements[strtoupper($achievement->apiname)] = [
						'name' => $achievement->name,
						'description' => $achievement->description
					];
				}
			}
		} catch (\Exception $e) {
			
		}
				
		// 2) Get unlocked achievements
		$url = sprintf(self::STEAM_PLAYER_ACHIEVEMENTS_URL, $profileId, $appId);
		$response = file_get_contents($url);
		
		$unlockedAchievements = [];
		$xml = simplexml_load_string($response);
		if($xml === false) {
			throw new \Exception('Error creating object.');
		}
		$achievements = $xml->xpath('/playerstats/achievements/achievement[@closed=1]');
		foreach ($achievements AS $achievement) {
			$apiName = strtoupper($achievement->apiname);
			if(!array_key_exists($apiName, $allAchievements)) {
				continue;
			}
			/** @var \SimpleXMLElement $apiNameElement */
			$achievementFromList = $allAchievements[$apiName];
			$unlockedAchievements[] = [
				'name' => $achievementFromList['name'],
				'description' => $achievementFromList['description'],
				'unlockTimestamp' => trim($achievement->unlockTimestamp)
			];
		}
		
		usort($unlockedAchievements, function($a, $b) {
			if($a['unlockTimestamp'] > $b['unlockTimestamp']) {
				return -1;
			}
			return 1;
		});
		return [
			'unlockedAchievements' => $unlockedAchievements,
			'allAchievements' => $allAchievements
		];
	}
}
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

class Playtime extends AbstractAPI {
	const STEAM_PLAYER_OWNED_GAMES_URL = 'http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=2A5272BC88D9B77C4E80AA69DDC7B5C2&steamid=%s';
	
	public function loadData($profileId, $appId) {
		$url_details = sprintf(self::STEAM_PLAYER_OWNED_GAMES_URL, $profileId);
		$detailResponse = json_decode(file_get_contents($url_details));
		foreach($detailResponse->response->games AS $game) {
			if($game->appid == $appId) {
				return [
					'playtime_forever' => $game->playtime_forever
				];
				break;
			}
		}
		return [];
	}
	//
}
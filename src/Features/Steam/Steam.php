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

namespace saitho\TwitchBot\Features\Steam;

use saitho\TwitchBot\Core\Feature;
use saitho\TwitchBot\Core\Logger;

class Steam extends Feature {
	static public function updateGameList() {
		Logger::cliLog('[STEAM] Updating Game List');
		$url = 'http://api.steampowered.com/ISteamApps/GetAppList/v0002/';
		$content = json_decode(file_get_contents($url));
		$fileContent = [];
		foreach($content->applist->apps AS $app) {
			$fileContent[$app->name] = $app->appid;
		}
		// Save new game list to gamelist.ini
		$file = fopen(__DIR__.'/gamelist.php', 'w') or die('Unable to open file!');
		fwrite($file, '<?php'.PHP_EOL.'return '.var_export($fileContent, true).';');
		fclose($file);
	}
	static public function init() {
		// Update gamelist.ini
		self::updateGameList();
	}
}
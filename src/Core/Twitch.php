<?php
namespace saitho\TwitchBot\Core;

class Twitch {
	static private $streamData = [];
	
	static private function getStreamData() {
		$config = Config::getInstance();
		
		$channelName = $config->get('app.channelName');
		if(empty($channelName)) {
			return [];
		}
		
		if(empty(self::$streamData)) {
			ob_start();
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://api.twitch.tv/kraken/streams/'.$channelName);
			$headers = [
				'Accept: application/vnd.twitchtv.v3+json',
				'Client-ID: '.$config->get('app.clientId'),
				'Authorization: OAuth '.$config->get('irc.oauth')
			];
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_exec($ch);
			curl_close($ch);
			$result = ob_get_clean();
			$json = json_decode($result);
			if(empty($json->stream)) {
				return [];
			}
			self::$streamData = $json->stream;
		}
		return self::$streamData;
	}
	
	static public function getPlayedGame() {
		$config = self::getStreamData();
		if(empty($config->game)) {
			return '';
		}
		return $config->game;
	}
	
}
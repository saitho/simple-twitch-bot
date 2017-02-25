<?php
namespace saitho\TwitchBot\Features\Steam\Helper;

class Config {
	static private $config;
	static public function getFeatureConfig() {
		if(empty(self::$config)) {
			self::$config = parse_ini_file(BASE_PATH.'src/Features/Steam/config.ini', true);
		}
		return self::$config;
	}
}
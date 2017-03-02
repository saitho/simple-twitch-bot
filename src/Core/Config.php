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

class Config {
    /**
     * @var Config
     */
    private static $__instance;
	
    /**
     * @var array
     */
    private $__aConfig = array();
	
	static private $config = [];
	static public function getFeatureConfig($featureName) {
		if(!array_key_exists($featureName, self::$config)) {
			self::$config[$featureName] = parse_ini_file(BASE_PATH.'src/Features/'.$featureName.'/config.ini', true);
		}
		return self::$config[$featureName];
	}

    /**
     * Parses configs/config.ini file and saves it to an array
     */
    public function __construct() {
        $this->__aConfig = parse_ini_file( BASE_PATH . 'config/config.ini', false, INI_SCANNER_NORMAL );
		Logger::cliLog( 'Configuration loaded from config/config.ini', 'SETUP' );
    }


    /**
     * @return Config
     */
    public static function getInstance() {
        if (empty(self::$__instance)) {
            self::$__instance = new self;
        }
        return self::$__instance;
    }
	
	
	/**
	 * @param $sKey
	 * @return bool
	 */
	public function hasKey( $sKey ) {
		return array_key_exists($sKey, $this->__aConfig);
	}
	
	/**
	 * Returns config value
	 *
	 * @param $sKey
	 *
	 * @return mixed
	 */
	public function get( $sKey ) {
		if(!$this->hasKey($sKey)) {
			return null;
		}
		return $this->__aConfig[ $sKey ];
	}

    /**
     * Helper function to check if a nick has mod status.
     *
     * @param string $sNick
     *
     * @return bool
     */
    public function isMod( $sNick ) {
        return in_array( strtolower($sNick), explode( ',', $this->get( 'app.mods' ) ) );
    }
}
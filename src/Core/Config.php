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
    private static $__aConfig = array();
	
	
	static public $configDir = BASE_PATH . 'config/';
	static public $systemExampleConfigPath = BASE_PATH . 'src/Core/config.example.ini';
	static public $featureExampleConfigPath = BASE_PATH . 'src/Features/%s/config.example.ini';
	static private $featureConfig = [];
	static public function getFeatureConfig($featureName) {
		if(!array_key_exists($featureName, self::$featureConfig)) {
			self::$featureConfig[$featureName] = parse_ini_file(self::$configDir.'/config.'.$featureName.'.ini', true);
		}
		return self::$featureConfig[$featureName];
	}
	
	public static function hasFeatureConf($featureName) {
		return file_exists(self::$configDir.'/config.'.$featureName.'.ini');
	}

    /**
     * Parses configs/config.ini file and saves it to an array
     */
    public function __construct() {
		self::initialize();
    }
    
    public static function initialize() {
		if(file_exists(self::$configDir.'config.ini')) {
			self::$__aConfig = parse_ini_file( self::$configDir.'config.ini', true );
			Logger::cliLog( 'Configuration loaded from config/config.ini', 'SETUP' );
		}
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
		$config = self::$__aConfig;
		foreach (explode('.', $sKey) AS $sKeyPart) {
			if(!array_key_exists($sKeyPart, $config)) {
				return false;
			}
			$config = $config[$sKeyPart];
		}
		return true;
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
		$configElement = self::$__aConfig;
		foreach (explode('.', $sKey) AS $sKeyPart) {
			$configElement = $configElement[$sKeyPart];
		}
		return $configElement;
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
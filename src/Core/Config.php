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
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\Translator;

class Config {
    /**
     * @var Config
     */
    private static $__instance;
	
    /**
     * @var array
     */
    private $__aConfig = array();


    /**
     * Parses configs/config.ini file and saves it to an array
     */
    public function __construct() {
        $this->__aConfig = parse_ini_file( BASE_PATH . 'config/config.ini', false, INI_SCANNER_NORMAL );
		GeneralUtility::cliLog( 'Configuration loaded from config/config.ini', 'SETUP' );
    }


    /**
     * @return Config
     */
    public static function getInstance() {
        if ( !self::$__instance ) {
            self::$__instance = new self;
        }

        return self::$__instance;
    }


    /**
     * Returns config value
     *
     * @param $sKey
     *
     * @return null|mixed
     */
    public function get( $sKey ) {
        return isset( $this->__aConfig[ $sKey ] ) ? $this->__aConfig[ $sKey ] : null;
    }


    /**
     * Helper function to get a language string from the config.
     *
     * @param string $sKey
     * @param array  $aParams
     *
     * @return mixed|null
     */
    public function lang( $sKey, $aParams = array() ) {
        $sLanguage   = $this->__aConfig[ 'app.language' ];
        
        $translator = new Translator($sLanguage);
		$translator->addLoader('xlf', new XliffFileLoader());
		$translationFiles = glob( BASE_PATH.'config/locale/*.xlf' );
		foreach($translationFiles AS $translationFile) {
			preg_match('/config\/locale\/(.*)\.xlf$/', $translationFile, $match);
			$translator->addResource('xlf', $translationFile, $match[1]);
		}
		$translator->addResource('xlf', BASE_PATH.'config/locale/en.xlf', 'en');
		$sTranslated = $translator->trans($sKey);
	
		if( !empty( $sTranslated ) && count( $aParams ) ) {
			$sTranslated = sprintf( $sTranslated, $aParams );
		}
        return $sTranslated;
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
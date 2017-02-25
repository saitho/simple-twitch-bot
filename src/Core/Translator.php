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

class Translator {
	    
    /** @var \Symfony\Component\Translation\Translator $translator */
    static private $translator;

    /**
     * Parses configs/config.ini file and saves it to an array
     */
	static public function getInstance() {
		if(empty(self::$translator)) {
			$sLanguage = Config::getInstance()->get('app.language');
			self::$translator = new \Symfony\Component\Translation\Translator($sLanguage);
			self::$translator->addLoader('xlf', new XliffFileLoader());
			$translationFiles = glob( BASE_PATH.'config/locale/*.xlf' );
			foreach($translationFiles AS $translationFile) {
				preg_match('/config\/locale\/(.*)\.xlf$/', $translationFile, $match);
				self::$translator->addResource('xlf', $translationFile, $match[1]);
			}
		}
		
		Logger::cliLog( 'Translations loaded from config/locale/', 'SETUP' );
		return self::$translator;
    }
}
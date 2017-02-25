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

class GeneralUtility {
	static private $logFile;
		
	/**
	 * Helper-Method for CLI logging.
	 *
	 * @param string $sMessage
	 * @param string $sType
	 */
	static public function cliLog( $sMessage, $sType = 'INFO' ) {
		if(empty(self::$logFile)) {
			self::$logFile = BASE_PATH . 'logs/'.date('Y-m-d_H-i-s').'.log';
		}
		
		$sLog = '[' . date( 'Y-m-d H:i:s' ) . '] [' . str_pad( $sType, 11, ' ', STR_PAD_BOTH ) . '] ' . $sMessage . PHP_EOL;
		
		echo $sLog;
		file_put_contents( self::$logFile, $sLog, FILE_APPEND );
		
		if( $sType == 'CRITICAL' ) {
			die( 'Exiting.'.PHP_EOL );
		}
	}
}
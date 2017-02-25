<?php
namespace saitho\TwitchBot\Core;
/**
 * @link        https://github.com/Crease29/simple-twitch-bot
 * @author      Kai Neuwerth <github.com/Crease29>
 */

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
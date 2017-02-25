<?php
/**
 * @link        https://github.com/Crease29/simple-twitch-bot
 * @author      Kai Neuwerth <github.com/Crease29>
 */

define('BASE_PATH', realpath( dirname( __FILE__ ) . '/../' ) . '/');
// Clearing Log File
file_put_contents( BASE_PATH . 'cli.log', '');

class GeneralUtility {
	/**
	 * Helper-Method for CLI logging.
	 *
	 * @param string $sMessage
	 * @param string $sType
	 */
	static public function cliLog( $sMessage, $sType = 'INFO' ) {
		$sLog = '[' . date( 'Y-m-d H:i:s' ) . '] [' . str_pad( $sType, 11, ' ', STR_PAD_BOTH ) . '] ' . $sMessage . PHP_EOL;
		
		echo $sLog;
		file_put_contents( BASE_PATH . 'cli.log', $sLog, FILE_APPEND );
		
		if( $sType == 'CRITICAL' ) {
			die( 'Exiting.'.PHP_EOL );
		}
	}
}
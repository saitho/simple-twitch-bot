<?php
/**
 * @link        https://github.com/Crease29/simple-twitch-bot
 * @author      Kai Neuwerth <github.com/Crease29>
 */

require_once 'vendor/autoload.php';
define('BASE_PATH', realpath( dirname( __FILE__ ) . '/' ) . '/');

echo PHP_EOL;

$oConfig = \saitho\TwitchBot\Core\Config::getInstance();
$oIRCBot = new \saitho\TwitchBot\Core\IRCBot(
	$oConfig->get( 'irc.server' ),
	$oConfig->get( 'irc.port' ),
	$oConfig->get( 'irc.nick' ),
	explode( ',', $oConfig->get( 'irc.channels' ) ),
	$oConfig->get( 'irc.oauth' )
);

echo PHP_EOL.PHP_EOL;
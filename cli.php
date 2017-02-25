<?php
/**
 * @link        https://github.com/Crease29/simple-twitch-bot
 * @author      Kai Neuwerth <github.com/Crease29>
 */

require_once dirname(__FILE__) . '/core/GeneralUtility.php';
require_once BASE_PATH . 'core/Config.php';
require_once BASE_PATH . 'core/DB.php';
require_once BASE_PATH . 'core/IRCBot.php';

echo PHP_EOL;

$oConfig = Config::getInstance();
$oIRCBot = new IRCBot(
	$oConfig->get( 'irc.server' ),
	$oConfig->get( 'irc.port' ),
	$oConfig->get( 'irc.nick' ),
	explode( ',', $oConfig->get( 'irc.channels' ) ),
	$oConfig->get( 'irc.oauth' )
);

echo PHP_EOL.PHP_EOL;
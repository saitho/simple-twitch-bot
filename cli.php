<?php
/**
 * @link        https://github.com/Crease29/simple-twitch-bot
 * @author      Kai Neuwerth <github.com/Crease29>
 */

require_once dirname( __FILE__ ) . '/core/Helper.php';
require_once getBasePath() . 'core/Config.php';
require_once getBasePath() . 'core/DB.php';
require_once getBasePath() . 'core/IRCBot.php';

echo "\n";

$oConfig = Config::getInstance();
$oIRCBot = new IRCBot( $oConfig->get( 'irc.server' ), $oConfig->get( 'irc.port' ), $oConfig->get( 'irc.nick' ), explode( ',', $oConfig->get( 'irc.channels' ) ), $oConfig->get( 'irc.oauth' ) );

echo "\n\n";
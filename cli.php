<?php
/**
 *
 *     |o     o    |          |
 * ,---|.,---..,---|,---.,---.|__/
 * |   |||   |||   ||---'`---.|  \
 * `---'``---|``---'`---'`---'`   `
 *       `---'    [media solutions]
 *
 * @link            http://www.digidesk.de
 * @author          Kai Neuwerth
 * @version         Git: $Id$
 */

require_once 'core/Helper.php';
require_once 'core/Config.php';
require_once 'core/DB.php';
require_once 'core/IRCBot.php';


echo "\n";

$oConfig = Config::getInstance();
$oIRCBot = new IRCBot( $oConfig->get( 'irc.server' ), $oConfig->get( 'irc.port' ), $oConfig->get( 'irc.nick' ), explode( ',', $oConfig->get( 'irc.channels' ) ), $oConfig->get( 'irc.oauth' ) );

echo "\n\n";
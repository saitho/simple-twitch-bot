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

namespace saitho\TwitchBot\Commands;
use saitho\TwitchBot\Core\Command;

use saitho\TwitchBot\Core\Translator;

/**
 * Class Uptime_Command
 *
 * Returns the uptime of the bot.
 *
 * Usage:
 * !uptime
 */
class Uptime extends Command {
	protected $_commandName = 'uptime';


    /**
     * @var \DateTime
     */
    private $__oStartDateTime = null;


    public function __construct() {
    	parent::__construct();
        $this->__oStartDateTime = new \DateTime();
    }


    /**
     * Calculates current uptime of the bot
     */
    public function doExecute() {
        parent::doExecute();

        $oNow = new \DateTime();
        $oDifference = $this->__oStartDateTime->diff( $oNow );

        $iYears   = $oDifference->format( '%y' );
        $iDays    = $oDifference->format( '%d' );
        $iHours   = $oDifference->format( '%H' );
        $iMinutes = $oDifference->format( '%I' );

        $aResponse = array();
		if(!empty($iYears)) {
			$aResponse[] = $iYears . ' ' . Translator::getInstance()->trans( 'YEARS' );
		}
		if(!empty($iDays)) {
			$aResponse[] = $iDays . ' ' . Translator::getInstance()->trans( 'DAYS' );
		}
		if(!empty($iHours)) {
			$aResponse[] = $iHours . ' ' . Translator::getInstance()->trans( 'HOURS' );
		}
		if(!empty($iMinutes)) {
			$aResponse[] = $iMinutes . ' ' . Translator::getInstance()->trans( 'MINUTES' );
		}
        $this->setReturnMessage( implode(', ', $aResponse) );
    }
}
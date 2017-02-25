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
use saitho\TwitchBot\Core\Config;

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

        $sResponse  = $iYears ? $iYears . ' ' . Config::getInstance()->lang( 'YEARS' ) . ', ' : '';
        $sResponse .= $iDays  ? $iDays  . ' ' . Config::getInstance()->lang( 'DAYS' ) . ', ' : '';
        $sResponse .= $iHours . ':' . $iMinutes . ' ' . Config::getInstance()->lang( 'HOURS' );

        $this->setReturnMessage( $sResponse );
    }
}
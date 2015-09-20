<?php
/**
 * @link        https://github.com/Crease29/simple-twitch-bot
 * @author      Kai Neuwerth <github.com/Crease29>
 */

/**
 * Class Uptime_Command
 *
 * Returns the uptime of the bot.
 *
 * Usage:
 * !uptime
 */
class Uptime_Command extends Command
{
    /**
     * RegEx pattern to get the triggered
     *
     * @var string
     */
    protected $_sCmdPattern = "/^!uptime/i";


    /**
     * Command that will be listed in the available commands in the chat
     *
     * @var string
     */
    protected $_sReadablePattern = '!uptime';


    /**
     * @var DateTime
     */
    private $__oStartDateTime = null;


    public function __construct()
    {
        $this->__oStartDateTime = new DateTime();
    }


    /**
     * Calculates current uptime of the bot
     */
    public function doExecute()
    {
        parent::doExecute();

        $oNow = new DateTime();
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
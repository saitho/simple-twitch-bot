<?php
/**
 *
 *     |o     o    |          |    
 * ,---|.,---..,---|,---.,---.|__/ 
 * |   |||   |||   ||---'`---.|  \ 
 * `---'``---|``---'`---'`---'`   `
 *       `---'    [media solutions]
 *
 * @copyright   (c) digidesk - media solutions
 * @link        http://www.digidesk.de
 * @author      kneuwerth
 * @version     SVN: $Id$
 */

/**
 * Class Uptime_Command
 *
 * Returns the uptime of the bot.
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
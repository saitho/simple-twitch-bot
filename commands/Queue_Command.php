<?php
/**
 * @link        https://github.com/Crease29/simple-twitch-bot
 * @author      Kai Neuwerth <github.com/Crease29>
 */

/**
 * Class Queue_Command
 *
 * Saves nicks in a queue for viewergames.
 *
 * Usage:
 * !queue join <nick>
 * !queue get <amount>
 * !queue list
 * !queue clear
 */
class Queue_Command extends Command
{
    /**
     * RegEx pattern to get the triggered
     *
     * @var string
     */
    protected $_sCmdPattern = "/^!queue( ([a-zA-Z]+))( ([a-zA-Z0-9_-]+))?$/i";


    /**
     * Command that will be listed in the available commands in the chat
     *
     * @var string
     */
    protected $_sReadablePattern = '!queue';


    /**
     * Queue holder
     *
     * @var array
     */
    private $__aNicks = array();


    /**
     * Can be used as hook before a command is executed.
     *
     * @return void
     */
    public function onBeforeExecute()
    {
        parent::onBeforeExecute();
    }


    /**
     * Can be used as hook when a command is *really* executed.
     *
     * @return void
     */
    public function doExecute()
    {
        parent::doExecute();

        $sMessage = $this->getReceivedMessage();
        $aArgs = $this->getParameters();
        $sAction = $aArgs[ 2 ][ 0 ];

        switch ( $sAction )
        {
            case 'join':
                $this->addToQueue( $this->getSender(), ( !empty( $aArgs[ 4 ][ 0 ] ) ? $aArgs[ 4 ][ 0 ] : $this->getSender() ) );
                break;
            case 'get':
                $this->getFromQueue( ( !empty( $aArgs[ 4 ][ 0 ] ) ? (int)$aArgs[ 4 ][ 0 ] : 1 ) );
                break;
            case 'list':
                $this->listAll();
                break;
            case 'clear':
                $this->clearQueue();
                break;
        }
    }


    /**
     * Can be used as hook after a command is executed.
     *
     * @return void
     */
    public function onAfterExecute()
    {
        parent::onAfterExecute();
    }


    /**
     * Adds a viewer to the queue.
     *
     * @param string $sSender
     * @param string $sGameNick
     */
    public function addToQueue( $sSender, $sGameNick )
    {
        $iPos = $this->__isInQueue( $sSender );

        if ( $iPos === 0 )
        {
            cliLog( "Adding {$sSender} ({$sGameNick}) to the queue.", 'QUEUE' );
            $this->__aNicks[] = array( 'twitch_nick' => $sSender, 'game_nick' => $sGameNick );

            $iPos = count( $this->__aNicks );
        }

        $this->setReturnMessage( Config::getInstance()->lang( 'QUEUE_ADDED', array( $sSender, $iPos ) ) );
    }


    /**
     * Checks if a user is already in the queue and returns the queue position.
     *
     * @param string $sNick
     *
     * @return int
     */
    private function __isInQueue( $sNick )
    {
        $iRet = 0;

        foreach ( $this->__aNicks as $iPos => $aViewer )
        {
            if ( $aViewer[ 'twitch_nick' ] == $sNick )
            {
                $iRet = (int)$iPos + 1;
                break;
            }
        }

        return $iRet;
    }


    /**
     * Returns X viewers from the queue and deletes them from the queue.
     *
     * @param int $iAmount
     */
    public function getFromQueue( $iAmount )
    {
        if ( Config::getInstance()->isMod( $this->getSender() ) && count( $this->__aNicks ) && $iAmount > 0 )
        {
            cliLog( "Fetching {$iAmount} from the queue.", 'QUEUE' );

            $aPicked = array_slice( $this->__aNicks, 0, $iAmount );
            $sPicked = '';

            // build return message
            foreach ( $aPicked as $aViewer )
            {
                $sPicked .= $aViewer[ 'twitch_nick' ] . ' (' . $aViewer[ 'game_nick' ] . '), ';

                // remove viewer from queue
                $this->removeFromQueue( $aViewer[ 'twitch_nick' ] );
            }

            if ( !empty( $sPicked ) )
            {
                cliLog( "Picked from queue: " . substr( $sPicked, 0, -2 ), 'QUEUE' );
                $this->setReturnMessage( Config::getInstance()->lang( 'QUEUE_PICKED' ) . ' ' . substr( $sPicked, 0, -2 ) );
            }
        }
    }


    /**
     * Clears the queue.
     *
     * @param string $sNick
     */
    public function removeFromQueue( $sNick )
    {
        $iPos = $this->__isInQueue( $sNick );

        if ( $iPos !== 0 )
        {
            cliLog( "Removed {$sNick} from the queue.", 'QUEUE' );
            unset( $this->__aNicks[ ( $iPos - 1 ) ] );
        }
    }


    /**
     * Lists all viewers that are in the queue.
     */
    public function listAll()
    {
        $sNicks = '';

        // build return message
        foreach ( $this->__aNicks as $aViewer )
        {
            $sNicks .= $aViewer[ 'twitch_nick' ] . ' (' . $aViewer[ 'game_nick' ] . '), ';
        }

        if ( !empty( $sNicks ) )
        {
            cliLog( "Current queue: " . substr( $sNicks, 0, -2 ), 'QUEUE' );
            $this->setReturnMessage( Config::getInstance()->lang( 'QUEUE_LIST' ) . ' ' . substr( $sNicks, 0, -2 ) );
        }
        else
        {
            cliLog( "Queue is empty.", 'QUEUE' );
            $this->setReturnMessage( Config::getInstance()->lang( 'QUEUE_EMPTY' ) );
        }
    }


    /**
     * Clears the queue.
     */
    public function clearQueue()
    {
        if ( Config::getInstance()->isMod( $this->getSender() ) )
        {
            cliLog( "Queue cleared.", 'QUEUE' );

            $this->__aNicks = array();
            $this->setReturnMessage( Config::getInstance()->lang( 'QUEUE_CLEARED' ) );
        }
    }
}
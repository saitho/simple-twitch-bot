<?php
/**
 * @link        https://github.com/Crease29/simple-twitch-bot
 * @author      Kai Neuwerth <github.com/Crease29>
 */
/**
 * Class Welcome_Command
 *
 * Says hello to the sender.
 *
 * Usage:
 * !welcome
 * !welcome <nick>
 */
class Welcome_Command extends Command
{
    /**
     * RegEx pattern to get the triggered
     *
     * @var string
     */
    protected $_sCmdPattern = "/^!welcome( ([a-z0-9_-]+))?$/i";

    /**
     * Command that will be listed in the available commands in the chat
     *
     * @var string
     */
    protected $_sReadablePattern = '!welcome';


    /**
     * Says hello to the sender or first command parameter.
     */
    public function doExecute()
    {
        parent::doExecute();

        $sToBeGreeted = $this->getSender();
        $aArgs        = $this->getParameters();

        // Checking if there is a name given that shall be greeted
        if( isset( $aArgs[ 2 ][ 0 ] ) && !empty( $aArgs[ 2 ][ 0 ] ) )
        {
            $sToBeGreeted = $aArgs[ 2 ][ 0 ];
        }

        $this->setReturnMessage( Config::getInstance()->lang( 'WELCOME', $sToBeGreeted ) );
    }
}
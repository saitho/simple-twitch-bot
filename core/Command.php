<?php
/**
 * @link        https://github.com/Crease29/simple-twitch-bot
 * @author      Kai Neuwerth <github.com/Crease29>
 */

class Command
{
    /**
     * Defines if a command is public. If a command is mod-only it wont be public.
     *
     * @var bool
     */
    protected $_blIsPublic = true;


    /**
     * Defines if a command can only be used by admin or everyone.
     *
     * @var bool
     */
    protected $_blIsModOnly = false;


    /**
     * RegEx pattern to get the triggered
     *
     * @var string
     */
    protected $_sCmdPattern = "//i";


    /**
     * Command that will be listed in the available commands in the chat
     *
     * @var string
     */
    protected $_sReadablePattern = '';


    /**
     * Nick that initiated the execution of a command
     *
     * @var string
     */
    protected $_sSender = '';


    /**
     * Contains whole message with command
     *
     * @var string
     */
    protected $_sReceivedMessage = '';


    /**
     * Message that the bot sends after executing the command
     *
     * @var string
     */
    protected $_sReturnMessage = '';


    /**
     * Returns if a command is public. If a command is mod-only it wont be public.
     *
     * @return bool
     */
    public function isPublic()
    {
        return $this->_blIsPublic && !$this->_blIsModOnly;
    }


    /**
     * Returns if a command can only be used by admin or everyone.
     *
     * @return bool
     */
    public function isModOnly()
    {
        return $this->_blIsModOnly;
    }


    /**
     * Returns the command pattern as RegEx
     *
     * @return string
     */
    public function getCommandPattern()
    {
        return $this->_sCmdPattern;
    }


    /**
     * Returns the readable command pattern
     *
     * @return string
     */
    public function getReadableCommandPattern()
    {
        return $this->_sReadablePattern;
    }


    /**
     * Getter method for property "_sSender"
     *
     * @return string
     */
    public function getSender()
    {
        return $this->_sSender;
    }


    /**
     * Getter method for property "_sSender"
     *
     * @param string $sSender
     */
    public function setSender( $sSender )
    {
        $this->_sSender = $sSender;
    }


    /**
     * Getter method for property "_sReceivedMessage"
     *
     * @return string
     */
    public function getReturnMessage()
    {
        return $this->_sReturnMessage;
    }


    /**
     * Getter method for property "_sReceivedMessage"
     *
     * @param string $sReturnMessage
     */
    public function setReturnMessage( $sReturnMessage )
    {
        $this->_sReturnMessage = $sReturnMessage;
    }


    /**
     * Getter method for property "_sReceivedMessage"
     *
     * @return string
     */
    public function getReceivedMessage()
    {
        return $this->_sReceivedMessage;
    }


    /**
     * Getter method for property "_sReceivedMessage"
     *
     * @param string $sReceivedMessage
     */
    public function setReceivedMessage( $sReceivedMessage )
    {
        $this->_sReceivedMessage = $sReceivedMessage;
    }


    /**
     * Checks if a message contains a command
     *
     * @return bool
     */
    public function messageContainsCommand( $sMessage )
    {
        $sMessage = trim( $sMessage );

        return (bool)preg_match( $this->getCommandPattern(), $sMessage );
    }


    /**
     * Get parameters given in a command
     *
     * @return array
     */
    public function getParameters()
    {
        $iMatches = preg_match_all( $this->getCommandPattern(), $this->getReceivedMessage(), $aMatches );

        return $iMatches > 0 ? $aMatches : array();
    }


    public function execute( $sMessage, $sFrom )
    {
        $this->setReceivedMessage( trim( $sMessage ) );
        $this->setSender( $sFrom );
        $this->setReturnMessage( '' );

        $this->onBeforeExecute();
        $this->doExecute();
        $this->onAfterExecute();

        return $this->getReturnMessage();
    }


    /**
     * Can be used as hook before a command is executed.
     *
     * @return void
     */
    public function onBeforeExecute()
    {
        cliLog( "Trigger " . __METHOD__, 'COMMANDER' );
    }


    /**
     * Can be used as hook when a command is *really* executed.
     *
     * @return void
     */
    public function doExecute()
    {
        cliLog( "Executing command " . __CLASS__, 'COMMANDER' );
    }


    /**
     * Can be used as hook after a command is executed.
     *
     * @return void
     */
    public function onAfterExecute()
    {
        cliLog( "Trigger " . __METHOD__, 'COMMANDER' );
    }
}
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

namespace saitho\TwitchBot\Core;

class Command {
	/** @var Config $config */
	protected $config = null;
	protected $_commandName = '';
	
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
	 * @var array
	 */
    protected $_arguments = [];

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

    public function __construct() {
    	$this->config = Config::getInstance();
	}
	
	/**
     * Returns if a command is public. If a command is mod-only it wont be public.
     *
     * @return bool
     */
    public function isPublic() {
        return $this->_blIsPublic && !$this->_blIsModOnly;
    }


    /**
     * Returns if a command can only be used by admin or everyone.
     *
     * @return bool
     */
    public function isModOnly() {
        return $this->_blIsModOnly;
    }


    /**
     * Returns the command pattern as RegEx
     *
     * @return string
	 * @throws \Exception
     */
    public function getCommandPattern() {
		$pattern = COMMAND_PREFIX.$this->_commandName;
    	if(!empty($this->_arguments)) {
			foreach($this->_arguments AS $argName => $arg) {
				if(!array_key_exists('regex', $arg)) {
					throw new \Exception('Missing key "regex" for argument '.$argName.' in command '.$this->_commandName);
				}
				$pattern .= '\s?('.$arg['regex'].')';
				if(array_key_exists('optional', $arg)) {
					$pattern .= '?';
				}
			}
		}
		return '/^'.$pattern.'$/i';
    }


    /**
     * Returns the readable command pattern
     *
     * @return string
     */
    public function getReadableCommandPattern() {
        return COMMAND_PREFIX.$this->_commandName;
    }


    /**
     * Getter method for property "_sSender"
     *
     * @return string
     */
    public function getSender() {
        return $this->_sSender;
    }


    /**
     * Getter method for property "_sSender"
     *
     * @param string $sSender
     */
    public function setSender( $sSender ) {
        $this->_sSender = $sSender;
    }


    /**
     * Getter method for property "_sReceivedMessage"
     *
     * @return string
     */
    public function getReturnMessage() {
        return $this->_sReturnMessage;
    }


    /**
     * Getter method for property "_sReceivedMessage"
     *
     * @param string $sReturnMessage
     */
    public function setReturnMessage( $sReturnMessage ) {
        $this->_sReturnMessage = $sReturnMessage;
    }


    /**
     * Getter method for property "_sReceivedMessage"
     *
     * @return string
     */
    public function getReceivedMessage() {
        return $this->_sReceivedMessage;
    }


    /**
     * Getter method for property "_sReceivedMessage"
     *
     * @param string $sReceivedMessage
     */
    public function setReceivedMessage( $sReceivedMessage ) {
        $this->_sReceivedMessage = $sReceivedMessage;
    }


    /**
     * Checks if a message contains a command
     *
	 * @param string $sMessage
     * @return bool
     */
    public function messageContainsCommand( $sMessage ) {
        $sMessage = trim( $sMessage );
        return boolval(preg_match( $this->getCommandPattern(), $sMessage ));
    }
	
	
	/**
	 * Get parameters given in a command
	 *
	 * @return array
	 */
	public function getParameters() {
		$iMatches = preg_match_all( $this->getCommandPattern(), $this->getReceivedMessage(), $aMatches );
		
		return $iMatches > 0 ? $aMatches : array();
	}
	/**
	 * Get a certain parameter
	 *
	 * @param $i
	 * @return array
	 */
	public function getParameter($i) {
		$parameters = $this->getParameters();
		return $parameters[$i][0];
	}


    public function execute( $sMessage, $sFrom ) {
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
    public function onBeforeExecute() {
		Logger::cliLog( 'Trigger ' . __METHOD__, 'COMMANDER' );
    }


    /**
     * Can be used as hook when a command is *really* executed.
     *
     * @return void
     */
    public function doExecute() {
		Logger::cliLog( 'Executing command ' . __CLASS__, 'COMMANDER' );
    }


    /**
     * Can be used as hook after a command is executed.
     *
     * @return void
     */
    public function onAfterExecute() {
		Logger::cliLog( 'Trigger ' . __METHOD__, 'COMMANDER' );
    }
}
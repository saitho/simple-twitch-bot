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

class IRCBot {
    const SYSTEM_RELOAD   = 1;
    const SYSTEM_SHUTDOWN = 2;

    /**
     * IP or host of the irc server to connect
     *
     * @var string
     */
    protected $_sServer   = '';


    /**
     * Port of the irc server to connect
     *
     * @var int
     */
    protected $_iPort     = 6667;


    /**
     * Nickname to connect with
     *
     * @var string
     */
    protected $_sNick     = '';


    /**
     * Contains channels that the bot has joined
     *
     * @var array
     */
    protected $_aChannels = array();


    /**
     * OAuth string for irc login
     *
     * @var string
     */
    protected $_sOAuth    = '';


    /**
     * TCP/IP-Connection to Server
     *
     * @var null
     */
    private $__fpSocket = null;


    /**
     * Array with static messages defined in configs/static_commands.ini
     *
     * @var array
     */
    private $__aStaticMessages = array();

    /**
     *
     * @var Commander
     */
    private $__oCommander = null;

    /**
     *
     * @var array
     */
    private $__aNewMessages = array();


    /**
     * Getter method for property "__fpSocket"
     *
     * @return null|resource
     */
    public function getSocket() {
        return $this->__fpSocket;
    }


    /**
     * Getter method for property "__oCommander"
     *
     * @return null|Commander
     */
    public function getCommander() {
        return $this->__oCommander;
    }


    /**
     * Setter method for property "__oCommander"
     *
     * @param Commander $oCommander
     */
    public function setCommander( $oCommander ) {
        $this->__oCommander = $oCommander;
    }


    /**
     * Getter method for property "_sServer".
     *
     * @return string
     */
    public function getServer() {
        return $this->_sServer;
    }


    /**
     * Setter method for property "_sServer".
     *
     * @param string $sServer
     */
    public function setServer( $sServer ) {
        $this->_sServer = $sServer;
    }


    /**
     * Getter method for property "_iPort".
     *
     * @return int
     */
    public function getPort() {
        return $this->_iPort;
    }


    /**
     * Setter method for property "_iPort".
     *
     * @param int $iPort
     */
    public function setPort( $iPort ) {
        $this->_iPort = $iPort;
    }


    /**
     * Getter method for property "_sNick".
     *
     * @return string
     */
    public function getNick() {
        return $this->_sNick;
    }


    /**
     * Setter method for property "_sNick".
     *
     * @param string $sNick
     */
    public function setNick( $sNick ) {
        $this->_sNick = $sNick;
    }


    /**
     * Getter method for property "_aChannels".
     *
     * @return array
     */
    public function getChannels() {
        return $this->_aChannels;
    }


    /**
     * Setter method for property "_aChannels".
     *
     * @param array $aChannels
     */
    public function setChannels( $aChannels ) {
        $this->_aChannels = $aChannels;
    }


    /**
     * Getter method for property "_sOAuth".
     *
     * @return string
     */
    public function getOAuth() {
        return $this->_sOAuth;
    }


    /**
     * Setter method for property "_sOAuth".
     *
     * @param string $sOAuth
     */
    public function setOAuth( $sOAuth ) {
        $this->_sOAuth = $sOAuth;
    }


    /**
     * Opens the server connection, logs the bot in
     *
     * @param string $sServer
     * @param int    $iPort
     * @param string $sNick
     * @param array  $aChannels
     * @param string $sOAuth
     */
    public function __construct( $sServer, $iPort = 6667, $sNick, $aChannels, $sOAuth ) {
		Logger::cliLog( 'Setting server: '.$sServer, 'SETUP' );
        $this->setServer( $sServer );
		Logger::cliLog( 'Setting port: '.$iPort, 'SETUP' );
        $this->setPort( $iPort );
		Logger::cliLog( 'Setting nickname: '.$sNick, 'SETUP' );
        $this->setNick( $sNick );
		Logger::cliLog( 'Setting channel: ' . implode( ', ', $aChannels ), 'SETUP' );
        $this->setChannels( $aChannels );
		Logger::cliLog( 'Setting oauth: '.$sOAuth, 'SETUP' );
        $this->setOAuth( $sOAuth );

        $this->_init();

        $oConfig = Config::getInstance();

        // Starting main thread
        while ( true ) {
            $aStatus = socket_get_status( $this->getSocket() );

            if( $aStatus[ 'timed_out' ] ) {
				Logger::cliLog( 'Reloading after timeout...', 'SYSTEM' );
				ViewerManager::clear();
                new self( $sServer, $iPort, $sNick, $aChannels, $sOAuth );
            }

            if( memory_get_usage( true ) / 1048576 > (double)$oConfig->get( 'app.mem_warning' ) ) {
				Logger::cliLog( 'Current memory usage: ' . memory_get_usage( true ) / 1048576 . 'MB', 'WARNING' );
            }

            $this->_getNewSocketContents();
            //$this->_getSTDINContents();

            if ( count( $this->__aNewMessages ) ) {
                $iReturn = $this->main();

                // Checking for system operations
                if ( $iReturn ) {
                    switch ( $iReturn ) {
                        case IRCBot::SYSTEM_RELOAD:
							ViewerManager::clear();
							Logger::cliLog( 'Reloading...', 'SYSTEM' );
                            new self( $sServer, $iPort, $sNick, $aChannels, $sOAuth );
                            break;
                            break;
                        case IRCBot::SYSTEM_SHUTDOWN:
							ViewerManager::clear();
							Logger::cliLog( 'Shutting down...', 'SYSTEM' );
                            exit;
                            break;
                    }
                }
            }

            sleep( (double)$oConfig->get( 'app.thread_interval' ) );
        }
    }


    /**
     * Initializes the bot's functions.
     */
    protected function _init() {
        $this->setCommander( new Commander() );
	
		Logger::cliLog( 'Opening socket...', 'SETUP' );
        $this->__fpSocket = fsockopen( $this->getServer(), $this->getPort() )
		or Logger::cliLog( 'Unable to connect to '.$this->getServer().':'.$this->getPort(), 'CRITICAL' );
		stream_set_timeout($this->__fpSocket, 360);
        
		Logger::cliLog( 'Logging in to server', 'SETUP' );
        $this->login();
	
		Logger::cliLog( 'Joining channels: ' . implode( ', ', $this->getChannels() ), 'SETUP' );
        $this->joinChannels( $this->getChannels() );
    }


    /**
     * Fetches new messages from socket.
     */
    protected function _getNewSocketContents() {
        //stream_set_blocking( $this->getSocket(), 0 );
        $aData = fgets( $this->getSocket(), 256 );

        $this->__aNewMessages = explode( ' ', $aData );
    }


    /**
     * Fetches new messages from STDIN stream.
     */
    protected function _getSTDINContents() {
        //stream_set_blocking( STDIN, 0 );
        $sInput = trim( fgets( STDIN ) );

        // ToDo: define some commands that can be typed in STDIN

        if( !empty( $sInput ) ) {
            var_dump( $sInput );
        }
    }

    /**
     * This is the workhorse function, grabs the data from the server and displays on the browser
     *
     * @return int
     */
    public function main() {
        if ( $this->__aNewMessages[0] == 'PING' ) {
            $this->sendData( 'PONG', $this->__aNewMessages[ 1 ] ); //Plays ping-pong with the server to stay connected.
			return 0;
        }
		preg_match('/^:(\w+)!/', $this->__aNewMessages[0], $matches);
        if(empty($matches[1])) {
        	return 0;
		}
		$sFrom = $matches[1];
        
        switch($this->__aNewMessages[1]) {
			case 'JOIN':
				ViewerManager::userJoins($sFrom);
				break;
			case 'PART':
				ViewerManager::userLeaves($sFrom);
				break;
			case 'PRIVMSG':
				$oConfig  = Config::getInstance();
				$sChannel = $this->__aNewMessages[ 2 ];
				if(empty($this->__aNewMessages[ 3 ])) {
					break;
				}
				$this->__aNewMessages[3] = substr( str_replace( array( chr( 10 ), chr( 13 ) ), '', $this->__aNewMessages[3] ), 1 );
				$sCommand = $this->__aNewMessages[3];
					
				unset( $this->__aNewMessages[0], $this->__aNewMessages[1], $this->__aNewMessages[2]);
				$sMessage = trim( implode( ' ', $this->__aNewMessages ) );
				
				Logger::cliLog( 'Message received from ' . $sFrom . ': ' . $sMessage, 'RECEIVED' );
				
				if( $oConfig->isMod( $sFrom ) ) {
					switch ( $sCommand ) { //List of commands the bot responds to from a user.
						case COMMAND_PREFIX.'reload':
							return IRCBot::SYSTEM_RELOAD;
							break;
						case COMMAND_PREFIX.'shutdown':
							$this->sendData( 'PRIVMSG '.$sChannel.' :', Translator::getInstance()->trans( 'QUIT_MSG' ) );
							$this->sendData( 'QUIT', Translator::getInstance()->trans( 'QUIT_MSG' ) );
							return IRCBot::SYSTEM_SHUTDOWN;
							break;
					}
				}
				
				$sResponseMessage = $this->getCommander()->processMessage( $sMessage, $sFrom );
				
				if( !empty( $sResponseMessage ) ) {
					$this->sendMessage( $sResponseMessage, $sChannel );
				} else {
					if( substr( $sCommand, 0, 1 ) == COMMAND_PREFIX) {
						$sCommand = substr( $sCommand, 1 );
						if( isset( $this->__aStaticMessages[ $sCommand ] ) ) {
							$this->sendMessage( sprintf( $this->__aStaticMessages[ $sCommand ], $sFrom ), $sChannel );
						}
						elseif( $sCommand == 'help' || $sCommand == 'commands' ) {
							$this->sendMessage(
								Translator::getInstance()->trans( 'AVAILABLE_COMMANDS' ) .
								': ' . implode( ', ', $this->getCommander()->getReadableCommands() ),
								$sChannel
							);
						}
					}
				}
				break;
		}
        return 0;
    }


    /**
     * Displays stuff to the broswer and sends data to the server.
     *
     * @param string $sCommand
     * @param null   $sMessage
     */
    public function sendData( $sCommand, $sMessage = null ) {
        $sPut = $sCommand;

        if ( !empty( $sMessage ) ) {
            $sPut .= ' ' . $sMessage;
        }
	
		Logger::cliLog( $sPut, 'SENDING' );
        fputs( $this->getSocket(), $sPut . PHP_EOL );
    }
	
	
	/**
	 * Sends a message to a channel.
	 *
	 * @param $sMessage
	 * @param $sChannel
	 */
	public function sendMessage( $sMessage, $sChannel ) {
		$this->sendData( 'PRIVMSG '.$sChannel.' :', $sMessage );
	}


    /**
     * Logs the bot in on the server.
     */
    public function login() {
        $this->sendData( 'PASS', $this->getOAuth() );
        $this->sendData( 'USER', $this->getNick() . ' twitch.tv ' . $this->getNick() . ' :' . $this->getNick() );
        $this->sendData( 'NICK', $this->getNick() );
    }


    /**
     * Joins given channels
     *
     * @param array|string $mChannel
     */
    public function joinChannels( $mChannel ) {
        if ( is_array( $mChannel ) ) {
            foreach ( $mChannel as $sChannel ) {
                $this->joinChannel($sChannel);
            }
        } else {
			$this->joinChannel($mChannel);
        }
    }
    
    private function joinChannel($sChannel) {
		$this->sendData( 'JOIN', $sChannel );
		// allows whispering the bot
		$this->sendData( 'CAP REQ :twitch.tv/commands' );
		// access to user list (join, leave)
		$this->sendData( 'CAP REQ :twitch.tv/membership' );
	}
}
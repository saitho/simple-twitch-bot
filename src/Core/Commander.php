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

class Commander {
    /** @var array */
    private $__aStaticCommands = array();

    /** @var array */
    private $__aCommands = array();
	/** @var array */
    private $__aConfig = array();

    /** @var array */
    private $__aReadableCommands = array();

    public function __construct() {
		$this->__aConfig = GeneralUtility::getConfig();
        GeneralUtility::cliLog( 'Setting up the Commander', 'SETUP' );
        $this->_setupFeatures();
    }
		
	/**
	 * Sets up all commands that are available.
	 */
	protected function _setupFeatures() {
		$di = new \DirectoryIterator(BASE_PATH.'src/Features/');
		foreach (new \IteratorIterator($di) as $filename => $file) {
			$dirName = $file->getFileName();
			if($dirName == '.' || $dirName == '..') {
				continue;
			}
			if(
				!array_key_exists('features.'.strtolower($dirName), $this->__aConfig) ||
				$this->__aConfig[ 'features.'.strtolower($dirName) ] != 1
			) {
				continue;
			}
			$aLang = glob( $file->getPathName().'/locale/*.xlf' );
			foreach($aLang AS $item) {
				preg_match('/\/locale\/(.*)\.xlf$/', $item, $result);
				Translator::getInstance()->addResource('xlf', $item, $result[1]);
			}
			
			$aCommands = glob( $file->getPathName().'/Commands/*.php' );
			foreach ( $aCommands as $sCommandClass ) {
				$sClassName = basename( $sCommandClass, '.php' );
				$this->addCommand( 'saitho\\TwitchBot\\Features\\'.$dirName.'\\Commands\\'.$sClassName );
			}
		}
		GeneralUtility::cliLog( 'Available commands: ' . implode( ', ', $this->__aReadableCommands ), 'SETUP' );
	}


    /**
     * Returns the command list
     *
     * @return array
     */
    public function getCommands() {
        return $this->__aCommands;
    }


    /**
     * Returns the static command list
     *
     * @return array
     */
    public function getStaticCommands() {
        return $this->__aStaticCommands;
    }


    /**
     * Returns the static command list
     *
     * @param string $sCommand
     *
     * @return string
     */
    public function getStaticCommand( $sCommand ) {
        return $this->__aStaticCommands[ $sCommand ];
    }


    /**
     * Returns the command list
     *
     * @return array
     */
    public function getReadableCommands() {
        return $this->__aReadableCommands;
    }


    /**
     * Returns the given command object
     *
     * @param string $sCommandName
     *
     * @return Command
     */
    public function getCommand( $sCommandName ) {
        return $this->__aCommands[ $sCommandName ][ 'oCommand' ];
    }


    /**
     * Adds a command to the command list
     *
     * @param string  $sCommandName
     */
    public function addCommand( $sCommandName ) {
        if( class_exists( $sCommandName ) ) {
			GeneralUtility::cliLog( 'Adding command: '.$sCommandName, 'SETUP' );

            /** @var Command $oCommand */
            $oCommand = new $sCommandName;

            $this->__aCommands[ $sCommandName ] = array(
                'sPattern'         => $oCommand->getCommandPattern(),
                'sReadablePattern' => $oCommand->getReadableCommandPattern(),
                'oCommand'         => $oCommand,
            );

            if( $oCommand->isPublic() ) {
                $this->__aReadableCommands[] = $oCommand->getReadableCommandPattern();
            }
        }
    }


    /**
     * Checks if the message matches a command and returns the output from the command, if some is returned.
     *
     * @param $sMessage
     * @param $sFrom
     *
     * @return string
     */
    public function processMessage( $sMessage, $sFrom ) {
        $sReturn = '';

        // Checking for static command
        $aMessage = explode( ' ', $sMessage );
		$command = substr($aMessage[0], 1);
		
        if( is_array( $aMessage ) && in_array( $command, array_keys( $this->getStaticCommands() ) ) ) {
			GeneralUtility::cliLog( 'Static command found: !' . $command, 'COMMANDER' );
            $sReturn = sprintf( $this->getStaticCommand($command), '@'.$sFrom );
        }

        // Checking for dynamic command
        foreach ( $this->getCommands() as $aCommand ) {
            if( preg_match( $aCommand[ 'sPattern' ], $sMessage ) ) {
				GeneralUtility::cliLog( 'Command found: ' . $aCommand[ 'sReadablePattern' ], 'COMMANDER' );

				/** @var Command $oCommand */
				$oCommand = $aCommand[ 'oCommand' ];
                $sReturn = $oCommand->execute( $sMessage, $sFrom );
                break;
            }
        }

        return $sReturn;
    }
}
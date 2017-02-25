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

use saitho\TwitchBot\Core\GeneralUtility;
use saitho\TwitchBot\Core\Translator;

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
class Queue extends Command {
	protected $_commandName = 'queue';
	protected $_arguments = [
		[
			'regex' => '[a-zA-Z]+'
		],
		[
			'regex' => '[a-zA-Z0-9_-]+',
			'optional' => true
		]
	];
	
	/**
	 * Queue holder
	 *
	 * @var array
	 */
	private $__aNicks = array();
	
	/**
	 * Can be used as hook when a command is *really* executed.
	 *
	 * @return void
	 */
	public function doExecute() {
		parent::doExecute();
		
		$aArgs = $this->getParameters();
		$sAction = $aArgs[ 1 ][ 0 ];
		$arg2 = $aArgs[ 2 ][ 0 ];
		
		switch ( $sAction ) {
			case 'join':
				if(empty($arg2)) {
					$arg2 = $this->getSender();
				}
				$this->addToQueue( $this->getSender(), $arg2 );
				break;
			case 'get':
				$amount = intval($arg2);
				if(empty($amount)) {
					$amount = 1;
				}
				$this->getFromQueue($amount);
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
	 * Adds a viewer to the queue.
	 *
	 * @param string $sSender
	 * @param string $sGameNick
	 */
	private function addToQueue( $sSender, $sGameNick ) {
		$iPos = $this->__isInQueue( $sSender );
		
		if ( $iPos === 0 ) {
			GeneralUtility::cliLog( "Adding {$sSender} ({$sGameNick}) to the queue.", 'QUEUE' );
			$this->__aNicks[] = array( 'twitch_nick' => $sSender, 'game_nick' => $sGameNick );
			
			$iPos = count( $this->__aNicks );
		}
		
		$this->setReturnMessage( Translator::getInstance()->trans( 'QUEUE_ADDED', array( $sSender, $iPos ) ) );
	}
	
	
	/**
	 * Checks if a user is already in the queue and returns the queue position.
	 *
	 * @param string $sNick
	 *
	 * @return int
	 */
	private function __isInQueue( $sNick ) {
		$iRet = 0;
		
		foreach ( $this->__aNicks as $iPos => $aViewer ) {
			if ( $aViewer[ 'twitch_nick' ] == $sNick ) {
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
	private function getFromQueue( $iAmount ) {
		if ( $this->config->isMod( $this->getSender() ) && count( $this->__aNicks ) && $iAmount > 0 ) {
			GeneralUtility::cliLog( "Fetching {$iAmount} from the queue.", 'QUEUE' );
			
			$aPicked = array_slice( $this->__aNicks, 0, $iAmount );
			$sPicked = '';
			
			// build return message
			foreach ( $aPicked as $aViewer ) {
				$sPicked .= $aViewer[ 'twitch_nick' ] . ' (' . $aViewer[ 'game_nick' ] . '), ';
				
				// remove viewer from queue
				$this->removeFromQueue( $aViewer[ 'twitch_nick' ] );
			}
			
			if ( !empty( $sPicked ) ) {
				GeneralUtility::cliLog( "Picked from queue: " . substr( $sPicked, 0, -2 ), 'QUEUE' );
				$this->setReturnMessage( Translator::getInstance()->trans( 'QUEUE_PICKED' ) . ' ' . substr( $sPicked, 0, -2 ) );
			}
		}
	}
	
	
	/**
	 * Clears the queue.
	 *
	 * @param string $sNick
	 */
	private function removeFromQueue( $sNick ) {
		$iPos = $this->__isInQueue( $sNick );
		
		if ( $iPos !== 0 ) {
			GeneralUtility::cliLog( "Removed {$sNick} from the queue.", 'QUEUE' );
			unset( $this->__aNicks[ ( $iPos - 1 ) ] );
		}
	}
	
	
	/**
	 * Lists all viewers that are in the queue.
	 */
	private function listAll() {
		$sNicks = '';
		
		// build return message
		foreach ( $this->__aNicks as $aViewer ) {
			$sNicks .= $aViewer[ 'twitch_nick' ] . ' (' . $aViewer[ 'game_nick' ] . '), ';
		}
		
		if ( !empty( $sNicks ) ) {
			GeneralUtility::cliLog( "Current queue: " . substr( $sNicks, 0, -2 ), 'QUEUE' );
			$this->setReturnMessage( Translator::getInstance()->trans( 'QUEUE_LIST' ) . ' ' . substr( $sNicks, 0, -2 ) );
		} else {
			GeneralUtility::cliLog( "Queue is empty.", 'QUEUE' );
			$this->setReturnMessage( Translator::getInstance()->trans( 'QUEUE_EMPTY' ) );
		}
	}
	
	
	/**
	 * Clears the queue.
	 */
	private function clearQueue() {
		if ( $this->config->isMod( $this->getSender() ) ) {
			GeneralUtility::cliLog( "Queue cleared.", 'QUEUE' );
			
			$this->__aNicks = array();
			$this->setReturnMessage( Translator::getInstance()->trans( 'QUEUE_CLEARED' ) );
		}
	}
}
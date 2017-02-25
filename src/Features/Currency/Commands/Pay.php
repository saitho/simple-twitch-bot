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

namespace saitho\TwitchBot\Features\Currency\Commands;
use saitho\TwitchBot\Core\Command;

class Pay extends Command {
	protected $_commandName = 'pay';
	protected $_commandDescription = 'Transfer gold to another user.';
	protected $_arguments = [
		[
			'regex' => '[a-zA-Z]+'
		],
		[
			'regex' => '[0-9]+'
		]
	];

    /**
     * Can be used as hook when a command is *really* executed.
     *
     * @return void
     */
    public function doExecute() {
		$aArgs = $this->getParameters();
		$userName = $aArgs[1][0];
		$amount = $aArgs[2][0];
		if($amount <= 0) {
			return;
		}
		
		$this->setReturnMessage( $this->_sSender.' transferred '.$amount.' Currency to '.$userName );
    }
}
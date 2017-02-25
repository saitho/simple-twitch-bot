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

class Gold extends Command {
	protected $_commandName = 'gold';
	protected $_commandDescription = 'Displays gold';

    /**
     * Can be used as hook when a command is *really* executed.
     *
     * @return void
     */
    public function doExecute() {
		$this->setReturnMessage( 'Not implemented yet.' );
    }
}
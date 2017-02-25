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

class Test extends Command {
    /**
     * Defines if a command is public. If a command is mod-only it wont be public.
     *
     * @var bool
     */
    protected $_blIsPublic = false;
	protected $_commandName = 'test';

    /**
     * Can be used as hook when a command is *really* executed.
     *
     * @return void
     */
    public function doExecute() {
        parent::doExecute();
	
		$this->setReturnMessage( 'Test successful!' );
    }
}
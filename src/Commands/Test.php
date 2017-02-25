<?php
namespace saitho\TwitchBot\Commands;
use saitho\TwitchBot\Core\Command;
/**
 * @link        https://github.com/Crease29/simple-twitch-bot
 * @author      Kai Neuwerth <github.com/Crease29>
 */

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
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

use saitho\TwitchBot\Core\Translator;

/**
 * Class Welcome_Command
 *
 * Says hello to the sender.
 *
 * Usage:
 * !welcome
 * !welcome <nick>
 */
class Welcome extends Command {
	protected $_commandName = 'welcome';
	protected $_arguments = [
		[
			'regex' => '[a-z0-9_-]+',
			'optional' => true
		]
	];
	
    /**
     * Says hello to the sender or first command parameter.
     */
    public function doExecute() {
        parent::doExecute();

        $sToBeGreeted = $this->getSender();
        $firstParam = $this->getParameter(1);

        // Checking if there is a name given that shall be greeted
        if(!empty($firstParam)) {
            $sToBeGreeted = $firstParam;
        }

        $this->setReturnMessage( Translator::getInstance()->trans( 'WELCOME', ['%name%' => $sToBeGreeted] ) );
    }
}
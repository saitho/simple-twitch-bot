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
use saitho\TwitchBot\Core\Config;

class Gold extends Command {
	protected $_commandName = 'gold';
	protected $_commandDescription = 'Displays gold';

    /**
     * Can be used as hook when a command is *really* executed.
     *
     * @return void
     */
    public function doExecute() {
	
		$userFile = BASE_PATH.'data/viewers/'.$this->_sSender.'.ini';
		if(file_exists($userFile)) {
			$userData = parse_ini_file($userFile, true);
		}else{
			touch($userFile);
			$userData = ['currency' => 0];
		}
    	
		$message = $userData['currency'].' Gold';
	
		$config = Config::getFeatureConfig('Currency');
    	if($config['bot']['whisper_gold']) {
			$message = '/w '.$this->_sSender.' '.$message;
		}
		$this->setReturnMessage($message);
    }
}
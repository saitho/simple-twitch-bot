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
namespace saitho\TwitchBot\Features\Currency\Daemon;

use saitho\TwitchBot\Core\Config;
use saitho\TwitchBot\Core\Daemon;

class CurrencyDaemon extends Daemon {
	const TWITCH_CHATTERS_URL = 'https://tmi.twitch.tv/group/user/%s/chatters';
	
	protected $appName = 'currencydaemon';
	protected $description = 'Fetches viewers every x minutes and adds currency';
	
	protected function process(Daemon $daemon, &$cnt) {
		$config = Config::getFeatureConfig('Currency');
		$interval = $config['payout']['interval'];
		$amount = $config['payout']['amount'];
		if(empty($interval)) {
			\System_Daemon::info('Payout interval is set to 0 - aborting execution');
			return Daemon::RETURN_ABORT;
		}elseif(empty($amount)) {
			\System_Daemon::info('Payout amount is set to 0 - aborting execution');
			return Daemon::RETURN_ABORT;
		}
		$mode = '';
		if(!\System_Daemon::isInBackground()) {
			$mode = 'non-';
		}
		$mode .= 'daemon mode';
		
		$channelName = Config::getInstance()->get('app.channelName');
		$sContent = file_get_contents(sprintf(CurrencyDaemon::TWITCH_CHATTERS_URL, $channelName));
		$aContent = json_decode($sContent);
		\System_Daemon::info('{appName} running in %s (Run #%s)', $mode, $cnt);
		if($cnt == 1) {
			// first run
			\System_Daemon::info('Skipping payout after initializing', $mode, $cnt);
		}else{
			foreach($aContent->chatters->viewers AS $viewer) {
				\System_Daemon::info('Giving '.$amount.' currency to '.$viewer.'.');
			}
			$iViewers = count($aContent->chatters->viewers);
			\System_Daemon::info('Paid out a total of '.$amount*$iViewers.' currency to '.$iViewers.' viewers. Next payout in '.$interval.' seconds.');
			
		}
				
		\System_Daemon::info('Next payout in '.$interval.' seconds.');
		\System_Daemon::iterate($interval);
		return Daemon::RETURN_SUCCESS;
	}
}
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
	protected  $loop_interval = 1;
	const TWITCH_CHATTERS_URL = 'https://tmi.twitch.tv/group/user/%s/chatters';
	protected $appName = 'currency';
	
	private $cnt = 0;
	private $config = [];
	
	public function setup() {
		$this->config = Config::getFeatureConfig('Currency');
	}
	
	public function execute() {
		$this->cnt++;
		$interval = $this->config['payout']['interval'];
		$amount = $this->config['payout']['amount'];
		if(empty($interval)) {
			$this->error('Payout interval is set to 0 - aborting execution.');
		}elseif(empty($amount)) {
			$this->error('Payout amount is set to 0 - aborting execution.');
		}
		$this->loop_interval($interval*60);
		$mode = '';
		if(!$this->is_daemon()) {
			$mode = 'non-';
		}
		$mode .= 'daemon mode';
		
		$channelName = Config::getInstance()->get('app.channelName');
		$sContent = file_get_contents(sprintf(CurrencyDaemon::TWITCH_CHATTERS_URL, $channelName));
		$aContent = json_decode($sContent);
		$this->log('{appName} running in '.$mode.' (Run #'.$this->cnt.')', 'INFO');
		if($this->cnt == 1 && !$this->config['payout']['interval_pay_on_startup']) {
			// first run
			$this->log('Skipping payout after initializing', 'INFO');
		}else{
			foreach($aContent->chatters->viewers AS $viewer) {
				$this->log('Giving '.$amount.' currency to '.$viewer.'.', 'INFO');
				
				$userFile = BASE_PATH.'data/viewers/'.$viewer.'.ini';
				
				$userData = ['currency' => 0, 'viewTime' => 0, 'lastOn' => ''];
				if(file_exists($userFile)) {
					$userData = array_merge($userData, parse_ini_file($userFile, true));
				}else{
					touch($userFile);
				}
				$userData['currency'] += $amount;
				$userData['viewTime'] += $interval;
				$userData['lastOn'] = date('Y-m-d, H:i:s');
				$userDataText = '';
				foreach($userData AS $k => $v) {
					$userDataText .= $k.' = '.$v.PHP_EOL;
				}
				
				$handle = fopen($userFile, 'w+') or die('Unable to open file '.$userFile);
				fwrite($handle, $userDataText);
				fclose($handle);
			}
			$iViewers = count($aContent->chatters->viewers);
			$this->log('Paid out a total of '.$amount*$iViewers.' currency to '.$iViewers.' viewers.', 'INFO');
		}
		$this->log('Next payout in '.$interval.' minutes.', 'INFO');
	}
	
	
	protected $description = 'Fetches viewers every x minutes and adds currency';
	
	protected function process(Daemon $daemon, &$cnt) {
	}
}
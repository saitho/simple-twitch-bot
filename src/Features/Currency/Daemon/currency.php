#!/usr/bin/php -q
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
define('BASE_PATH', '../../../../');
require_once BASE_PATH.'vendor/autoload.php';

const TWITCH_CHATTERS_URL = 'https://tmi.twitch.tv/group/user/%s/chatters';

$config = \saitho\TwitchBot\Core\Config::getFeatureConfig('Currency');

// Allowed arguments & their defaults
$runmode = array(
	'no-daemon' => false,
	'help' => false,
	'write-initd' => false,
);

// Scan command line attributes for allowed arguments
foreach ($argv as $k=>$arg) {
	if (substr($arg, 0, 2) == '--' && isset($runmode[substr($arg, 2)])) {
		$runmode[substr($arg, 2)] = true;
	}
}
var_dump($runmode);

// Help mode. Shows allowed argumentents and quit directly
if ($runmode['help'] == true) {
	echo 'Usage: '.$argv[0].' [runmode]' . "\n";
	echo 'Available runmodes:' . "\n";
	foreach ($runmode as $runmod=>$val) {
		echo ' --'.$runmod . "\n";
	}
	die();
}

// Make it possible to test in source directory
// This is for PEAR developers only
ini_set('include_path', ini_get('include_path').':..');

// Include Class
error_reporting(E_STRICT);

// Setup
$options = array(
	'appName' => 'twitchbot_currency',
	'appDir' => dirname(__FILE__),
	'appDescription' => 'Fetches viewers every x minutes and adds currency',
	'authorName' => 'Mario Lubenka',
	'authorEmail' => 'mario.lubenka@googlemail.com',
	'sysMaxExecutionTime' => 0,
	'sysMaxInputTime' => 0,
	'sysMemoryLimit' => '1024M',
	'appRunAsGID' => 1000,
	'appRunAsUID' => 1000,
);

System_Daemon::setOptions($options);

// This program can also be run in the forground with runmode --no-daemon
if (!$runmode['no-daemon']) {
	// Spawn Daemon
	System_Daemon::start();
}

// With the runmode --write-initd, this program can automatically write a
// system startup file called: 'init.d'
// This will make sure your daemon will be started on reboot
if (!$runmode['write-initd']) {
	System_Daemon::info('not writing an init.d script this time');
} else {
	if (($initd_location = System_Daemon::writeAutoRun()) === false) {
		System_Daemon::notice('unable to write init.d script');
	} else {
		System_Daemon::info(
			'sucessfully written startup script: %s',
			$initd_location
		);
	}
}

// Run your code
// Here comes your own actual code

// This variable gives your own code the ability to breakdown the daemon:
$runningOkay = true;

// This variable keeps track of how many 'runs' or 'loops' your daemon has
// done so far. For example purposes, we're quitting on 3.
$cnt = 1;
while (!System_Daemon::isDying() && $runningOkay) {
    $interval = $config['payout']['interval'];
    $amount = $config['payout']['amount'];
	if(empty($interval)) {
		System_Daemon::info('Payout interval is set to 0 - aborting execution');
		break;
	}elseif(empty($amount)) {
		System_Daemon::info('Payout amount is set to 0 - aborting execution');
		break;
	}
    $mode = '';
    if(!System_Daemon::isInBackground()) {
        $mode = 'non-';
    }
	$mode .= 'daemon mode';
	
	$channelName = \saitho\TwitchBot\Core\Config::getInstance()->get('app.channelName');
    $sContent = file_get_contents(sprintf(TWITCH_CHATTERS_URL, $channelName));
    $aContent = json_decode($sContent);
	System_Daemon::info('[START] {appName} running in %s (Run #%s)', $mode, $cnt);
    foreach($aContent->chatters->viewers AS $viewer) {
		System_Daemon::info('   [PAYOUT] Giving '.$amount.' currency to '.$viewer.'.');
    }
    $iViewers = count($aContent->chatters->viewers);
	System_Daemon::info('[END] Paid out a total of '.$amount*$iViewers.' currency to '.$iViewers.' viewers.'.
	'Next payout in '.$interval.' seconds.');
    
	$runningOkay = true;
	if (!$runningOkay) {
		System_Daemon::err('parseLog() produced an error, so this will be my last run');
	}
	
	System_Daemon::iterate($interval);
	$cnt++;
}

// Shut down the daemon nicely
// This is ignored if the class is actually running in the foreground
System_Daemon::stop();
?>
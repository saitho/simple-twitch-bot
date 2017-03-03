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
namespace saitho\TwitchBot\Core;

abstract class Daemon {
	
	const RETURN_SUCCESS = 0;
	const RETURN_ABORT = 1;
	
	protected $appName = '';
	protected $description = '';
	
	protected $commander = null;
	public function setCommander(Commander $commander) {
		$this->commander = $commander;
	}
	
	public function getCLIOptions() {
		global $argv;
		$options = [];
		foreach ($argv as $k => $arg) {
			if(substr($arg, 0, 2) == '--') {
				continue;
			}
			$options[] = $arg;
		}
		return $options;
	}
	
	public function getCLIArguments() {
		global $argv;
		$arguments = [];
		foreach ($argv as $k => $arg) {
			if(substr($arg, 0, 2) != '--') {
				continue;
			}
			$arguments[] = substr($arg, 2);
		}
		return $arguments;
	}
	
	private $runmode = [];
	private $options = [];
	public function __construct() {
		if(empty($this->appName)) {
			throw new \Exception('Missing appName for Daemon class '.get_called_class());
		}
		$this->runmode = array(
			'stop' => false,
			'no-daemon' => false,
			'help' => false,
			'write-initd' => false,
		);
		
		foreach ($this->getCLIArguments() AS $argument) {
			$this->runmode[$argument] = true;
		}
		
		$this->options = [
			'appName' => $this->appName,
			'appDir' => dirname(__FILE__),
			'appDescription' => $this->description,
			'logLocation' => BASE_PATH.'logs/daemon_'.$this->appName.'.log',
			'sysMaxExecutionTime' => 0,
			'sysMaxInputTime' => 0,
			'sysMemoryLimit' => '1024M',
			'appRunAsGID' => 1000,
			'appRunAsUID' => 1000,
		];
	}
	abstract protected function process(Daemon $daemon, &$cnt);
		
	final public function call() {
		if ($this->runmode['help'] == true) {
			echo 'Usage: sudo '.__FILE__.' [runmode]' . "\n";
			echo 'Available runmodes:' . "\n";
			foreach ($this->runmode as $runmod=>$val) {
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
		\System_Daemon::setOptions($this->options);
		if ($this->runmode['stop'] == true) {
			\System_Daemon::stopRunning();
			die();
		}
		
		// This program can also be run in the forground with runmode --no-daemon
		if (!$this->runmode['no-daemon']) {
			// Spawn Daemon
			\System_Daemon::start();
		}
		
		// With the runmode --write-initd, this program can automatically write a
		// system startup file called: 'init.d'
		// This will make sure your daemon will be started on reboot
		if (!$this->runmode['write-initd']) {
			\System_Daemon::info('not writing an init.d script this time');
		} else {
			if (($initd_location = \System_Daemon::writeAutoRun()) === false) {
				\System_Daemon::notice('unable to write init.d script');
			} else {
				\System_Daemon::info(
					'sucessfully written startup script: %s',
					$initd_location
				);
			}
		}
		
		// Run your code
		// Here comes your own actual code
		
		// This variable keeps track of how many 'runs' or 'loops' your daemon has
		// done so far. For example purposes, we're quitting on 3.
		$cnt = 1;
		while (!\System_Daemon::isDying()) {
			$runningOkay = $this->process($this, $cnt);
			$cnt++;
			if($runningOkay != Daemon::RETURN_SUCCESS) {
				\System_Daemon::err('An error occured. Stopping execution.');
				break;
			}
		}
		
		// Shut down the daemon nicely
		// This is ignored if the class is actually running in the foreground
		\System_Daemon::stop();
	}
}
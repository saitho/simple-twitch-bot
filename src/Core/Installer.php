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
use Composer\IO\IOInterface;
use Composer\Script\Event;

define('CONTEXT', '');
require_once(realpath( dirname( __FILE__ ) . '/../' ).'/_init.php');

class Installer {
	private static function createConfigFromExample(IOInterface $io, $configPath, $exampleConfigPath) {
		$exampleConfig = parse_ini_file($exampleConfigPath, true);
		$configText = '';
		// load example config
		foreach($exampleConfig AS $groupName => $config) {
			$configText .= '['.$groupName.']'.PHP_EOL;
			foreach($config AS $name => $defaultValue) {
				// Ask for value - add default value in [] if available
				$result = $io->ask($name.($defaultValue == '' ? ' ['.$defaultValue.']' : '').': ');
				$configValue = $defaultValue;
				if(!empty($result)) {
					$configValue = $result;
				}
				$configText .= $name.' = \''.$configValue.'\''.PHP_EOL;
			}
			$configText .= PHP_EOL;
		}
		
		$configFile = fopen($configPath, 'w') or die('Unable to open file!');
		fwrite($configFile, $configText);
		fclose($configFile);
	}
	
	private static function checkFeatureBinaries(IOInterface $io, $featureName) {
		$binDir = BASE_PATH.'src/Features/'.ucfirst($featureName).'/bin/';
		if(!is_dir($binDir)) {
			return;
		}
		$binFiles = scandir($binDir);
		foreach($binFiles AS $binFile) {
			if($binFile == '.' || $binFile == '..') {
				continue;
			}
			chmod($binDir.$binFile, 0770);
		}
	}
	
	public static function install(Event $event) {
		$io = $event->getIO();
		$configFilePath = Config::$configDir.'config.ini';
		
		if(!file_exists($configFilePath)) {
			// Create config file if it doesn't already exist
			self::createConfigFromExample($io, $configFilePath, Config::$systemExampleConfigPath);
			// Reinitialize config after file was created
			Config::initialize();
		}
		
		$features = Config::getInstance()->get('features');
		if($features) {
			foreach($features AS $featureName => $featureStatus) {
				if(!intval($featureStatus)) {
					continue;
				}
				$featureConfigPath = Config::$configDir.'/config.'.$featureName.'.ini';
				$exampleFeatureConfigPath = sprintf(Config::$featureExampleConfigPath, $featureName);
				if(!file_exists($featureConfigPath)) {
					// Create feature config file if it doesn't already exist
					$io->write('Configuration for Feature "'.ucfirst($featureName).'"');
					self::createConfigFromExample($io, $featureConfigPath, $exampleFeatureConfigPath);
				}
				
				// Check bin folder permissions of Features
				self::checkFeatureBinaries($io, $featureName);
			}
		}
	}
}
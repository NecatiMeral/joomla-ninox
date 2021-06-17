<?php
/*
 * @license MIT License (https://github.com/NecatiMeral/joomla-ninox/blob/main/LICENSE)
 * @author  Necati Meral (necati_meral@yahoo.de)
 * @url     https://github.com/NecatiMeral/joomla-ninox
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$lang = JFactory::getLanguage();
$base_dir = JPATH_SITE.'/components/com_ninox';
$lang->load('com_ninox', $base_dir, 'en-GB', true);
$lang->load('com_ninox', $base_dir, NULL, true);

class NinoxHelper {
	protected static $_params;
	
	static function getParams() {
		// Test if the config is already loaded.
		if (!self::$_params) {
			JTable::addIncludePath(JPATH_SITE.'/administrator/components/com_ninox/tables');
			$jconfig = JFactory::getConfig();
			jimport( 'joomla.html.parameter' );
			$config = JTable::getInstance( 'Config' , 'NinoxTable' );
			$config->load( 'config' );
			self::$_params = new JRegistry;
			// Bind the user saved configuration.
			$row = new JRegistry;
			$row->loadString($config->params);
			self::$_params->merge( $row );
		}
		return self::$_params;
	}

	static function getTeams() {
		$config = self::getParams();
		if (!$config->get('apikey')) {
			return;
		}

		$ch = curl_init("https://api.ninoxdb.de/v1/teams");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json', 
			'Accept: application/json',
			'Authorization: Bearer '.$config->get('apikey'))
		);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		if(curl_error($ch)){
			curl_close($ch);
			return null;
		}
		$list = json_decode($response, true);

		curl_close($ch);
		return $list;
	}

	static function getDatabases() {
		$config = self::getParams();
		$teamId = $config->get('team_id');
		if (!$config->get('apikey')) {
			return;
		}
		if (!$teamId) {
			return;
		}

		$ch = curl_init("https://api.ninoxdb.de/v1/teams/$teamId/databases");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json', 
			'Accept: application/json',
			'Authorization: Bearer '.$config->get('apikey'))
		);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		if(curl_error($ch)){
			curl_close($ch);
			return null;
		}
		$list = json_decode($response, true);

		curl_close($ch);
		return $list;
	}

	static function getTables() {
		$config = self::getParams();
		$teamId = $config->get('team_id');
		$databaseId = $config->get('database_id');
		if (!$config->get('apikey')) {
			return;
		}
		if (!$teamId || !$databaseId) {
			return;
		}

		$ch = curl_init("https://api.ninoxdb.de/v1/teams/$teamId/databases/$databaseId/tables");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json', 
			'Accept: application/json',
			'Authorization: Bearer '.$config->get('apikey'))
		);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		if(curl_error($ch)){
			curl_close($ch);
			return null;
		}
		$list = json_decode($response, true);

		curl_close($ch);
		return $list;
	}
}
?>
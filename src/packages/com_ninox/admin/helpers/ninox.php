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
	
	static function addContacts($users) {
		$config = self::getParams();
		$teamId = $config->get('team_id');
		$databaseId = $config->get('database_id');
		$tableId = $config->get('table_id');
		if (!$config->get('apikey')) {
			return;
		}
		if (!$teamId || !$databaseId || !$tableId) {
			return;
		}

		$contacts = array_map(function($user) {
			$contact = new stdClass();
			$contact->fields = [
				"Vorname" => $user['first_name'],
				"Nachname" => $user['last_name'],
				"E-Mail" => $user['email'],
				"Website-ID" => $user['id']
			];
			return $contact;
		}, $users);
		
		$ch = curl_init("https://api.ninoxdb.de/v1/teams/$teamId/databases/$databaseId/tables/$tableId/records");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $config->get('apikey')));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($contacts));
		// change this when we know we can use our bundled CA bundle!
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = json_decode(curl_exec($ch), true);
		$result['code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if($result['code'] >= 200 && $result['code'] < 300)
		{
			$db = JFactory::getDBO();
			for ($i = 0; $i < count($users); ++$i) {
				$user = $users[$i];
				$contact = $result[$i];
				
				// TODO: Fix select N+1 issue
				$query = $db
					->getQuery(true)
					->select($db->quoteName('id'))
					->from($db->quoteName('#__ninox_user_map'))
					->where($db->quoteName('id') . ' = ' . $user['id']);

				$db->setQuery($query);
				$mapItem = $db->loadObject();
			
				// Add user-id to ninox key to map-table
				if(!$mapItem)
				{
					$values = array(
						$user['id'],
						$contact['id']
					);

					$query = $db
						->getQuery(true)
						->insert($db->quoteName('#__ninox_user_map'))
						->columns($db->quoteName(array('id', 'ninoxId')))
						->values(implode(',', $values));
						
					$db->setQuery($query);
					$db->execute();
				}
			}
		}
		
		return $result;
	}
	
	static function loadScripts() {
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		JHtml::_('jquery.framework');
		JHtml::_('bootstrap.framework');
		JHtml::_('bootstrap.tooltip');
		
		if ($app->isAdmin()) {
			$document->addScript(JURI::base(true).'/components/com_ninox/assets/javascript/ninox.js');
			$document->addStyleSheet(JURI::base(true).'/components/com_ninox/assets/css/ninox.css');
		}

		$js = "
		var ninox = {
			livesite: '" . JURI::root() . "',
			succeedTemplate: '" . JText::_('COM_NINOX_EXPORT_SUCCEED') . "'
		};";
		$document->addScriptDeclaration($js);
	}
}
?>
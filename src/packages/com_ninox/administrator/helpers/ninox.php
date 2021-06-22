<?php
/*
 * @license MIT License (https://github.com/NecatiMeral/joomla-ninox/blob/main/LICENSE)
 * @author  Necati Meral (necati_meral@yahoo.de)
 * @url     https://github.com/NecatiMeral/joomla-ninox
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

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

	static function deleteContacts($arrayOfUserIds) {
		$config = self::getParams();
		$teamId = $config->get('team_id');
		$databaseId = $config->get('database_id');
		$tableId = $config->get('table_id');
		if (!$config->get('apikey')
			|| !$teamId || !$databaseId || !$tableId) 
		{
			return;
		}
		
		$db = JFactory::getDBO();
		$query = $db
			->getQuery(true)
			->select($db->quoteName('ninoxId'))
			->from($db->quoteName('#__ninox_user_map'))
			->where($db->quoteName('id') . ' IN ( ' . implode(', ', $arrayOfUserIds) . ')');

		$db->setQuery($query);
		$ninoxIds = $db->loadColumn();
		$return = array();
		
		foreach ($ninoxIds as $ninoxId) 
		{
			$ch = curl_init("https://api.ninoxdb.de/v1/teams/$teamId/databases/$databaseId/tables/$tableId/records/$ninoxId");

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $config->get('apikey')));
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			curl_exec($ch);

			$return['code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			if($return['code'] != 200)
			{
				return $return;
			}
		}

		$query = $db
			->getQuery(true)
			->delete($db->quoteName('#__ninox_user_map'))
			->where($db->quoteName('id') . ' IN ( ' . implode(', ', $arrayOfUserIds) . ')');
		$db->setQuery($query);
		$db->execute();

		return $return;
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
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  string
	 *
	 * @return void
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			Text::_('COM_NINOX_TITLE_CONFIGURATION'),
			'index.php?option=com_ninox&view=configuration',
			$vName == 'configuration'
		);
		
		JHtmlSidebar::addEntry(
			Text::_('COM_NINOX_TITLE_MAPPINGS'),
			'index.php?option=com_ninox&view=mappings',
			$vName == 'mappings'
		);
	}

	/**
	 * Gets the files attached to an item
	 *
	 * @param   int     $pk     The item's id
	 *
	 * @param   string  $table  The table's name
	 *
	 * @param   string  $field  The field's name
	 *
	 * @return  array  The files
	 */
	public static function getFiles($pk, $table, $field)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select($field)
			->from($table)
			->where('id = ' . (int) $pk);

		$db->setQuery($query);

		return explode(',', $db->loadResult());
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return    JObject
	 *
	 * @since    1.6
	 */
	public static function getActions()
	{
		$user   = Factory::getUser();
		$result = new JObject;

		$assetName = 'com_ninox';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}
?>
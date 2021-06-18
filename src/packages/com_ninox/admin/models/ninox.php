<?php
/*
 * @license MIT License (https://github.com/NecatiMeral/joomla-ninox/blob/main/LICENSE)
 * @author  Necati Meral (necati_meral@yahoo.de)
 * @url     https://github.com/NecatiMeral/joomla-ninox
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
class NinoxModelNinox extends JModelLegacy {
	protected $users_table;
	protected $include_blocked;
	protected $include_unconfirmed;
	protected $registered_after;
	protected $last_login_after;

	public function __construct($config = array()) {
		$this->users_table = '#__users';
		$this->include_blocked = 0;
		$this->include_unconfirmed = 0;
		$this->registered_after = '';
		$this->last_login_after = '';
		parent::__construct($config);
	}

	public function save($app) {
		$db = JFactory::getDBO();
		$input = $app->input;
		$task = $input->get('task', '');
		$postData = $_POST;
		unset($postData['option']);
		unset($postData['view']);
		unset($postData['task']);

		$db->setQuery( "SELECT COUNT(*) FROM #__ninox_config WHERE `name`='config'" );
		$count = (int)$db->loadResult();
		if (!$count) {
			$db->setQuery( "INSERT INTO #__ninox_config (`name`) VALUES ('config')" );
			$db->query();
		}
		$config = $this->getTable('Config', 'NinoxTable');
		$config->load('config');
		$params = new JRegistry;
		$params->loadString($config->params);
		
		foreach ($postData as $key=>$value) {
			if ($key != 'task' && $key != 'option' && $key != 'view') {
				$params->set($key, $value);
			}
		}
		$result = $params->toString();
		$config->params	= $result;
		$db->setQuery( "UPDATE #__ninox_config SET `params`='{$config->params}' WHERE `name`='config'" );
		$db->query();
		
		$app->enqueueMessage(JText::_('COM_NINOX_CONFIGURATION_SAVED'));
		$app->redirect('index.php?option=com_ninox');
	}
		
	public function export($input) {
		$db = $this->getDbo();
		$this->include_blocked = $input->get('include_blocked', 0, 'INT');
		$this->include_unconfirmed = $input->get('include_unconfirmed', 0, 'INT');
		$registered_after = $input->get('registered_after', '', 'STR');
		$last_login_after = $input->get('last_login_after', '', 'STR');
		$this->registered_after = ($registered_after) ? JFactory::getDate($registered_after)->toSQL() : '0000-00-00 00:00:00';
		$this->last_login_after = ($last_login_after) ? JFactory::getDate($last_login_after)->toSQL() : '0000-00-00 00:00:00';
		
		//init users table
		$db->setQuery( "SHOW COLUMNS FROM `{$this->users_table}` LIKE 'ninox'" );
		$result = $db->loadObjectList();
		if (!count($result)) {
			$db->setQuery( "ALTER TABLE `{$this->users_table}` ADD COLUMN `ninox` tinyint(1) NOT NULL default '0'" );
			$db->query();
		}
		$return = array('total'=>0, 'exported'=>0, 'exported_percent'=>0, 'exported_total'=>0, 'error'=>'', 'completed'=>0);

		$query = $db->getQuery(true);
		$query
			->select("COUNT(*)")
			->from($db->quoteName($this->users_table));
		$query = $this->filterExportQuery($query);
		$db->setQuery($query);
		$total = (int)$db->loadResult();
		$return['total'] = $total;
		
		$query = $db->getQuery(true);
		$query
			->select($db->quoteName(array('id', 'name', 'email')))
			->from($db->quoteName($this->users_table))
			->where("ninox = '0'")
			->order("id ASC")
			->setLimit("500");
		$query = $this->filterExportQuery($query);
		$db->setQuery($query);
		$users = $db->loadObjectList();
		
		$contacts = array();
		$_IDs = array();
		foreach ($users as $user) {
			$parts = explode(" ", $user->name);
			$last_name = array_pop($parts);
			$first_name = implode(" ", $parts);
			$contacts[] = array('id' => $user->id, 'first_name' => $first_name, 'last_name' => $last_name, 'email' => $user->email);
			$_IDs[] = "`id`={$user->id}";
		}

		$return['exported'] = count($_IDs);
		if(count($_IDs) > 0)
		{
			$response = NinoxHelper::addContacts($contacts);
			if ($response['code'] >= 200 && $response['code'] < 300) {
				$_OR = implode(' OR ', $_IDs);
				$db->setQuery( "UPDATE {$this->users_table} SET `ninox`=1 WHERE ({$_OR})" );
				$db->query();
			}
			else {
				$return['code'] = $response['code'];
				$return['error'] = JText::_('COM_NINOX_EXPORT_FAILED');
			}
		}

		$query = $db->getQuery(true);
		$query
			->select("COUNT(*)")
			->from($db->quoteName($this->users_table))
			->where("ninox = '1'");
		$query = $this->filterExportQuery($query);
		$db->setQuery($query);
		$exported_total = (int)$db->loadResult();
		$return['exported_total'] = $exported_total;
		$return['exported_percent'] = ($total) ? round($exported_total / $total * 100) : 100;
		
		if ($exported_total == $total || !$return['exported']) {
			$return['completed'] = 1;
		}
		echo json_encode($return);
	}
	
	private function filterExportQuery($query) {
		if (!$this->include_blocked)
			$query->where("block = '0'");
		if (!$this->include_unconfirmed)
			$query->where("activation = ''");
		if ($this->registered_after)
			$query->where("registerDate >= '{$this->registered_after}'");
		if ($this->last_login_after)
			$query->where("lastvisitDate >= '{$this->last_login_after}'");
		return $query;
	}
}
?>
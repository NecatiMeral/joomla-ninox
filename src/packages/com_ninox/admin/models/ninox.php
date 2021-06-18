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

		$return = array(
			'total' => 0, 
			'exported' => 0, 
			'exported_percent' => 0, 
			'exported_total' => 0, 
			'error' => '', 
			'completed' => 0
		);

		$query = $db
			->getQuery(true)
			->select("COUNT(*)")
			->from($db->quoteName($this->users_table, 'a'));
		$query = $this->filterExportQuery($db, $query);
		$db->setQuery($query);
		$total = (int)$db->loadResult();
		$return['total'] = $total;
		
		$query = $db
			->getQuery(true)
			->select(array('a.id', 'a.name', 'a.email'))
			->from($db->quoteName($this->users_table, 'a'))
			->join('LEFT', $db->quoteName('#__ninox_user_map', 'b') . ' ON ' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.id'))
			->where($db->quoteName('b.id') . ' IS NULL')
			->order($db->quoteName('a.id') . ' ASC')
			->setLimit("500");
		$query = $this->filterExportQuery($db, $query);
		$db->setQuery($query);
		$users = $db->loadObjectList();
		
		$userDtos = array();
		foreach ($users as $user) {
			$parts = explode(" ", $user->name);
			$last_name = array_pop($parts);
			$first_name = implode(" ", $parts);
			$userDtos[] = array('id' => $user->id, 'first_name' => $first_name, 'last_name' => $last_name, 'email' => $user->email);
		}

		$return['exported'] = count($userDtos);
		if(count($userDtos) > 0)
		{
			$response = NinoxHelper::addContacts($userDtos);
			if ($response['code'] >= 200 && $response['code'] < 300) {
			}
			else {
				$return['code'] = $response['code'];
				$return['error'] = JText::_('COM_NINOX_EXPORT_FAILED');
			}
		}
		
		$query = $db
			->getQuery(true)
			->select('COUNT(*)')
			->from($db->quoteName($this->users_table, 'a'))
			->join('LEFT', $db->quoteName('#__ninox_user_map', 'b') . ' ON ' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.id'))
			->where($db->quoteName('b.id') . ' IS NOT NULL');
		$query = $this->filterExportQuery($db, $query);
		$db->setQuery($query);
		$exported_total = (int)$db->loadResult();
		$return['exported_total'] = $exported_total;
		$return['exported_percent'] = ($total) ? round($exported_total / $total * 100) : 100;
		
		if ($exported_total == $total || !$return['exported']) {
			$return['completed'] = 1;
		}
		echo json_encode($return);
	}
	
	private function filterExportQuery($db, $query) {
		if (!$this->include_blocked)
		{
			$query->where($db->quoteName('a.block') . ' = 0');
		}
		if (!$this->include_unconfirmed)
		{
			$query->where($db->quoteName('a.activation') . " = ''");
		}
		if ($this->registered_after)
		{
			$query->where($db->quoteName('a.registerDate') . '>= ' . $db->quote($this->registered_after));
		}
		if ($this->last_login_after)
		{
			$query->where($db->quoteName('a.lastvisitDate') . ' >= ' . $db->quote($this->last_login_after));
		}
		return $query;
	}
}
?>
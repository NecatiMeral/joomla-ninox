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
		
		$app->enqueueMessage('Configuration Saved.');
		$app->redirect('index.php?option=com_ninox');
	}
}
?>
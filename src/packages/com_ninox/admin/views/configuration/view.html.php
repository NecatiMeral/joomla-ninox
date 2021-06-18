<?php
/*
 * @license MIT License (https://github.com/NecatiMeral/joomla-ninox/blob/main/LICENSE)
 * @author  Necati Meral (necati_meral@yahoo.de)
 * @url     https://github.com/NecatiMeral/joomla-ninox
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class NinoxViewConfiguration extends JViewLegacy {
    function display($tpl=NULL) {
		$app = JFactory::getApplication();
		$input = $app->input;
		$task = $input->get('task', '');
		$model = $this->getModel();
		
		if (!$task) {
			$this->defaultView($tpl, $model);
			return;
		}
		
		switch ($task) {
			case 'apply':
			case 'save':
				$model->save($app);
				break;
		}
    }
	
	function defaultView($tpl, $model) {
		$config	= NinoxHelper::getParams();

		// Teams
		$teams = NinoxHelper::getTeams();
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('COM_NINOX_SELECT_TEAM'));
		foreach($teams as $team) {
			$options[] = JHtml::_('select.option', $team['id'], $team['name']);
		}

		$lists['team_id'] = JHtml::_('select.genericlist', $options, 'team_id', ' class="inputbox" ', 'value', 'text',
			$config['team_id']);
		
		// Databases
		$databases = NinoxHelper::getDatabases();
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('COM_NINOX_SELECT_DATABASE'));
		foreach($databases as $database) {
			$options[] = JHtml::_('select.option', $database['id'], $database['name']);
		}

		$lists['database_id'] = JHtml::_('select.genericlist', $options, 'database_id', ' class="inputbox" ', 'value', 'text',
			$config['database_id']);
		
		// Tables
		$tables = NinoxHelper::getTables();
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('COM_NINOX_SELECT_TABLE'));
		foreach($tables as $table) {
			$options[] = JHtml::_('select.option', $table['id'], $table['name']);
		}

		$lists['table_id'] = JHtml::_('select.genericlist', $options, 'table_id', ' class="inputbox" ', 'value', 'text',
			$config['table_id']);

		$this->config = $config;
		$this->lists = $lists;
		$this->addToolBar();

		parent::display($tpl);
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar($total=null) {
		JToolBarHelper::title('Ninox Auto-Sync');
		JToolBarHelper::apply('apply');
	}

	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() {
		$document = JFactory::getDocument();
		$document->setTitle('Ninox Auto-Sync - Setup');
	}
}
?>

<?php
/**
 * @license MIT License (https://github.com/NecatiMeral/joomla-ninox/blob/main/LICENSE)
 * @author  Necati Meral (necati_meral@yahoo.de)
 * @url     https://github.com/NecatiMeral/joomla-ninox
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

/**
 * View to configure
 *
 * @since  1.6
 */
class NinoxViewConfiguration extends \Joomla\CMS\MVC\View\HtmlView
{
	protected $config;

	protected $lists;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

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

		NinoxHelper::addSubmenu('configuration');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
    }

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function addToolBar() 
	{
		JToolBarHelper::title('Ninox Auto-Sync');
		JToolBarHelper::apply('apply');

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_ninox&view=configuration');
	}
}
?>

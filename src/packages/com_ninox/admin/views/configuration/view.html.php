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

		$this->assign( 'config' , $config );
		parent::display($tpl);
		$this->addToolBar();
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

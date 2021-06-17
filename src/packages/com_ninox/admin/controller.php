<?php
/*
 * @license MIT License (https://github.com/NecatiMeral/joomla-ninox/blob/main/LICENSE)
 * @author  Necati Meral (necati_meral@yahoo.de)
 * @url     https://github.com/NecatiMeral/joomla-ninox
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
class NinoxController extends JControllerLegacy {
	
	protected $default_view = 'configuration';

	public function __construct() {
		parent::__construct();
	}
	
	function display($cachable = false, $urlparams = array()) {
		$input = JFactory::getApplication()->input;
		$viewName = $input->get('view', $this->default_view);
        $layoutName = $input->get('layout', 'default');
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		
		$view = $this->getView($viewName, $viewType);
		$model = $this->getModel($viewName);
        if ($model)
		{
			$view->setModel($model, true);
		}
        $view->setLayout($layoutName);
        $view->display();
		
		return $this;
	}
}
?>
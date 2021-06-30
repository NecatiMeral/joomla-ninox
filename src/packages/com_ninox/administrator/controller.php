<?php
/*
 * @license MIT License (https://github.com/NecatiMeral/joomla-ninox/blob/main/LICENSE)
 * @author  Necati Meral (necati_meral@yahoo.de)
 * @url     https://github.com/NecatiMeral/joomla-ninox
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

use \Joomla\CMS\Factory;
 
/**
 * Class NinoxController
 *
 * @since  1.6
 */
class NinoxController extends \Joomla\CMS\MVC\Controller\BaseController
{	
	protected $default_view = 'configuration';
	
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   mixed    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return   JController This object to support chaining.
	 *
	 * @since    1.5
     * @throws Exception
	 */
	function display($cachable = false, $urlparams = false) {

		$app = Factory::getApplication();
		$viewName = $app->input->getCmd('view', $this->default_view);
        $layoutName = $app->input->get('layout', 'default');
		$app->input->set('view', $viewName);

		$document = Factory::getDocument();
		$viewType = $document->getType();

		$view = $this->getView($viewName, $viewType);
		$model = $this->getModel('Ninox');
        if ($model)
		{
			$view->setModel($model, true);
		}

        $view->setLayout($layoutName);
        parent::display($cachable, $urlparams);
		
		return $this;
	}
}
?>
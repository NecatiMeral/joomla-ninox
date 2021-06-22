<?php
/**
 * @license MIT License (https://github.com/NecatiMeral/joomla-ninox/blob/main/LICENSE)
 * @author  Necati Meral (necati_meral@yahoo.de)
 * @url     https://github.com/NecatiMeral/joomla-ninox
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class NinoxViewConfiguration extends JViewLegacy 
{
    function display($tpl = null) 
	{
		$input = JFactory::getApplication()->input;
		$task = $input->get('task', '');
		$model = $this->getModel();

		if ($task) {
			$model->{$task}($input);
		}
    }
}
?>

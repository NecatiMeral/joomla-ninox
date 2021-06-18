<?php
/*
 * @license MIT License (https://github.com/NecatiMeral/joomla-ninox/blob/main/LICENSE)
 * @author  Necati Meral (necati_meral@yahoo.de)
 * @url     https://github.com/NecatiMeral/joomla-ninox
 */

 // No direct access to this file
defined('_JEXEC') or die('Restricted access');
require_once(JPATH_COMPONENT.'/helpers/ninox.php');
require_once(JPATH_COMPONENT.'/helpers/html.php');
require_once(JPATH_COMPONENT.'/controller.php');

NinoxHelper::loadScripts();
// import joomla controller library
jimport('joomla.application.component.controller');
 
$controller = JControllerLegacy::getInstance('Ninox');
$controller->execute(JFactory::getApplication()->input->get('task', ''));
$controller->redirect();
?>
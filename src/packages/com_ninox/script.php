<?php
/*
 * @license MIT License (https://github.com/NecatiMeral/joomla-ninox/blob/main/LICENSE)
 * @author  Necati Meral (necati_meral@yahoo.de)
 * @url     https://github.com/NecatiMeral/joomla-ninox
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class com_NinoxInstallerScript {
	/**
	* method to install the component
	*
	* @return void
	*/
	function install($parent) {
		$this->ninoxInstall();
	}
	
	/**
	* method to uninstall the component
	*
	* @return void
	*/
	function uninstall($parent) {
		// $parent is the class calling this method
		//echo '<p>' . JText::_('COM_HELLOWORLD_UNINSTALL_TEXT') . '</p>';
	}
	
	/**
	* method to update the component
	*
	* @return void
	*/
	function update($parent)  {
		$this->ninoxInstall();
		// $parent is the class calling this method
		//echo '<p>' . JText::sprintf('COM_HELLOWORLD_UPDATE_TEXT', $parent->get('manifest')->version) . '</p>';
	}
	
	/**
	* method to run before an install/update/uninstall method
	*
	* @return void
	*/
	function preflight($type, $parent) {
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		//drop old version's profileurl_config
	}
	
	/**
	* method to run after an install/update/uninstall method
	*
	* @return void
	*/
	function postflight($type, $parent) {
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		//echo '<p>' . JText::_('COM_HELLOWORLD_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
	}
	
	function ninoxInstall() {
		$app = JFactory::getApplication();
		$app->enqueueMessage('Component installed. Remember to enter your Ninox API Key on the <a href="'.JURI::root().'administrator/index.php?option=com_ninox">component page</a>.');
	}
}
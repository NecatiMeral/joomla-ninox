<?php
/*
 * @license MIT License (https://github.com/NecatiMeral/joomla-ninox/blob/main/LICENSE)
 * @author  Necati Meral (necati_meral@yahoo.de)
 * @url     https://github.com/NecatiMeral/joomla-ninox
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$lang = JFactory::getLanguage();
$base_dir = JPATH_SITE.'/components/com_ninox';
$lang->load('com_ninox', $base_dir, 'en-GB', true);
$lang->load('com_ninox', $base_dir, NULL, true);

class NinoxHelper {
	protected static $_params;
	
	static function getParams() {
		// Test if the config is already loaded.
		if (!self::$_params) {
			JTable::addIncludePath(JPATH_SITE.'/administrator/components/com_ninox/tables');
			$jconfig = JFactory::getConfig();
			jimport( 'joomla.html.parameter' );
			$config = JTable::getInstance( 'Config' , 'NinoxTable' );
			$config->load( 'config' );
			self::$_params = new JRegistry;
			// Bind the user saved configuration.
			$row = new JRegistry;
			$row->loadString($config->params);
			self::$_params->merge( $row );
		}
		return self::$_params;
	}
}
?>
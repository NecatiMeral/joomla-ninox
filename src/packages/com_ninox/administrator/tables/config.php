<?php
/*
 * @license MIT License (https://github.com/NecatiMeral/joomla-ninox/blob/main/LICENSE)
 * @author  Necati Meral (necati_meral@yahoo.de)
 * @url     https://github.com/NecatiMeral/joomla-ninox
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
class NinoxTableConfig extends JTable {

	/**
	* Constructor
	*
	* @param object Database connector object
	*/
	function __construct(&$db) {
		parent::__construct('#__ninox_config', 'name', $db);
	}
	
}
?>
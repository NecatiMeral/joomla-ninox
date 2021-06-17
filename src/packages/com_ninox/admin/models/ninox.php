<?php
/*
 * @license MIT License (https://github.com/NecatiMeral/joomla-ninox/blob/main/LICENSE)
 * @author  Necati Meral (necati_meral@yahoo.de)
 * @url     https://github.com/NecatiMeral/joomla-ninox
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
class NinoxModelNinox extends JModelLegacy {
	protected $users_table;
	protected $include_blocked;
	protected $include_unconfirmed;
	protected $registered_after;
	protected $last_login_after;

	public function __construct($config = array()) {
		$this->users_table = '#__users';
		$this->include_blocked = 0;
		$this->include_unconfirmed = 0;
		$this->registered_after = '';
		$this->last_login_after = '';
		parent::__construct($config);
	}
}
?>
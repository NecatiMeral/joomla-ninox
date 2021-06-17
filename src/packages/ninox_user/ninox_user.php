<?php
/*
 * @license MIT License (https://github.com/NecatiMeral/joomla-ninox/blob/main/LICENSE)
 * @author  Necati Meral (necati_meral@yahoo.de)
 * @url     https://github.com/NecatiMeral/joomla-ninox
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgUserNinox_user extends JPlugin {
	protected $article_id;
	
	public function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
	}
		
	public function onUserAfterSave($user, $isnew, $success, $msg) {
		// TODO: Implement ninox sync
		// re-use existing logic in main component
	}
	
}
?>
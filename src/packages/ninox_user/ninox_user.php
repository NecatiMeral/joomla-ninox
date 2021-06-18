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
		if($isnew){
			require_once(JPATH_SITE.'/administrator/components/com_ninox/helpers/ninox.php');
			$config = NinoxHelper::getParams();
			if ($config->get('auto_sync') && $config->get('apikey')) 
			{
				$contacts = array();
				$parts = explode(" ", $user['name']);
				$last_name = array_pop($parts);
				$first_name = implode(" ", $parts);
				$contacts[] = array(
					'id' => $user['id'], 
					'first_name' => $first_name, 
					'last_name' => $last_name, 
					'email' => $user['email']
				);

				NinoxHelper::addContacts($contacts);
			}
		}
	}
	
}
?>
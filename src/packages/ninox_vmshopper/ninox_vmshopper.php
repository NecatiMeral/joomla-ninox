<?php
/*
 * @license MIT License (https://github.com/NecatiMeral/joomla-ninox/blob/main/LICENSE)
 * @author  Necati Meral (necati_meral@yahoo.de)
 * @url     https://github.com/NecatiMeral/joomla-ninox
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

class plgVmShopperNinox_vmshopper extends JPlugin {
	
	public function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
	}

	public function plgVmAfterUserStore($data) 
	{
		require_once(JPATH_SITE.'/administrator/components/com_ninox/helpers/ninox.php');
		
		$config = NinoxHelper::getParams();
		if ($config->get('auto_sync') && $config->get('apikey')) 
		{
			$contacts = array();
			$parts = explode(" ", $data['name']);
			$last_name = array_pop($parts);
			$first_name = implode(" ", $parts);
			$contacts[] = array(
				'id' => $data['virtuemart_user_id'], 
				'first_name' => $first_name,
				'last_name' => $last_name,
				'email' => $data['email']
			);

			NinoxHelper::addContacts($contacts);
		}
		return $data;
	}
}
?>
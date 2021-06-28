<?php
/*
 * @license MIT License (https://github.com/NecatiMeral/joomla-ninox/blob/main/LICENSE)
 * @author  Necati Meral (necati_meral@yahoo.de)
 * @url     https://github.com/NecatiMeral/joomla-ninox
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

class plgVmShopperNinox_vmshopperInstallerScript {
	function install($parent) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->update($db->quoteName('#__extensions'))
			->set($db->quoteName('enabled') . ' = ' . $db->quote(1))
			->where($db->quoteName('element') . ' = ' . $db->quote('ninox_vmshopper'))
			->where($db->quoteName('folder') . ' = ' . $db->quote('vmshopper'));
		$db->setQuery($query);
		
		try {
			$db->execute();
		}
		catch (RuntimeException $e) {
			JError::raiseWarning(500, 'Ninox Auto-Sync vmshopper plugin <strong>not enabled</strong>. Please enable it manually by going to <a href="'.JURI::root().'administrator/index.php?option=com_plugins">Joomla\'s Plugins manager</a>.');
			return;
		}
		$app = JFactory::getApplication();
		$app->enqueueMessage('Ninox Auto-Sync vmshopper plugin <strong>enabled</strong>.');
	}

	function update($parent) {
		if (self::isEnabled()) {
			$app = JFactory::getApplication();
			$app->enqueueMessage('Ninox Auto-Sync vmshopper plugin <strong>enabled</strong>.');
		}
		else
			JError::raiseWarning(500, 'Ninox Auto-Sync vmshopper plugin <strong>not enabled</strong>. Please enable it manually by going to <a href="'.JURI::root().'administrator/index.php?option=com_plugins">Joomla\'s Plugins manager</a>.');
	}

	function isEnabled() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName('enabled'))
			->from($db->quoteName('#__extensions'))
			->where($db->quoteName('element') . ' = ' . $db->quote('ninox_vmshopper'))
			->where($db->quoteName('folder') . ' = ' . $db->quote('vmshopper'));
		$db->setQuery($query);
		return $db->loadResult();
	}
}
?>
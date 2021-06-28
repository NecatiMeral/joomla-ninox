<?php
/*
 * @license MIT License (https://github.com/NecatiMeral/joomla-ninox/blob/main/LICENSE)
 * @author  Necati Meral (necati_meral@yahoo.de)
 * @url     https://github.com/NecatiMeral/joomla-ninox
 */

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Form\FormHelper;

class NinoxHtmlHelper
{
	/**
	 * Get bootstrapped style boolean input
	 *
	 * @param $name
	 * @param $value
	 *
	 * @return string
	 */
	public static function getBooleanInput($name, $value)
	{
		JFormHelper::loadFieldClass('radio');
		
		$field = new JFormFieldRadio();

		$element = new SimpleXMLElement('<field />');
		$element->addAttribute('name', $name);

		if (version_compare(JVERSION, '4.0.0-dev', 'ge'))
		{
			$element->addAttribute('layout', 'joomla.form.field.radio.switcher');
		}
		else
		{
			$element->addAttribute('class', 'radio btn-group btn-group-yesno');
		}

		$element->addAttribute('default', '0');

		$node = $element->addChild('option', 'JNO');
		$node->addAttribute('value', '0');

		$node = $element->addChild('option', 'JYES');
		$node->addAttribute('value', '1');

		$field->setup($element, (int) $value);

		return $field->input;
	}
}

?>
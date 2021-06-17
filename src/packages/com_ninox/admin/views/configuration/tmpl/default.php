<?php
/*
 * @license MIT License (https://github.com/NecatiMeral/joomla-ninox/blob/main/LICENSE)
 * @author  Necati Meral (necati_meral@yahoo.de)
 * @url     https://github.com/NecatiMeral/joomla-ninox
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>

<fieldset class="form-horizontal">
	<legend>General configuration</legend>
	<form action="index.php" method="post" name="adminForm" id="adminForm">

		<input type="hidden" name="option" value="com_ninox" />
		<input type="hidden" name="view" value="ninox" />
		<input type="hidden" name="task" value="" />

	</form>
</fieldset>

<fieldset id="export" class="form-horizontal">
	<legend>Export existing users to Ninox</legend>
</fieldset>
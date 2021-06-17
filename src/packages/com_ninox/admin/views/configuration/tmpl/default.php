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

		<div class="control-group">
			<label class="hasTooltip control-label" for="apikey" title="API key of your Ninox account.">
				API Key
			</label>
			<div class="controls">
				<input type="text" id="apikey" name="apikey" value="<?php echo $this->config->get('apikey');?>" class="input-xxlarge" />
				<small class="help-block">To create an API key, go <a href="https://user.ninox.com/account/api" target="_blank">here</a> and click on the Create API Key button.</small>
				<small class="help-block">Copy and paste the generated API Key here.</small>
			</div>
		</div>

		<input type="hidden" name="option" value="com_ninox" />
		<input type="hidden" name="view" value="ninox" />
		<input type="hidden" name="task" value="" />

	</form>
</fieldset>

<fieldset id="export" class="form-horizontal">
	<legend>Export existing users to Ninox</legend>
</fieldset>
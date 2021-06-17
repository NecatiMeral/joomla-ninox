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
	<legend><?php echo JText::_('COM_NINOX_GENERAL_SETTINGS'); ?></legend>
	<form action="index.php" method="post" name="adminForm" id="adminForm">

		<div class="control-group">
			<label class="hasTooltip control-label" for="apikey" title="<?php echo JText::_('COM_NINOX_API_KEY_TOOLTIP'); ?>">
				<?php echo JText::_('COM_NINOX_API_KEY'); ?>
			</label>
			<div class="controls">
				<input type="text" id="apikey" name="apikey" value="<?php echo $this->config->get('apikey');?>" class="input-xxlarge" />
				<small class="help-block"><?php echo JText::_('COM_NINOX_API_KEY_HINT1'); ?></small>
				<small class="help-block"><?php echo JText::_('COM_NINOX_API_KEY_HINT2'); ?></small>
			</div>
		</div>

		<div class="control-group">
			<label class="hasTooltip control-label" for="team_id">
				<?php echo JText::_('COM_NINOX_API_TEAM'); ?>
			</label>
			<div class="controls">
				<?php

				$disabled = ($this->teams) ? '' : 'disabled="disabled"';
				echo '<select id="team_id" name="team_id" class="input-xxlarge" ' . $disabled . '>';
				
				if($this->teams) {
					
					echo '<option value=""></option>';
					foreach ($this->teams as $team) {
						$selected = $this->config->get('team_id') == $team['id'] ? ' selected' : '';
						echo '<option value="' . $team['id'] . '"' . $selected . '>' . $team['name'] . '</option>';
					}

				}
				else {
					echo '<option value="" selected></option>';
				}
				echo '</select>';
				
				if(!$this->teams) {
					echo '<small class="help-block">Please enter a valid API Key first.</small>';
				}

				?>
			</div>
		</div>

		<div class="control-group">
			<label class="hasTooltip control-label" for="database_id">
				<?php echo JText::_('COM_NINOX_API_DATABASE'); ?>
			</label>
			<div class="controls">
				<?php

				$disabled = ($this->databases) ? '' : 'disabled="disabled"';
				echo '<select id="database_id" name="database_id" class="input-xxlarge" ' . $disabled . '>';
				
				if($this->databases) {
					
					echo '<option value=""></option>';
					foreach ($this->databases as $database) {
						$selected = $this->config->get('database_id') == $database['id'] ? ' selected' : '';
						echo '<option value="' . $database['id'] . '"' . $selected . '>' . $database['name'] . '</option>';
					}

				}
				else {
					echo '<option value="" selected></option>';
				}
				echo '</select>';
				
				if(!$this->databases) {
					echo '<small class="help-block">Please enter a valid API Key first and select a team.</small>';
				}

				?>
			</div>
		</div>

		<div class="control-group">
			<label class="hasTooltip control-label" for="table_id">
				<?php echo JText::_('COM_NINOX_API_TABLE'); ?>
			</label>
			<div class="controls">
				<?php

				$disabled = ($this->tables) ? '' : 'disabled="disabled"';
				echo '<select id="table_id" name="table_id" class="input-xxlarge" ' . $disabled . '>';

				if($this->tables) {

					echo '<option value=""></option>';
					foreach ($this->tables as $table) {
						$selected = $this->config->get('table_id') == $table['id'] ? ' selected' : '';
						echo '<option value="' . $table['id'] . '"' . $selected . '>' . $table['name'] . '</option>';
					}

				}
				else {
					echo '<option value="" selected></option>';
				}
				echo '</select>';
				
				if(!$this->tables) {
					echo '<small class="help-block">Please enter a valid API Key first and select a team.</small>';
				}

				?>
			</div>
		</div>

		<input type="hidden" name="option" value="com_ninox" />
		<input type="hidden" name="view" value="ninox" />
		<input type="hidden" name="task" value="" />

	</form>
</fieldset>

<fieldset id="export" class="form-horizontal">
	<legend><?php echo JText::_('COM_NINOX_EXPORT_EXISTING'); ?></legend>
</fieldset>
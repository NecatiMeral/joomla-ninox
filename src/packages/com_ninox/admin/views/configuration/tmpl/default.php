<?php
/*
 * @license MIT License (https://github.com/NecatiMeral/joomla-ninox/blob/main/LICENSE)
 * @author  Necati Meral (necati_meral@yahoo.de)
 * @url     https://github.com/NecatiMeral/joomla-ninox
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$js = "
Joomla.submitbutton = function(task) {
	var $ = jQuery, error = false;
	if (task == 'apply') {
		if ($.trim($('#apikey').val()) == '') {
			$('#apikey').focus().attr('placeholder', 'API Key is required').closest('.control-group').addClass('error');
		}
		else
			Joomla.submitform(task, document.getElementById('adminForm'));
	}
}
";
$document->addScriptDeclaration($js);
?>

<fieldset class="form-horizontal">
	<legend><?php echo JText::_('COM_NINOX_GENERAL_SETTINGS'); ?></legend>
	<form action="index.php" method="post" name="adminForm" id="adminForm">

		<div class="control-group">
			<label class="hasTooltip control-label" for="apikey" title="<?php echo JText::_('COM_NINOX_API_KEY_TOOLTIP'); ?>">
				<?php echo JText::_('COM_NINOX_API_KEY'); ?>
			</label>
			<div class="controls">
				<input type="text" id="apikey" name="apikey" value="<?php echo $this->config->get('apikey');?>" class="input-xlarge" size="36" />
				<small class="help-block"><?php echo JText::_('COM_NINOX_API_KEY_HINT1'); ?></small>
				<small class="help-block"><?php echo JText::_('COM_NINOX_API_KEY_HINT2'); ?></small>
			</div>
		</div>

		<div class="control-group">
			<label class="hasTooltip control-label" for="team_id">
				<?php echo JText::_('COM_NINOX_API_TEAM'); ?>
			</label>
			<div class="controls">
				<?php echo $this->lists['team_id']; ?>
			</div>
		</div>

		<div class="control-group">
			<label class="hasTooltip control-label" for="database_id">
				<?php echo JText::_('COM_NINOX_API_DATABASE'); ?>
			</label>
			<div class="controls">
				<?php echo $this->lists['database_id']; ?>
			</div>
		</div>

		<div class="control-group">
			<label class="hasTooltip control-label" for="table_id">
				<?php echo JText::_('COM_NINOX_API_TABLE'); ?>
			</label>
			<div class="controls">
				<?php echo $this->lists['table_id']; ?>
			</div>
		</div>
	
		<div class="control-group">
			<label class="hasTooltip control-label" for="auto_sync" title="<?php echo JText::_('COM_NINOX_AUTO_SYNC_TOOLTIP'); ?>">
				<?php echo JText::_('COM_NINOX_AUTO_SYNC'); ?>
			</label>
			<div class="controls">
				<?php echo NinoxHtmlHelper::getBooleanInput('auto_sync', $this->config['auto_sync']); ?>
			</div>
		</div>

		<input type="hidden" name="option" value="com_ninox" />
		<input type="hidden" name="view" value="ninox" />
		<input type="hidden" name="task" value="" />

	</form>
</fieldset>

<fieldset id="export" class="form-horizontal">
	<legend><?php echo JText::_('COM_NINOX_EXPORT_EXISTING'); ?></legend>
	<?php
	if (!$this->config->get('apikey'))
	{
		echo '<div class="muted">' . JText::_('COM_NINOX_API_KEY_FIRST') . '</div>';
	}
	else {
	?>
	<div class="row-fluid">
		<div class="span6">
			<div class="control-group">
				<label class="hasTooltip control-label" for="include_blocked" title="<?php echo JText::_('COM_NINOX_INCLUDE_BLOCKED_USERS_TOOLTIP'); ?>">
					<?php echo JText::_('COM_NINOX_INCLUDE_BLOCKED_USERS'); ?>
				</label>
				<div class="controls">
					<?php echo NinoxHtmlHelper::getBooleanInput('include_blocked', '0'); ?>
				</div>
			</div>
	
			<div class="control-group">
				<label class="hasTooltip control-label" for="include_unconfirmed" title="<?php echo JText::_('COM_NINOX_INCLUDE_UNCONFIRMED_USERS_TOOLTIP'); ?>">
					<?php echo JText::_('COM_NINOX_INCLUDE_UNCONFIRMED_USERS'); ?>
				</label>
				<div class="controls">
					<?php echo NinoxHtmlHelper::getBooleanInput('include_unconfirmed', '0'); ?>
				</div>
			</div>
	
			<div class="control-group">
				<label class="hasTooltip control-label" for="registered_after" title="<?php echo JText::_('COM_NINOX_REGISTERED_DATE_FILTER_TOOLTIP'); ?>">
					<?php echo JText::_('COM_NINOX_REGISTERED_DATE'); ?>
				</label>
				<div class="controls">
					<?php echo JHtml::calendar('', 'registered_after', 'registered_after', '%Y-%m-%d', 'class="input-medium"');?>
					<small class="help-block"><?php echo JText::_('COM_NINOX_REGISTERED_DATE_FILTER_BLANK'); ?></small>
				</div>
			</div>
	
			<div class="control-group">
				<label class="hasTooltip control-label" for="last_login_after" title="<?php echo JText::_('COM_NINOX_LAST_LOGIN_DATE_FILTER_TOOLTIP'); ?>">
					<?php echo JText::_('COM_NINOX_LAST_LOGIN_DATE'); ?>
				</label>
				<div class="controls">
					<?php echo JHtml::calendar('', 'last_login_after', 'last_login_after', '%Y-%m-%d', 'class="input-medium"');?>
					<small class="help-block"><?php echo JText::_('COM_NINOX_LAST_LOGIN_DATE_FILTER_BLANK'); ?></small>
				</div>
			</div>
		</div>
		<div class="span6">
			<div class="progress">
				<div class="bar" style="width:0%;"></div>
			</div>
			<a class="btn btn-large btn-primary btn-export" onclick="beginExport(this)"><?php echo JText::_('COM_NINOX_BEGIN_EXPORT'); ?></a>
			<div class="alert hidden"></div>
		</div>
	</div>
	<?php } ?>
</fieldset>
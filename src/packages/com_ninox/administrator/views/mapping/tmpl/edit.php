<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Ninox
 * @author     Necati Meral <sphinxgraph@yahoo.de>
 * @copyright  2021 Necati Meral
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;


HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.formvalidation');
HTMLHelper::_('formbehavior.chosen', 'select');
HTMLHelper::_('behavior.keepalive');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'media/com_ninox/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	});

	Joomla.submitbutton = function (task) {
		if (task == 'mapping.cancel') {
			Joomla.submitform(task, document.getElementById('mapping-form'));
		}
		else {
			
			if (task != 'mapping.cancel' && document.formvalidator.isValid(document.id('mapping-form'))) {
				
				Joomla.submitform(task, document.getElementById('mapping-form'));
			}
			else {
				alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_ninox&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="mapping-form" class="form-validate form-horizontal">

	
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'mapping')); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'mapping', JText::_('COM_NINOX_TAB_MAPPING', true)); ?>
	<div class="row-fluid">
		<div class="span10 form-horizontal">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_NINOX_FIELDSET_MAPPING'); ?></legend>
				<?php echo $this->form->renderField('joomla_prop'); ?>
				<?php echo $this->form->renderField('ninox_prop'); ?>
			</fieldset>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
	<?php echo $this->form->renderField('created_by'); ?>
	<?php echo $this->form->renderField('modified_by'); ?>

	

	<?php $this->ignore_fieldsets = array('general', 'info', 'detail', 'jmetadata', 'item_associations', 'accesscontrol'); ?>
	<?php echo JLayoutHelper::render('joomla.edit.params', $this); ?>
	
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>

</form>

<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Pixext
 * @author     Johan Sundell <johan@pixpro.net>
 * @copyright  2017 Johan Sundell
 * @license    GNU General Public License version 2 eller senare; se LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'media/com_pixext/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	js('input:hidden.pixext_extension_version_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('pixext_extension_version_idhidden')){
			js('#jform_pixext_extension_version_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_pixext_extension_version_id").trigger("liszt:updated");
	js('input:hidden.pixext_package_version_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('pixext_package_version_idhidden')){
			js('#jform_pixext_package_version_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_pixext_package_version_id").trigger("liszt:updated");
	});

	Joomla.submitbutton = function (task) {
		if (task == 'packageextensionversion.cancel') {
			Joomla.submitform(task, document.getElementById('packageextensionversion-form'));
		}
		else {
			
			if (task != 'packageextensionversion.cancel' && document.formvalidator.isValid(document.id('packageextensionversion-form'))) {
				
				Joomla.submitform(task, document.getElementById('packageextensionversion-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_pixext&layout=edit&pixext_package_extension_version_id=' . (int) $this->item->pixext_package_extension_version_id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="packageextensionversion-form" class="form-validate">

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_PIXEXT_TITLE_PACKAGEEXTENSIONVERSION', true)); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">

									<?php echo $this->form->renderField('pixext_package_extension_version_id'); ?>
				<?php echo $this->form->renderField('pixext_extension_version_id'); ?>

			<?php
				foreach((array)$this->item->pixext_extension_version_id as $value):
					if(!is_array($value)):
						echo '<input type="hidden" class="pixext_extension_version_id" name="jform[pixext_extension_version_idhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>				<?php echo $this->form->renderField('pixext_package_version_id'); ?>

			<?php
				foreach((array)$this->item->pixext_package_version_id as $value):
					if(!is_array($value)):
						echo '<input type="hidden" class="pixext_package_version_id" name="jform[pixext_package_version_idhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>				<?php echo $this->form->renderField('state'); ?>
				<?php echo $this->form->renderField('created_by'); ?>


					<?php if ($this->state->params->get('save_history', 1)) : ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
					</div>
					<?php endif; ?>
				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>

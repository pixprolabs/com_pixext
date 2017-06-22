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
		
	js('input:hidden.pixext_extension_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('pixext_extension_idhidden')){
			js('#jform_pixext_extension_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_pixext_extension_id").trigger("liszt:updated");
	js('input:hidden.pixext_tag_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('pixext_tag_idhidden')){
			js('#jform_pixext_tag_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_pixext_tag_id").trigger("liszt:updated");
	});

	Joomla.submitbutton = function (task) {
		if (task == 'version.cancel') {
			Joomla.submitform(task, document.getElementById('version-form'));
		}
		else {
			
			if (task != 'version.cancel' && document.formvalidator.isValid(document.id('version-form'))) {
				
				Joomla.submitform(task, document.getElementById('version-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_pixext&layout=edit&pixext_version_id=' . (int) $this->item->pixext_version_id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="version-form" class="form-validate">

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_PIXEXT_TITLE_VERSION', true)); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">

									<?php echo $this->form->renderField('pixext_version_id'); ?>
				<?php echo $this->form->renderField('pixext_extension_id'); ?>

			<?php
				foreach((array)$this->item->pixext_extension_id as $value):
					if(!is_array($value)):
						echo '<input type="hidden" class="pixext_extension_id" name="jform[pixext_extension_idhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>				<?php echo $this->form->renderField('pixext_tag_id'); ?>

			<?php
				foreach((array)$this->item->pixext_tag_id as $value):
					if(!is_array($value)):
						echo '<input type="hidden" class="pixext_tag_id" name="jform[pixext_tag_idhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>				<?php echo $this->form->renderField('major'); ?>
				<?php echo $this->form->renderField('minor'); ?>
				<?php echo $this->form->renderField('patch'); ?>
				<?php echo $this->form->renderField('joomla_version'); ?>
				<?php echo $this->form->renderField('state'); ?>
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

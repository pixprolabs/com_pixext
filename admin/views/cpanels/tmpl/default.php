<?php
// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_pixext/assets/css/pixext.css');
$document->addStyleSheet(JUri::root() . 'media/com_pixext/css/list.css');

JFactory::getDocument()->addScriptDeclaration('
		Joomla.submitbuttonupload = function()
		{
			var form = document.getElementById("adminForm");

			// do field validation
			if (form.file_upload.value == "")
			{
				alert("' . JText::_('COM_PIXEXT_CPANELS_NOFILE') . '");
			}
			else
			{
				jQuery("#loading").css("display", "block");
				form.task.value = "cpanels.upload";
				form.submit();
			}
		};
		');

?>


<?php

// Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar))
{
	$this->sidebar .= $this->extra_sidebar;
}

?>

<form action="<?php echo JRoute::_('index.php?option=com_pixext&view=cpanels'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>
			<input type="file" name="file_upload" id="file_upload" />
			<button class="btn btn-primary" type="button" id="installbutton_package" onclick="Joomla.submitbuttonupload()">
				<?php echo JText::_('COM_PIXEXT_CPANELS_UPLOAD_FILE'); ?>
			</button>
			<input type="hidden" name="task" value=""/>
			<?php echo JHtml::_('form.token'); ?>
		</div>
</form>
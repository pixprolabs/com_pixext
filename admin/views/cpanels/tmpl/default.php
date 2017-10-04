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
$document->addScript( JUri::root().'media/com_pixext/bower_components/webcomponentsjs/webcomponents-loader.js' );
$document->addHeadLink( JUri::root().'media/com_pixext/pixext-licenses.html', 'import' );
$document->addHeadLink( JUri::root().'media/com_pixext/pixext-extension.html', 'import' );
JHTML::_('behavior.modal');

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
			<div>
				<?php foreach( $this->extensions as $row ): ?>
				<a href="<?php echo JRoute::_( 'index.php?option=com_pixext&view=extension&layout=modal&pixext_extension_id='.(int)$row->pixext_extension_id.'&tmpl=component' ); ?>" class="modal"><?php echo $row->name; ?></a><br/>
				<?php endforeach; ?>
			</div>
			<div>
				<!--
				<pixext-extension
					name="com_pixext"
					url="<?php echo JRoute::_( 'index.php?option=com_pixext&view=extension&layout=modal&tmpl=component' ); ?>"
				>
				</pixext-extension>
				 -->
			</div>
			<div>
				<!--
				<pixext-licenses base="<?php echo JRoute::_( 'index.php?option=com_pixext&task=cpanels.getinstallations' )?>"></pixext-licenses>
				 -->
			</div>
		</div>
</form>
    
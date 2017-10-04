<?php
/**
* @author		Johan Sundell <johan@pixpro.net>
* @link			https://www.pixpro.net/labs
* @copyright	Copyright © You Rock AB 2003-2017 All Rights Reserved.
* @license		GNU General Public License version 2 or later; see LICENSE.txt
*/

// No direct access
defined('_JEXEC') or die;
$document = JFactory::getDocument();
$document->addScript( JUri::root().'media/com_pixext/bower_components/webcomponentsjs/webcomponents-loader.js' );
$document->addHeadLink( JUri::root().'media/com_pixext/pixext-extension.html', 'import' );
?>
<div>
	<h2>
		<?php echo $this->item->name; ?>
	</h2>
</div>
<div>
	<pixext-licenses base="<?php echo JRoute::_( 'index.php?option=com_pixext&pixext_extension_id='.$this->pixext_extension_id.'&task=cpanels.getinstallations' )?>"></pixext-licenses>
</div>


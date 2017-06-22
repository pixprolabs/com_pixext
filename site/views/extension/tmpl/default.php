<?php
/**
* @copyright	Copyright (C) 2017 Johan Sundell. All rights reserved.
* @license		GNU General Public License version 2 or later; see LICENSE.txt
*/

// No direct access.
defined('_JEXEC') or die;
?>
<div>
	<?php foreach( $this->items as $row ):?>
	<div>
		<a href="<?php echo $this->url.$row->type.'/'.$row->name.'/'.$row->major.'.'.$row->minor.'.'.$row->patch.'/'.$row->licence_key.'.zip'; ?>"><?php echo $row->name; ?></a>
	</div>
	<?php endforeach;?>
</div>
<?php //print_r( $this->items );?>



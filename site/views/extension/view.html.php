<?php
/**
* @copyright	Copyright (C) 2017 Johan Sundell. All rights reserved.
* @license		GNU General Public License version 2 or later; see LICENSE.txt
*/

// No direct access.
defined('_JEXEC') or die;

class PixextViewExtension extends JViewLegacy
{
	protected $items;
	protected $url;
	
	public function display( $tpl = null )
	{
		$this->items = $this->get( 'Items' );
		$this->url = $this->get( 'BaseUrl' );
		return parent::display( $tpl );
	}
}

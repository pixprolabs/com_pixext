<?php
/**
* @copyright	Copyright (C) 2017 Johan Sundell. All rights reserved.
* @license		GNU General Public License version 2 or later; see LICENSE.txt
*/

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.helper');

class PixextModelExtension extends JModelLegacy
{
	public function getItems()
	{
		$userId = JFactory::getUser()->id;
		if( $userId != 0 )
		{
			$db = JFactory::getDbo();
			$query = 	'SELECT * FROM ( '.
						'SELECT pe.pixext_extension_id, pe.name, pt.type, pv.major, pv.minor, pv.patch, pl.licence_key FROM #__pixext_extensions pe '.
						'JOIN #__pixext_versions pv ON pv.pixext_extension_id = pe.pixext_extension_id '.
						'JOIN #__pixext_licenses pl ON pl.pixext_extension_id = pe.pixext_extension_id '.
						'JOIN #__pixext_types pt ON pt.pixext_type_id = pe.pixext_type_id '.
						'WHERE pl.user_id = '.(int)$userId.' AND pl.valid_to > NOW() '.
						'ORDER BY pe.pixext_extension_id, pv.major DESC, pv.minor DESC, pv.patch DESC '.
						') as tmp GROUP BY pixext_extension_id';
			return $db->setQuery( $query )->loadObjectList();
		}
		return false;
	}
	
	public function getBaseUrl()
	{
		$url = JComponentHelper::getParams( 'com_pixext' )->get( 'url' );
		return $url.'/pixext/downloads/';
	}
}

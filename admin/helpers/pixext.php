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

/**
 * Pixext helper.
 *
 * @since  1.6
 */
class PixextHelpersPixext
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  string
	 *
	 * @return void
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_PIXEXT_TITLE_TAGS'),
			'index.php?option=com_pixext&view=tags',
			$vName == 'tags'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_PIXEXT_TITLE_TYPES'),
			'index.php?option=com_pixext&view=types',
			$vName == 'types'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_PIXEXT_TITLE_EXTENSIONS'),
			'index.php?option=com_pixext&view=extensions',
			$vName == 'extensions'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_PIXEXT_TITLE_VERSIONS'),
			'index.php?option=com_pixext&view=versions',
			$vName == 'versions'
		);
/*
JHtmlSidebar::addEntry(
			JText::_('COM_PIXEXT_TITLE_LOGTYPES'),
			'index.php?option=com_pixext&view=logtypes',
			$vName == 'logtypes'
		);

JHtmlSidebar::addEntry(
			JText::_('COM_PIXEXT_TITLE_IPS'),
			'index.php?option=com_pixext&view=ips',
			$vName == 'ips'
		);

JHtmlSidebar::addEntry(
			JText::_('COM_PIXEXT_TITLE_LOGS'),
			'index.php?option=com_pixext&view=logs',
			$vName == 'logs'
		);
*/
		JHtmlSidebar::addEntry(
			JText::_('COM_PIXEXT_TITLE_LICENSES'),
			'index.php?option=com_pixext&view=licenses',
			$vName == 'licenses'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_PIXEXT_TITLE_PACKAGES'),
			'index.php?option=com_pixext&view=packages',
			$vName == 'packages'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_PIXEXT_TITLE_PACKAGEVERSIONS'),
			'index.php?option=com_pixext&view=packageversions',
			$vName == 'packageversions'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_PIXEXT_TITLE_PACKAGELICENSES'),
			'index.php?option=com_pixext&view=packagelicenses',
			$vName == 'packagelicenses'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_PIXEXT_TITLE_PACKAGEEXTENSIONVERSIONS'),
			'index.php?option=com_pixext&view=packageextensionversions',
			$vName == 'packageextensionversions'
		);
/**/
		JHtmlSidebar::addEntry(
			JText::_('COM_PIXEXT_TITLE_CPANELS'),
			'index.php?option=com_pixext&view=cpanels',
			$vName == 'cpanels'
		);

	}

	/**
	 * Gets the files attached to an item
	 *
	 * @param   int     $pk     The item's id
	 *
	 * @param   string  $table  The table's name
	 *
	 * @param   string  $field  The field's name
	 *
	 * @return  array  The files
	 */
	public static function getFiles($pk, $table, $field)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select($field)
			->from($table)
			->where('id = ' . (int) $pk);

		$db->setQuery($query);

		return explode(',', $db->loadResult());
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return    JObject
	 *
	 * @since    1.6
	 */
	public static function getActions()
	{
		$user   = JFactory::getUser();
		$result = new JObject;

		$assetName = 'com_pixext';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}


class PixextHelper extends PixextHelpersPixext
{

}

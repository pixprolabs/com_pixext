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

jimport('joomla.application.component.view');

/**
 * View class for a list of Pixext.
 *
 * @since  1.6
 */
class PixextViewCpanels extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->extensions = $this->get( 'Extensions' );

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		PixextHelpersPixext::addSubmenu('cpanels');

		$this->addToolbar();
		//$data = PixextHelpersPixext::getComponentInstallations( 13, 0, 0 );
		//print_r( $data ); die();
		//$doc = JFactory::getDocument();
		//$doc->addScript( JUri::root().'media/com_pixext/bower_components/webcomponentsjs/webcomponents-loader.js' );
		//$doc->addHeadLink( JUri::root().'media/com_pixext/bower_components/polymer/polymer.html', 'import' );
		//$doc->addCustomTag( '<link href="http://localhost/extensionmanager/media/com_pixext/bower_components/polymer/polymer.html" rel="import">' );
		//$doc->addCustomTag( '<link href="http://localhost/extensionmanager/media/com_pixext/my-test.html" rel="import">' );
		//$doc->addHeadLink( JUri::root().'media/com_pixext/my-test.html', 'import' );
		//$doc->addHeadLink( JUri::root().'media/com_pixext/pixext-extension.html', 'import' );

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function addToolbar()
	{
		$state = $this->get('State');
		$canDo = PixextHelpersPixext::getActions();

		JToolBarHelper::title(JText::_('COM_PIXEXT_TITLE_CPANELS'), 'cpanels.png');

		// Check if the form exists before showing the add/edit buttons
		/*$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/cpanel';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('cpanel.add', 'JTOOLBAR_NEW');

				if (isset($this->items[0]))
				{
					JToolbarHelper::custom('cpanels.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
				}
			}

			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
				JToolBarHelper::editList('cpanel.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('cpanels.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('cpanels.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'cpanels.delete', 'JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('cpanels.archive', 'JTOOLBAR_ARCHIVE');
			}

			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('cpanels.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'cpanels.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			elseif ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('cpanels.trash', 'JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}*/
		
		// TODO: Add security check
		JToolbarHelper::custom( 'cpanels.ping', 'checkin.png', 'checkin_f2.png', 'ping', false );

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_pixext');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_pixext&view=cpanels');

		$this->extra_sidebar = '';
	}

	/**
	 * Method to order fields
	 *
	 * @return void
	 */
	protected function getSortFields()
	{
		return array(
			'a.`pixext_package_extension_version_id`' => JText::_('COM_PIXEXT_PACKAGEEXTENSIONVERSIONS_PIXEXT_PACKAGE_EXTENSION_VERSION_ID'),
			'a.`pixext_extension_version_id`' => JText::_('COM_PIXEXT_PACKAGEEXTENSIONVERSIONS_PIXEXT_EXTENSION_VERSION_ID'),
			'a.`pixext_package_version_id`' => JText::_('COM_PIXEXT_PACKAGEEXTENSIONVERSIONS_PIXEXT_PACKAGE_VERSION_ID'),
			'a.`state`' => JText::_('JSTATUS'),
			'a.`created_by`' => JText::_('COM_PIXEXT_PACKAGEEXTENSIONVERSIONS_CREATED_BY'),
		);
	}
}

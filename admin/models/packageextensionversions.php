<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Pixext
 * @author     Johan Sundell <johan@pixpro.net>
 * @copyright  2017 Johan Sundell
 * @license    GNU General Public License version 2 eller senare; se LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Pixext records.
 *
 * @since  1.6
 */
class PixextModelPackageextensionversions extends JModelList
{
/**
	* Constructor.
	*
	* @param   array  $config  An optional associative array of configuration settings.
	*
	* @see        JController
	* @since      1.6
	*/
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'pixext_package_extension_version_id', 'a.`pixext_package_extension_version_id`',
				'pixext_extension_version_id', 'a.`pixext_extension_version_id`',
				'pixext_package_version_id', 'a.`pixext_package_version_id`',
				'state', 'a.`state`',
				'created_by', 'a.`created_by`',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_pixext');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.pixext_package_extension_version_id', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return   string A store id.
	 *
	 * @since    1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select', 'DISTINCT a.*'
			)
		);
		$query->from('`#__pixext_package_extension_versions` AS a');

		// Join over the users for the checked out user
		$query->select("uc.name AS uEditor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");
		// Join over the foreign key 'pixext_extension_version_id'
		//$query->select('`#__pixext_versions_2663498`.`pixext_version_id` AS versions_fk_value_2663498');
		$query->select("CONCAT( pe.name, ' ', #__pixext_versions_2663498.major, '.', #__pixext_versions_2663498.minor, '.', #__pixext_versions_2663498.patch ) AS versions_fk_value_2663498");
		$query->join('LEFT', '#__pixext_versions AS #__pixext_versions_2663498 ON #__pixext_versions_2663498.`pixext_version_id` = a.`pixext_extension_version_id`')
			->join('LEFT', '#__pixext_extensions pe ON #__pixext_versions_2663498.`pixext_extension_id` = pe.pixext_extension_id');
		// Join over the foreign key 'pixext_package_version_id'
		//$query->select('`#__pixext_package_versions_2663499`.`pixext_package_version_id` AS packageversions_fk_value_2663499');
		$query->select("CONCAT( pp.packagename, ' ', #__pixext_package_versions_2663499.major, '.', #__pixext_package_versions_2663499.minor, '.', #__pixext_package_versions_2663499.patch ) AS packageversions_fk_value_2663499");
		$query->join('LEFT', '#__pixext_package_versions AS #__pixext_package_versions_2663499 ON #__pixext_package_versions_2663499.`pixext_package_version_id` = a.`pixext_package_version_id`')
			->join('LEFT', '#__pixext_packages pp ON #__pixext_package_versions_2663499.`pixext_package_id` = pp.pixext_package_id');

		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.state IN (0, 1))');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'pixext_package_extension_version_id:') === 0)
			{
				$query->where('a.pixext_package_extension_version_id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				
			}
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();

		foreach ($items as $oneItem) {

			if (isset($oneItem->pixext_extension_version_id))
			{
				$values = explode(',', $oneItem->pixext_extension_version_id);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('`#__pixext_versions_2663498`.`pixext_version_id`')
							->from($db->quoteName('#__pixext_versions', '#__pixext_versions_2663498'))
							->where($db->quoteName('pixext_version_id') . ' = '. $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->pixext_version_id;
					}
				}

			$oneItem->pixext_extension_version_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->pixext_extension_version_id;

			}

			if (isset($oneItem->pixext_package_version_id))
			{
				$values = explode(',', $oneItem->pixext_package_version_id);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('`#__pixext_package_versions_2663499`.`pixext_package_version_id`')
							->from($db->quoteName('#__pixext_package_versions', '#__pixext_package_versions_2663499'))
							->where($db->quoteName('pixext_package_version_id') . ' = '. $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->pixext_package_version_id;
					}
				}

			$oneItem->pixext_package_version_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->pixext_package_version_id;

			}
		}
		return $items;
	}
}

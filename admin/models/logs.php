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
class PixextModelLogs extends JModelList
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
				'pixext_log_id', 'a.`pixext_log_id`',
				'pixext_log_type_id', 'a.`pixext_log_type_id`',
				'pixext_log_from_id', 'a.`pixext_log_from_id`',
				'pixext_license_id', 'a.`pixext_license_id`',
				'when', 'a.`when`',
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
		parent::populateState('a.id', 'asc');
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
		$query->from('`#__pixext_logs` AS a');

		// Join over the foreign key 'pixext_log_type_id'
		$query->select('`#__pixext_log_types_2663429`.`type` AS logtypes_fk_value_2663429');
		$query->join('LEFT', '#__pixext_log_types AS #__pixext_log_types_2663429 ON #__pixext_log_types_2663429.`pixext_log_type_id` = a.`pixext_log_type_id`');
		// Join over the foreign key 'pixext_log_from_id'
		$query->select('`#__pixext_log_ips_2663430`.`ip` AS ips_fk_value_2663430');
		$query->join('LEFT', '#__pixext_log_ips AS #__pixext_log_ips_2663430 ON #__pixext_log_ips_2663430.`pixext_log_ip_id` = a.`pixext_log_from_id`');
		// Join over the foreign key 'pixext_license_id'
		$query->select('`#__pixext_licenses_2663431`.`licence_key` AS licenses_fk_value_2663431');
		$query->join('LEFT', '#__pixext_licenses AS #__pixext_licenses_2663431 ON #__pixext_licenses_2663431.`pixext_license_id` = a.`pixext_license_id`');

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
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

			if (isset($oneItem->pixext_log_type_id))
			{
				$values = explode(',', $oneItem->pixext_log_type_id);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('`#__pixext_log_types_2663429`.`type`')
							->from($db->quoteName('#__pixext_log_types', '#__pixext_log_types_2663429'))
							->where($db->quoteName('pixext_log_type_id') . ' = '. $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->type;
					}
				}

			$oneItem->pixext_log_type_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->pixext_log_type_id;

			}

			if (isset($oneItem->pixext_log_from_id))
			{
				$values = explode(',', $oneItem->pixext_log_from_id);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('`#__pixext_log_ips_2663430`.`ip`')
							->from($db->quoteName('#__pixext_log_ips', '#__pixext_log_ips_2663430'))
							->where($db->quoteName('pixext_log_ip_id') . ' = '. $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->ip;
					}
				}

			$oneItem->pixext_log_from_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->pixext_log_from_id;

			}

			if (isset($oneItem->pixext_license_id))
			{
				$values = explode(',', $oneItem->pixext_license_id);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('`#__pixext_licenses_2663431`.`licence_key`')
							->from($db->quoteName('#__pixext_licenses', '#__pixext_licenses_2663431'))
							->where($db->quoteName('pixext_license_id') . ' = '. $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->licence_key;
					}
				}

			$oneItem->pixext_license_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->pixext_license_id;

			}
		}
		return $items;
	}
}

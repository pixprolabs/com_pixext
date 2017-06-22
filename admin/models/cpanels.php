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
jimport('joomla.application.component.helper');

/**
 * Methods supporting a list of Pixext records.
 *
 * @since  1.6
 */
class PixextModelCpanels extends JModelList
{
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
		$db	= $this->getDbo();
		$query	= $db->getQuery(true);

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
	
	public function ping()
	{
		$params = JComponentHelper::getParams( 'com_pixext' );
		
		//print_r( $params->get( 'key' ) ); die();
		$url = $params->get( 'url' ).'/pixext/settings/'.$params->get( 'key' );
		$headers = array( 'Content-Type: application/json' );
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 0 );
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		if( count( $headers ) )
			curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
		$json = curl_exec( $ch );
		$httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		$curl_error = curl_error( $ch );
		curl_close( $ch );
		//print_r($httpCode);
		if( $httpCode == 200 )
		{
			$data = json_decode( $json );
			//print_r( $data ); die();
			//return $data;
			return true;
		}
		else
		{
			print_r( $curl_error );
			return false;
		}
	}
}

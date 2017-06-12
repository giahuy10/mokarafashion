<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Inventory
 * @author     Eddy Nguyen <contact@eddynguyen.com>
 * @copyright  2017 Eddy Nguyen
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Inventory records.
 *
 * @since  1.6
 */
class InventoryModelSales extends JModelList
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
				'id', 'a.`id`',
				'ordering', 'a.`ordering`',
				'state', 'a.`state`',
				'created_by', 'a.`created_by`',
				'modified_by', 'a.`modified_by`',
				'user_id', 'a.`user_id`',
				'created', 'a.`created`',
				'total', 'a.`total`',
				'discount', 'a.`discount`',
				'status', 'a.`status`',
				'comment', 'a.`comment`',
				'note', 'a.`note`',
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
		// Filtering created
		$this->setState('filter.created.from', $app->getUserStateFromRequest($this->context.'.filter.created.from', 'filter_from_created', '', 'string'));
		$this->setState('filter.created.to', $app->getUserStateFromRequest($this->context.'.filter.created.to', 'filter_to_created', '', 'string'));

		// Filtering status
		$this->setState('filter.status', $app->getUserStateFromRequest($this->context.'.filter.status', 'filter_status', '', 'string'));


		// Load the parameters.
		$params = JComponentHelper::getParams('com_inventory');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.user_id', 'asc');
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
		$query->from('`#__inventory_sales` AS a');

		// Join over the users for the checked out user
		$query->select("uc.name AS uEditor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");

		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'modified_by'
		$query->select('`modified_by`.name AS `modified_by`');
		$query->join('LEFT', '#__users AS `modified_by` ON `modified_by`.id = a.`modified_by`');

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
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.user_id LIKE ' . $search . ' )');
			}
		}


		//Filtering created
		$filter_created_from = $this->state->get("filter.created.from");

		if ($filter_created_from !== null && !empty($filter_created_from))
		{
			$query->where("a.`created` >= '".$db->escape($filter_created_from)."'");
		}
		$filter_created_to = $this->state->get("filter.created.to");

		if ($filter_created_to !== null  && !empty($filter_created_to))
		{
			$query->where("a.`created` <= '".$db->escape($filter_created_to)."'");
		}

		//Filtering status
		$filter_status = $this->state->get("filter.status");
		if ($filter_status !== null && !empty($filter_status))
		{
			$query->where("a.`status` = '".$db->escape($filter_status)."'");
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

			if (isset($oneItem->user_id)) {
				$values = explode(',', $oneItem->user_id);

				$textValue = array();
				foreach ($values as $value)
				{
					if (!empty($value))
					{
						$db = JFactory::getDbo();
						$query = "SELECT id, CONCAT(name,'-',email) as value FROM #__users where id =".$oneItem->user_id;
						$db->setQuery($query);
						$results = $db->loadObject();
						if ($results) {
							$textValue[] = $results->value;
						}
					}
				}

			$oneItem->user_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->user_id;

			}
			$oneItem->order_number = "MKR-".sprintf("%06d",$oneItem->id);
					$oneItem->status = JText::_('COM_INVENTORY_SALES_STATUS_OPTION_' . strtoupper($oneItem->status));
		}
		return $items;
	}
}

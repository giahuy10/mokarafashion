<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Inventory
 * @author     sugar lead <anjakahuy@gmail.com>
 * @copyright  2017 sugar lead
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Inventory records.
 *
 * @since  1.6
 */
class InventoryModelCoupons extends JModelList
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
				'coupon_code', 'a.`coupon_code`',
				'coupon_type', 'a.`coupon_type`',
				'coupon_value', 'a.`coupon_value`',
				'coupon_for_categories', 'a.`coupon_for_categories`',
				'coupon_for_products', 'a.`coupon_for_products`',
				'coupon_for_order', 'a.`coupon_for_order`',
				'coupon_limit', 'a.`coupon_limit`',
				'coupon_for_group_user', 'a.`coupon_for_group_user`',
				'coupon_one_time', 'a.`coupon_one_time`',
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
		// Filtering coupon_type
		$this->setState('filter.coupon_type', $app->getUserStateFromRequest($this->context.'.filter.coupon_type', 'filter_coupon_type', '', 'string'));


		// Load the parameters.
		$params = JComponentHelper::getParams('com_inventory');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.coupon_code', 'asc');
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
		$query->from('`#__inventory_coupon` AS a');

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
				$query->where('( a.coupon_code LIKE ' . $search . ' )');
			}
		}


		//Filtering coupon_type
		$filter_coupon_type = $this->state->get("filter.coupon_type");
		if ($filter_coupon_type !== null && !empty($filter_coupon_type))
		{
			$query->where("a.`coupon_type` = '".$db->escape($filter_coupon_type)."'");
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
					$oneItem->coupon_type = ($oneItem->coupon_type == '') ? '' : JText::_('COM_INVENTORY_COUPONS_COUPON_TYPE_OPTION_' . strtoupper($oneItem->coupon_type));

			if (isset($oneItem->coupon_for_categories)) {
				$values = explode(',', $oneItem->coupon_for_categories);

				$textValue = array();
				foreach ($values as $value)
				{
					if (!empty($value))
					{
						$db = JFactory::getDbo();
						$query = "SELECT id, title FROM #__categories WHERE published = 1 and parent_id = 20";
						$db->setQuery($query);
						$results = $db->loadObject();
						if ($results) {
							$textValue[] = $results->title;
						}
					}
				}

			$oneItem->coupon_for_categories = !empty($textValue) ? implode(', ', $textValue) : $oneItem->coupon_for_categories;

			}

			if (isset($oneItem->coupon_for_products)) {
				$values = explode(',', $oneItem->coupon_for_products);

				$textValue = array();
				foreach ($values as $value)
				{
					if (!empty($value))
					{
						$db = JFactory::getDbo();
						$query = "SELECT id, title FROM #__content WHERE state =1 and catid in (20,21,22,23,24,25)";
						$db->setQuery($query);
						$results = $db->loadObject();
						if ($results) {
							$textValue[] = $results->title;
						}
					}
				}

			$oneItem->coupon_for_products = !empty($textValue) ? implode(', ', $textValue) : $oneItem->coupon_for_products;

			}

			if ( isset($oneItem->coupon_for_group_user) ) {

				// Get the title of that particular user group
					$title = InventoryHelper::getGroupNameByGroupId($oneItem->coupon_for_group_user);
					$oneItem->coupon_for_group_user = !empty($title) ? $title : $oneItem->coupon_for_group_user;
				}
					$oneItem->coupon_one_time = ($oneItem->coupon_one_time == '') ? '' : JText::_('COM_INVENTORY_COUPONS_COUPON_ONE_TIME_OPTION_' . strtoupper($oneItem->coupon_one_time));
		}
		return $items;
	}
}

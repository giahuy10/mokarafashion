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
class InventoryModelProducts extends JModelList
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
				'id', 'a.id',
				'ordering', 'a.ordering',
				'state', 'a.state',
				'created_by', 'a.created_by',
				'modified_by', 'a.modified_by',
				'category', 'a.category',
				'name', 'a.name',
				'code', 'a.code',
				'price', 'a.price',
				'old_price', 'a.old_price',
				'color', 'a.color',
				'material', 'a.material',
				'neck', 'a.neck',
				'sleeve', 'a.sleeve',
				'type', 'a.type',
				'skirt', 'a.skirt',
				'input_price', 'a.input_price',
				'size_s', 'a.size_s',
				'size_m', 'a.size_m',
				'size_l', 'a.size_l',
				'size_xl', 'a.size_xl',
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
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app  = JFactory::getApplication();
		$list = $app->getUserState($this->context . '.list');

		$ordering  = isset($list['filter_order'])     ? $list['filter_order']     : null;
		$direction = isset($list['filter_order_Dir']) ? $list['filter_order_Dir'] : null;

		$list['limit']     = (int) JFactory::getConfig()->get('list_limit', 20);
		$list['start']     = $app->input->getInt('start', 0);
		$list['ordering']  = $ordering;
		$list['direction'] = $direction;

		$app->setUserState($this->context . '.list', $list);
		$app->input->set('list', null);

		// List state information.
		parent::populateState($ordering, $direction);

        $app = JFactory::getApplication();

        $ordering  = $app->getUserStateFromRequest($this->context . '.ordercol', 'filter_order', $ordering);
        $direction = $app->getUserStateFromRequest($this->context . '.orderdirn', 'filter_order_Dir', $ordering);

        $this->setState('list.ordering', $ordering);
        $this->setState('list.direction', $direction);

        $start = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0, 'int');
        $limit = $app->getUserStateFromRequest($this->context . '.limit', 'limit', 0, 'int');

        if ($limit == 0)
        {
            $limit = $app->get('list_limit', 0);
        }

        $this->setState('list.limit', $limit);
        $this->setState('list.start', $start);
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
		$query
			->select(
				$this->getState(
					'list.select', 'DISTINCT a.*'
				)
			);

		$query->from('`#__inventory_products` AS a');
		
		// Join over the users for the checked out user.
		$query->select('uc.name AS uEditor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		// Join over the created by field 'modified_by'
		$query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');
		
		if (!JFactory::getUser()->authorise('core.edit', 'com_inventory'))
		{
			$query->where('a.state = 1');
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
				$query->where('( a.name LIKE ' . $search . '  OR  a.code LIKE ' . $search . ' )');
			}
		}
		

		// Filtering color
		$filter_color = $this->state->get("filter.color");
		if ($filter_color != '') {
			$query->where("a.color LIKE '%\"".$db->escape($filter_color)."\"%'");
		}

		// Filtering material
		$filter_material = $this->state->get("filter.material");
		if ($filter_material != '') {
			$query->where("a.material LIKE '%\"".$db->escape($filter_material)."\"%'");
		}

		// Filtering neck
		$filter_neck = $this->state->get("filter.neck");
		if ($filter_neck != '') {
			$query->where("a.neck = '".$db->escape($filter_neck)."'");
		}
		// Filtering category
		$filter_category = $this->state->get("filter.category");
		if ($filter_category != '') {
			$query->where("a.category = '".$db->escape($filter_category)."'");
		}

		// Filtering sleeve
		$filter_sleeve = $this->state->get("filter.sleeve");
		if ($filter_sleeve != '' && $filter_category != 23) {
			$query->where("a.sleeve = '".$db->escape($filter_sleeve)."'");
		}
		// Filtering skirt
		$filter_skirt = $this->state->get("filter.skirt");
		if ($filter_skirt != '' && $filter_category == 23) {
			$query->where("a.skirt = '".$db->escape($filter_skirt)."'");
		}
		// Filtering price
		$filter_price = $this->state->get("filter.price");
		if ($filter_price != '') {
			$query->where("a.price BETWEEN ".$filter_price);
		}

		// Filtering type
		$filter_type = $this->state->get("filter.type");
		if ($filter_type != '') {
			$query->where("a.type = '".$db->escape($filter_type)."'");
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'ordering');
		$orderDirn = $this->state->get('list.direction', 'asc');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
		
		foreach ($items as $item)
		{


			if (isset($item->category))
			{
				$values = explode(',', $item->category);

				$textValue = array();
				foreach ($values as $value)
				{
					if (!empty($value))
					{
						$db = JFactory::getDbo();
						$query = "SELECT id, title FROM #__categories WHERE parent_id=20 and published=1 HAVING id LIKE '" . $value . "'";
						$db->setQuery($query);
						$results = $db->loadObject();
						if ($results)
						{
							$textValue[] = $results->title;
						}
					}
				}

			$item->category = !empty($textValue) ? implode(', ', $textValue) : $item->category;

			}
				// Get the title of every option selected.
				$options      = json_decode($item->color);
				$options_text = array();

				foreach ((array) $options as $option)
				{
					$options_text[] = JText::_('COM_INVENTORY_PRODUCTS_COLOR_OPTION_' . strtoupper($option));
				}

				$item->color = !empty($options_text) ? implode(',', $options_text) : $item->color;
				// Get the title of every option selected.
				$options      = json_decode($item->material);
				$options_text = array();

				foreach ((array) $options as $option)
				{
					$options_text[] = JText::_('COM_INVENTORY_PRODUCTS_MATERIAL_OPTION_' . strtoupper($option));
				}

				$item->material = !empty($options_text) ? implode(',', $options_text) : $item->material;
					$item->neck = JText::_('COM_INVENTORY_PRODUCTS_NECK_OPTION_' . strtoupper($item->neck));
					$item->sleeve = JText::_('COM_INVENTORY_PRODUCTS_SLEEVE_OPTION_' . strtoupper($item->sleeve));
					$item->type = JText::_('COM_INVENTORY_PRODUCTS_TYPE_OPTION_' . strtoupper($item->type));
				// Get the title of every option selected.
				$options      = json_decode($item->skirt);
				$options_text = array();

				foreach ((array) $options as $option)
				{
					$options_text[] = JText::_('COM_INVENTORY_PRODUCTS_SKIRT_OPTION_' . strtoupper($option));
				}

				$item->skirt = !empty($options_text) ? implode(',', $options_text) : $item->skirt;
		}

		return $items;
	}

	/**
	 * Overrides the default function to check Date fields format, identified by
	 * "_dateformat" suffix, and erases the field if it's not correct.
	 *
	 * @return void
	 */
	protected function loadFormData()
	{
		$app              = JFactory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;

		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && $this->isValidDate($value) == null)
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}

		if ($error_dateformat)
		{
			$app->enqueueMessage(JText::_("COM_INVENTORY_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in a specified format (YYYY-MM-DD)
	 *
	 * @param   string  $date  Date to be checked
	 *
	 * @return bool
	 */
	private function isValidDate($date)
	{
		$date = str_replace('/', '-', $date);
		return (date_create($date)) ? JFactory::getDate($date)->format("Y-m-d") : null;
	}
}

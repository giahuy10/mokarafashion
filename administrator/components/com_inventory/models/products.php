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
				'id', 'a.`id`',
				'ordering', 'a.`ordering`',
				'state', 'a.`state`',
				'created_by', 'a.`created_by`',
				'modified_by', 'a.`modified_by`',
				'category', 'a.`category`',
				'name', 'a.`name`',
				'code', 'a.`code`',
				'price', 'a.`price`',
				'old_price', 'a.`old_price`',
				'color', 'a.`color`',
				'material', 'a.`material`',
				'neck', 'a.`neck`',
				'sleeve', 'a.`sleeve`',
				'type', 'a.`type`',
				'skirt', 'a.`skirt`',
				'input_price', 'a.`input_price`',
				'size_s', 'a.`size_s`',
				'size_m', 'a.`size_m`',
				'size_l', 'a.`size_l`',
				'size_xl', 'a.`size_xl`',
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
		// Filtering color
		$this->setState('filter.color', $app->getUserStateFromRequest($this->context.'.filter.color', 'filter_color', '', 'string'));

		// Filtering material
		$this->setState('filter.material', $app->getUserStateFromRequest($this->context.'.filter.material', 'filter_material', '', 'string'));

		// Filtering neck
		$this->setState('filter.neck', $app->getUserStateFromRequest($this->context.'.filter.neck', 'filter_neck', '', 'string'));

		// Filtering sleeve
		$this->setState('filter.sleeve', $app->getUserStateFromRequest($this->context.'.filter.sleeve', 'filter_sleeve', '', 'string'));

		// Filtering type
		$this->setState('filter.type', $app->getUserStateFromRequest($this->context.'.filter.type', 'filter_type', '', 'string'));


		// Load the parameters.
		$params = JComponentHelper::getParams('com_inventory');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.category', 'asc');
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
		$query->from('`#__inventory_products` AS a');

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
				$query->where('( a.category LIKE ' . $search . '  OR  a.name LIKE ' . $search . '  OR  a.code LIKE ' . $search . '  OR  a.price LIKE ' . $search . '  OR  a.color LIKE ' . $search . '  OR  a.material LIKE ' . $search . '  OR  a.neck LIKE ' . $search . '  OR  a.sleeve LIKE ' . $search . '  OR  a.type LIKE ' . $search . ' )');
			}
		}


		//Filtering color
		$filter_color = $this->state->get("filter.color");
		if ($filter_color !== null && !empty($filter_color))
		{
			$query->where("a.`color` LIKE '%\"".$db->escape($filter_color)."\"%'");
		}

		//Filtering material
		$filter_material = $this->state->get("filter.material");
		if ($filter_material !== null && !empty($filter_material))
		{
			$query->where("a.`material` LIKE '%\"".$db->escape($filter_material)."\"%'");
		}

		//Filtering neck
		$filter_neck = $this->state->get("filter.neck");
		if ($filter_neck !== null && !empty($filter_neck))
		{
			$query->where("a.`neck` = '".$db->escape($filter_neck)."'");
		}

		//Filtering sleeve
		$filter_sleeve = $this->state->get("filter.sleeve");
		if ($filter_sleeve !== null && !empty($filter_sleeve))
		{
			$query->where("a.`sleeve` = '".$db->escape($filter_sleeve)."'");
		}

		//Filtering type
		$filter_type = $this->state->get("filter.type");
		if ($filter_type !== null && !empty($filter_type))
		{
			$query->where("a.`type` = '".$db->escape($filter_type)."'");
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

			if (isset($oneItem->category)) {
				$values = explode(',', $oneItem->category);

				$textValue = array();
				foreach ($values as $value)
				{
					if (!empty($value))
					{
						$db = JFactory::getDbo();
						$query = "SELECT id, title FROM #__categories WHERE parent_id=20 and published=1";
						$db->setQuery($query);
						$results = $db->loadObject();
						if ($results) {
							$textValue[] = $results->title;
						}
					}
				}

			$oneItem->category = !empty($textValue) ? implode(', ', $textValue) : $oneItem->category;

			}

				// Get the title of every option selected.

				$options = json_decode($oneItem->color);

				$options_text = array();

				foreach ((array) $options as $option)
				{
					$options_text[] = JText::_('COM_INVENTORY_PRODUCTS_COLOR_OPTION_' . strtoupper($option));
				}

				$oneItem->color = !empty($options_text) ? implode(',', $options_text) : $oneItem->color;

				// Get the title of every option selected.

				$options = json_decode($oneItem->material);

				$options_text = array();

				foreach ((array) $options as $option)
				{
					$options_text[] = JText::_('COM_INVENTORY_PRODUCTS_MATERIAL_OPTION_' . strtoupper($option));
				}

				$oneItem->material = !empty($options_text) ? implode(',', $options_text) : $oneItem->material;
					$oneItem->neck = JText::_('COM_INVENTORY_PRODUCTS_NECK_OPTION_' . strtoupper($oneItem->neck));
					$oneItem->sleeve = JText::_('COM_INVENTORY_PRODUCTS_SLEEVE_OPTION_' . strtoupper($oneItem->sleeve));
					$oneItem->type = JText::_('COM_INVENTORY_PRODUCTS_TYPE_OPTION_' . strtoupper($oneItem->type));

				// Get the title of every option selected.

				$options = json_decode($oneItem->skirt);

				$options_text = array();

				foreach ((array) $options as $option)
				{
					$options_text[] = JText::_('COM_INVENTORY_PRODUCTS_SKIRT_OPTION_' . strtoupper($option));
				}

				$oneItem->skirt = !empty($options_text) ? implode(',', $options_text) : $oneItem->skirt;
		}
		return $items;
	}
}

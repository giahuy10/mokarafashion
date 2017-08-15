<?php

/**
 * @version    CVS: 1.0.2
 * @package    Com_Product
 * @author     Eddy Nguyen <contact@eddynguyen.com>
 * @copyright  2017 Eddy Nguyen
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Product records.
 *
 * @since  1.6
 */
class ProductModelItems extends JModelList
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
				'title', 'a.`title`',
				'alias', 'a.`alias`',
				'catid', 'a.`catid`',
				'code', 'a.`code`',
				'price', 'a.`price`',
				'old_price', 'a.`old_price`',
				'intro', 'a.`intro`',
				'description', 'a.`description`',
				'combo', 'a.`combo`',
				'combo_products', 'a.`combo_products`',
				'hot_deal', 'a.`hot_deal`',
				'deal_price', 'a.`deal_price`',
				'deal_from', 'a.`deal_from`',
				'deal_to', 'a.`deal_to`',
				'deal_day', 'a.`deal_day`',
				'image_1', 'a.`image_1`',
				'color', 'a.`color`',
				'collar', 'a.`collar`',
				'sleeve', 'a.`sleeve`',
				'type', 'a.`type`',
				'shape', 'a.`shape`',
				'tags', 'a.`tags`',
				'images', 'a.`images`',
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
		// Filtering catid
		$this->setState('filter.catid', $app->getUserStateFromRequest($this->context.'.filter.catid', 'filter_catid', '', 'string'));

		// Filtering combo
		$this->setState('filter.combo', $app->getUserStateFromRequest($this->context.'.filter.combo', 'filter_combo', '', 'string'));

		// Filtering hot_deal
		$this->setState('filter.hot_deal', $app->getUserStateFromRequest($this->context.'.filter.hot_deal', 'filter_hot_deal', '', 'string'));

		// Filtering color
		$this->setState('filter.color', $app->getUserStateFromRequest($this->context.'.filter.color', 'filter_color', '', 'string'));

		// Filtering collar
		$this->setState('filter.collar', $app->getUserStateFromRequest($this->context.'.filter.collar', 'filter_collar', '', 'string'));

		// Filtering sleeve
		$this->setState('filter.sleeve', $app->getUserStateFromRequest($this->context.'.filter.sleeve', 'filter_sleeve', '', 'string'));

		// Filtering type
		$this->setState('filter.type', $app->getUserStateFromRequest($this->context.'.filter.type', 'filter_type', '', 'string'));

		// Filtering shape
		$this->setState('filter.shape', $app->getUserStateFromRequest($this->context.'.filter.shape', 'filter_shape', '', 'string'));


		// Load the parameters.
		$params = JComponentHelper::getParams('com_product');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.title', 'asc');
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
		$query->from('`#__product_items` AS a');

		// Join over the users for the checked out user
		$query->select("uc.name AS uEditor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");

		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'modified_by'
		$query->select('`modified_by`.name AS `modified_by`');
		$query->join('LEFT', '#__users AS `modified_by` ON `modified_by`.id = a.`modified_by`');
		// Join over the category 'catid'
		$query->select('`catid`.title AS `catid`');
		$query->join('LEFT', '#__categories AS `catid` ON `catid`.id = a.`catid`');

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
				$query->where('( a.title LIKE ' . $search . ' )');
			}
		}


		//Filtering catid
		$filter_catid = $this->state->get("filter.catid");
		if ($filter_catid !== null && !empty($filter_catid))
		{
			$query->where("a.`catid` = '".$db->escape($filter_catid)."'");
		}

		//Filtering combo
		$filter_combo = $this->state->get("filter.combo");
		if ($filter_combo !== null && !empty($filter_combo))
		{
			$query->where("a.`combo` = '".$db->escape($filter_combo)."'");
		}

		//Filtering hot_deal
		$filter_hot_deal = $this->state->get("filter.hot_deal");
		if ($filter_hot_deal !== null && !empty($filter_hot_deal))
		{
			$query->where("a.`hot_deal` = '".$db->escape($filter_hot_deal)."'");
		}

		//Filtering color
		$filter_color = $this->state->get("filter.color");
		if ($filter_color !== null && !empty($filter_color))
		{
			$query->where("a.`color` = '".$db->escape($filter_color)."'");
		}

		//Filtering collar
		$filter_collar = $this->state->get("filter.collar");
		if ($filter_collar !== null && !empty($filter_collar))
		{
			$query->where("a.`collar` = '".$db->escape($filter_collar)."'");
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

		//Filtering shape
		$filter_shape = $this->state->get("filter.shape");
		if ($filter_shape !== null && !empty($filter_shape))
		{
			$query->where("a.`shape` = '".$db->escape($filter_shape)."'");
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
					$oneItem->combo = ($oneItem->combo == '') ? '' : JText::_('COM_PRODUCT_ITEMS_COMBO_OPTION_' . strtoupper($oneItem->combo));

			if (isset($oneItem->combo_products)) {
				$values = explode(',', $oneItem->combo_products);

				$textValue = array();
				foreach ($values as $value)
				{
					if (!empty($value))
					{
						$db = JFactory::getDbo();
						$query = "SELECT id, title FROM #__product_items WHERE state = 1";
						$db->setQuery($query);
						$results = $db->loadObject();
						if ($results) {
							$textValue[] = $results->title;
						}
					}
				}

			$oneItem->combo_products = !empty($textValue) ? implode(', ', $textValue) : $oneItem->combo_products;

			}
					$oneItem->hot_deal = ($oneItem->hot_deal == '') ? '' : JText::_('COM_PRODUCT_ITEMS_HOT_DEAL_OPTION_' . strtoupper($oneItem->hot_deal));
					$oneItem->deal_day = JText::_('COM_PRODUCT_ITEMS_DEAL_DAY_OPTION_' . strtoupper($oneItem->deal_day));

				// Get the title of every option selected.

				$options = explode(',', $oneItem->color);

				$options_text = array();

				foreach ((array) $options as $option)
				{
					$options_text[] = JText::_('COM_PRODUCT_ITEMS_COLOR_OPTION_' . strtoupper($option));
				}

				$oneItem->color = !empty($options_text) ? implode(',', $options_text) : $oneItem->color;

				// Get the title of every option selected.

				$options = explode(',', $oneItem->collar);

				$options_text = array();

				foreach ((array) $options as $option)
				{
					$options_text[] = JText::_('COM_PRODUCT_ITEMS_COLLAR_OPTION_' . strtoupper($option));
				}

				$oneItem->collar = !empty($options_text) ? implode(',', $options_text) : $oneItem->collar;

				// Get the title of every option selected.

				$options = explode(',', $oneItem->sleeve);

				$options_text = array();

				foreach ((array) $options as $option)
				{
					$options_text[] = JText::_('COM_PRODUCT_ITEMS_SLEEVE_OPTION_' . strtoupper($option));
				}

				$oneItem->sleeve = !empty($options_text) ? implode(',', $options_text) : $oneItem->sleeve;

				// Get the title of every option selected.

				$options = explode(',', $oneItem->type);

				$options_text = array();

				foreach ((array) $options as $option)
				{
					$options_text[] = JText::_('COM_PRODUCT_ITEMS_TYPE_OPTION_' . strtoupper($option));
				}

				$oneItem->type = !empty($options_text) ? implode(',', $options_text) : $oneItem->type;

				// Get the title of every option selected.

				$options = explode(',', $oneItem->shape);

				$options_text = array();

				foreach ((array) $options as $option)
				{
					$options_text[] = JText::_('COM_PRODUCT_ITEMS_SHAPE_OPTION_' . strtoupper($option));
				}

				$oneItem->shape = !empty($options_text) ? implode(',', $options_text) : $oneItem->shape;

			if ( isset($oneItem->tags) ) {
				// Catch the item tags (string with ',' coma glue)
				$tags = explode(",",$oneItem->tags);

				$db = JFactory::getDbo();
					$namedTags = array(); // Cleaning and initalization of named tags array

					// Get the tag names of each tag id
					foreach ($tags as $tag) {

						$query = $db->getQuery(true);
						$query->select("title");
						$query->from('`#__tags`');
						$query->where( "id=" . intval($tag) );

						$db->setQuery($query);
						$row = $db->loadObjectList();

						// Read the row and get the tag name (title)
						if (!is_null($row)) {
							foreach ($row as $value) {
								if ( $value && isset($value->title) ) {
									$namedTags[] = trim($value->title);
								}
							}
						}

					}

					// Finally replace the data object with proper information
					$oneItem->tags = !empty($namedTags) ? implode(', ',$namedTags) : $oneItem->tags;
				}
		}
		return $items;
	}
}

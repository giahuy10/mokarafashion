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
				'id', 'a.id',
				'ordering', 'a.ordering',
				'state', 'a.state',
				'created_by', 'a.created_by',
				'modified_by', 'a.modified_by',
				'title', 'a.title',
				'alias', 'a.alias',
				'catid', 'a.catid',
				'code', 'a.code',
				'price', 'a.price',
				'old_price', 'a.old_price',
				'intro', 'a.intro',
				'description', 'a.description',
				'combo', 'a.combo',
				'combo_products', 'a.combo_products',
				'hot_deal', 'a.hot_deal',
				'deal_price', 'a.deal_price',
				'deal_from', 'a.deal_from',
				'deal_to', 'a.deal_to',
				'deal_day', 'a.deal_day',
				'image_1', 'a.image_1',
				'color', 'a.color',
				'collar', 'a.collar',
				'sleeve', 'a.sleeve',
				'type', 'a.type',
				'shape', 'a.shape',
				'tags', 'a.tags',
				'images', 'a.images',
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

		$query->from('`#__product_items` AS a');
		
		// Join over the users for the checked out user.
		$query->select('uc.name AS uEditor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		// Join over the created by field 'modified_by'
		$query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');
		// Join over the category 'catid'
		$query->select('categories_2755863.title AS catid');
		$query->join('LEFT', '#__categories AS categories_2755863 ON categories_2755863.id = a.catid');
		
		if (!JFactory::getUser()->authorise('core.edit', 'com_product'))
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
				$query->where('( a.title LIKE ' . $search . ' )');
			}
		}
		

		// Filtering catid
		$filter_catid = $this->state->get("filter.catid");
		if ($filter_catid)
		{
			$query->where("a.catid = '".$db->escape($filter_catid)."'");
		}

		// Filtering combo
		$filter_combo = $this->state->get("filter.combo");
		if ($filter_combo != '') {
			$query->where("a.combo = '".$db->escape($filter_combo)."'");
		}

		// Filtering hot_deal
		$filter_hot_deal = $this->state->get("filter.hot_deal");
		if ($filter_hot_deal != '') {
			$query->where("a.hot_deal = '".$db->escape($filter_hot_deal)."'");
		}

		// Filtering color
		$filter_color = $this->state->get("filter.color");
		if ($filter_color != '') {
			$query->where("a.color = '".$db->escape($filter_color)."'");
		}

		// Filtering collar
		$filter_collar = $this->state->get("filter.collar");
		if ($filter_collar != '') {
			$query->where("a.collar = '".$db->escape($filter_collar)."'");
		}

		// Filtering sleeve
		$filter_sleeve = $this->state->get("filter.sleeve");
		if ($filter_sleeve != '') {
			$query->where("a.sleeve = '".$db->escape($filter_sleeve)."'");
		}

		// Filtering type
		$filter_type = $this->state->get("filter.type");
		if ($filter_type != '') {
			$query->where("a.type = '".$db->escape($filter_type)."'");
		}

		// Filtering shape
		$filter_shape = $this->state->get("filter.shape");
		if ($filter_shape != '') {
			$query->where("a.shape = '".$db->escape($filter_shape)."'");
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


			if (isset($item->catid))
			{

				// Get the title of that particular template
					$title = ProductHelpersProduct::getCategoryNameByCategoryId($item->catid);

					// Finally replace the data object with proper information
					$item->catid = !empty($title) ? $title : $item->catid;
				}
				$item->combo = empty($item->combo) ? '' : JText::_('COM_PRODUCT_ITEMS_COMBO_OPTION_' . strtoupper($item->combo));

			if (isset($item->combo_products))
			{
				$values = explode(',', $item->combo_products);

				$textValue = array();
				foreach ($values as $value)
				{
					if (!empty($value))
					{
						$db = JFactory::getDbo();
						$query = "SELECT id, title FROM #__product_items WHERE state = 1 HAVING id LIKE '" . $value . "'";
						$db->setQuery($query);
						$results = $db->loadObject();
						if ($results)
						{
							$textValue[] = $results->title;
						}
					}
				}

			$item->combo_products = !empty($textValue) ? implode(', ', $textValue) : $item->combo_products;

			}
				$item->hot_deal = empty($item->hot_deal) ? '' : JText::_('COM_PRODUCT_ITEMS_HOT_DEAL_OPTION_' . strtoupper($item->hot_deal));
					$item->deal_day = JText::_('COM_PRODUCT_ITEMS_DEAL_DAY_OPTION_' . strtoupper($item->deal_day));

				// Get the title of every option selected.
				$options      = explode(',',$item->color);
				$options_text = array();

				foreach ((array) $options as $option)
				{
					$options_text[] = JText::_('COM_PRODUCT_ITEMS_COLOR_OPTION_' . strtoupper($option));
				}

				$item->color = !empty($options_text) ? implode(',', $options_text) : $item->color;

				// Get the title of every option selected.
				$options      = explode(',',$item->collar);
				$options_text = array();

				foreach ((array) $options as $option)
				{
					$options_text[] = JText::_('COM_PRODUCT_ITEMS_COLLAR_OPTION_' . strtoupper($option));
				}

				$item->collar = !empty($options_text) ? implode(',', $options_text) : $item->collar;

				// Get the title of every option selected.
				$options      = explode(',',$item->sleeve);
				$options_text = array();

				foreach ((array) $options as $option)
				{
					$options_text[] = JText::_('COM_PRODUCT_ITEMS_SLEEVE_OPTION_' . strtoupper($option));
				}

				$item->sleeve = !empty($options_text) ? implode(',', $options_text) : $item->sleeve;

				// Get the title of every option selected.
				$options      = explode(',',$item->type);
				$options_text = array();

				foreach ((array) $options as $option)
				{
					$options_text[] = JText::_('COM_PRODUCT_ITEMS_TYPE_OPTION_' . strtoupper($option));
				}

				$item->type = !empty($options_text) ? implode(',', $options_text) : $item->type;

				// Get the title of every option selected.
				$options      = explode(',',$item->shape);
				$options_text = array();

				foreach ((array) $options as $option)
				{
					$options_text[] = JText::_('COM_PRODUCT_ITEMS_SHAPE_OPTION_' . strtoupper($option));
				}

				$item->shape = !empty($options_text) ? implode(',', $options_text) : $item->shape;

			if (isset($item->tags))
			{
				// Catch the item tags (string with ',' coma glue)
				$tags = explode(",", $item->tags);
				$db = JFactory::getDbo();

				// Cleaning and initalization of named tags array
				$namedTags = array();

				// Get the tag names of each tag id
				foreach ($tags as $tag)
				{
					$query = $db->getQuery(true);
					$query->select("title");
					$query->from('`#__tags`');
					$query->where("id=" . intval($tag));

					$db->setQuery($query);
					$row = $db->loadObjectList();

					// Read the row and get the tag name (title)
					if (!is_null($row))
					{
						foreach ($row as $value)
						{
							if ( $value && isset($value->title))
							{
								$namedTags[] = trim($value->title);
							}
						}
					}
				}

				// Finally replace the data object with proper information
				$item->tags = !empty($namedTags) ? implode(', ', $namedTags) : $item->tags;
			}
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
			$app->enqueueMessage(JText::_("COM_PRODUCT_SEARCH_FILTER_DATE_FORMAT"), "warning");
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

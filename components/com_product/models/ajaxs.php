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
class ProductModelAjaxs extends JModelList
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
		$db	= $this->getDbo();
		$query	= $db->getQuery(true);

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

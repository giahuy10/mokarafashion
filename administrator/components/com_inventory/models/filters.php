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
class InventoryModelFilters extends JModelList
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

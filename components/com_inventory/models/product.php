<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Inventory
 * @author     Eddy Nguyen <contact@eddynguyen.com>
 * @copyright  2017 Eddy Nguyen
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');
jimport('joomla.event.dispatcher');

use Joomla\Utilities\ArrayHelper;

/**
 * Inventory model.
 *
 * @since  1.6
 */
class InventoryModelProduct extends JModelItem
{
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return void
	 *
	 * @since    1.6
	 *
	 */
	 public function ed_number_format ($money){
		$money = '<span  itemprop="price" content='.$money.'>'.number_format($money).'</span><sup>đ</sup>';
		return $money;
	}
	public function get_categories($cat_id = NULL) {
	// Get a db connection.
	$db = JFactory::getDbo();
	 
	// Create a new query object.
	$query = $db->getQuery(true);
	 
	// Select all records from the user profile table where key begins with "custom.".
	// Order it by the ordering field.
	$query->select($db->quoteName(array('id', 'title', 'alias')));
	$query->from($db->quoteName('#__categories'));
	$query->where($db->quoteName('extension') . ' = '. $db->quote('com_content'));
	if ($cat_id) {
		$query->where($db->quoteName('id') . ' = '. $db->quote($cat_id));
	}else {
		$query->where($db->quoteName('parent_id') . ' = '. $db->quote('20'));
	}
	
	$query->order('lft ASC');
	 
	// Reset the query using our newly populated query object.
	$db->setQuery($query);
	 
	// Load the results as a list of stdClass objects (see later for more options on retrieving data).
	$results = $db->loadObjectList();
	return($results);
}
	protected function populateState()
	{
		$app  = JFactory::getApplication('com_inventory');
		$user = JFactory::getUser();

		// Check published state
		if ((!$user->authorise('core.edit.state', 'com_inventory')) && (!$user->authorise('core.edit', 'com_inventory')))
		{
			$this->setState('filter.published', 1);
			$this->setState('filter.archived', 2);
		}

		// Load state from the request userState on edit or from the passed variable on default
		if (JFactory::getApplication()->input->get('layout') == 'edit')
		{
			$id = JFactory::getApplication()->getUserState('com_inventory.edit.product.id');
		}
		else
		{
			$id = JFactory::getApplication()->input->get('id');
			JFactory::getApplication()->setUserState('com_inventory.edit.product.id', $id);
		}

		$this->setState('product.id', $id);

		// Load the parameters.
		$params       = $app->getParams();
		$params_array = $params->toArray();

		if (isset($params_array['item_id']))
		{
			$this->setState('product.id', $params_array['item_id']);
		}

		$this->setState('params', $params);
	}

	/**
	 * Method to get an object.
	 *
	 * @param   integer $id The id of the object to get.
	 *
	 * @return  mixed    Object on success, false on failure.
	 */
	 public function getTag ($id) {
		$db = JFactory::getDbo();
 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select($db->quoteName(array('id', 'title', 'alias')));
		$query->from($db->quoteName('#__tags'));
		$query->where($db->quoteName('id') . ' = '. $db->quote($id));

		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$result = $db->loadObject();
		return ($result);
	}  
	public function &getData($id = null)
	{
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id))
			{
				$id = $this->getState('product.id');
			}

			// Get a level row instance.
			$table = $this->getTable();

			// Attempt to load the row.
			if ($table->load($id))
			{
				// Check published state.
				if ($published = $this->getState('filter.published'))
				{
					if (isset($table->state) && $table->state != $published)
					{
						throw new Exception(JText::_('COM_INVENTORY_ITEM_NOT_LOADED'), 403);
					}
				}

				// Convert the JTable to a clean JObject.
				$properties  = $table->getProperties(1);
				$this->_item = ArrayHelper::toObject($properties, 'JObject');
			}
		}

		if (isset($this->_item->created_by) )
		{
			$this->_item->created_by_name = JFactory::getUser($this->_item->created_by)->name;
		}if (isset($this->_item->modified_by) )
		{
			$this->_item->modified_by_name = JFactory::getUser($this->_item->modified_by)->name;
		}

			if (isset($this->_item->category) && $this->_item->category != '') {
				if (is_object($this->_item->category))
				{
					$this->_item->category = ArrayHelper::fromObject($this->_item->category);
				}

				$values = (is_array($this->_item->category)) ? $this->_item->category : explode(',',$this->_item->category);

				$textValue = array();
				foreach ($values as $value)
				{
					$db = JFactory::getDbo();
					$query = "SELECT id, title FROM #__categories WHERE extension='com_inventory' and published=1 and id = '" . $value . "'";
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->title;
					}
				}

			$this->_item->category = !empty($textValue) ? implode(', ', $textValue) : $this->_item->category;

			}
			$this->_item->fields = array();

				// GET OPTION FOR COLORS.

				$options = json_decode($this->_item->color);
				if (is_array($options)){
					$options_text = array();
					foreach ((array) $options as $option)
					{
						$options_text[] = $this->getTag($option)->title;
					}
					$this->_item->fields['color'] = array_combine($options, $options_text);
				
				}else {
					
					
					$this->_item->fields['color'] = array($this->_item->color => $this->getTag($this->_item->color)->title);
				}
				
				

				// GET OPTION FOR MATERIAL.
				$options = json_decode($this->_item->material);
				if (is_array($options)){
				$options_text = array();

				foreach ((array) $options as $option)
				{
					$options_text[] = $this->getTag($option)->title;
				}

					$this->_item->fields['material'] = array_combine($options, $options_text);
				}else {
					$this->_item->fields['material'] = array($this->_item->material => $this->getTag($this->_item->material)->title);
				}
			
				
				// GET OPTION FOR NECK.
				$this->_item->fields['neck'] = array($this->_item->neck => $this->getTag($this->_item->neck)->title);
				
				// GET OPTION FOR SLEEVE.
				$this->_item->fields['sleeve'] = array($this->_item->sleeve => $this->getTag($this->_item->sleeve)->title);
				
	
				// GET OPTION FOR STYLE.	
					$options = json_decode($this->_item->type);	
					if (is_array($options)){
					$options_text = array();
					foreach ((array) $options as $option)
					{
						$options_text[] = $this->getTag($option)->title;
						
					}
					$this->_item->fields['type'] = array_combine($options, $options_text);
					}else {
						$this->_item->fields['type'] = array($this->_item->type => $this->getTag($this->_item->type)->title);
					}
				
				// GET OPTION FOR SHAPE.					
					if ($this->_item->shape) {
					$this->_item->fields['shape'] = array($this->_item->shape => $this->getTag($this->_item->shape)->title);
					}
				// GET OPTION FOR SKIRT.
				if ($this->_item->skirt) {
				$options = json_decode($this->_item->skirt);
				
				if (is_array($options)){
				$options_text = array();

				foreach ((array) $options as $option)
				{
					$options_text[] = $this->getTag($option)->title;
				}
				$this->_item->fields['skirt'] = array_combine($options, $options_text);
				} else {
					
					$this->_item->fields['skirt'] = array($this->_item->skirt => $this->getTag($this->_item->skirt)->title);
				}
				}
				

		return $this->_item;
	}

	/**
	 * Get an instance of JTable class
	 *
	 * @param   string $type   Name of the JTable class to get an instance of.
	 * @param   string $prefix Prefix for the table class name. Optional.
	 * @param   array  $config Array of configuration values for the JTable object. Optional.
	 *
	 * @return  JTable|bool JTable if success, false on failure.
	 */
	public function getTable($type = 'Product', $prefix = 'InventoryTable', $config = array())
	{
		$this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_inventory/tables');

		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Get the id of an item by alias
	 *
	 * @param   string $alias Item alias
	 *
	 * @return  mixed
	 */
	public function getItemIdByAlias($alias)
	{
		$table      = $this->getTable();
		$properties = $table->getProperties();
		$result     = null;

		if (key_exists('alias', $properties))
		{
            $table->load(array('alias' => $alias));
            $result = $table->id;
		}

		return $result;
	}

	/**
	 * Method to check in an item.
	 *
	 * @param   integer $id The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function checkin($id = null)
	{
		// Get the id.
		$id = (!empty($id)) ? $id : (int) $this->getState('product.id');

		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Attempt to check the row in.
			if (method_exists($table, 'checkin'))
			{
				if (!$table->checkin($id))
				{
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Method to check out an item for editing.
	 *
	 * @param   integer $id The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function checkout($id = null)
	{
		// Get the user id.
		$id = (!empty($id)) ? $id : (int) $this->getState('product.id');

		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Get the current user object.
			$user = JFactory::getUser();

			// Attempt to check the row out.
			if (method_exists($table, 'checkout'))
			{
				if (!$table->checkout($user->get('id'), $id))
				{
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Get the name of a category by id
	 *
	 * @param   int $id Category id
	 *
	 * @return  Object|null    Object if success, null in case of failure
	 */
	public function getCategoryName($id)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('title')
			->from('#__categories')
			->where('id = ' . $id);
		$db->setQuery($query);

		return $db->loadObject();
	}

	/**
	 * Publish the element
	 *
	 * @param   int $id    Item id
	 * @param   int $state Publish state
	 *
	 * @return  boolean
	 */
	public function publish($id, $state)
	{
		$table = $this->getTable();
		$table->load($id);
		$table->state = $state;

		return $table->store();
	}

	/**
	 * Method to delete an item
	 *
	 * @param   int $id Element id
	 *
	 * @return  bool
	 */
	public function delete($id)
	{
		$table = $this->getTable();

		return $table->delete($id);
	}

	
}

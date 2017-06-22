<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Inventory
 * @author     Eddy Nguyen <contact@eddynguyen.com>
 * @copyright  2017 Eddy Nguyen
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Inventory helper.
 *
 * @since  1.6
 */
class InventoryHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  string
	 *
	 * @return void
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_INVENTORY_TITLE_PRODUCTS'),
			'index.php?option=com_inventory&view=products',
			$vName == 'products'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_INVENTORY_TITLE_CATEGORIES'),
			'index.php?option=com_categories&extension=com_inventory',
			$vName == 'categories'
		);
	
		JHtmlSidebar::addEntry(
			JText::_('COM_INVENTORY_TITLE_SALES'),
			'index.php?option=com_inventory&view=sales',
			$vName == 'sales'
		);
	
		JHtmlSidebar::addEntry(
			JText::_('COM_INVENTORY_TITLE_COUPONS'),
			'index.php?option=com_inventory&view=coupons',
			$vName == 'coupons'
		);

		
	}

	/**
	 * Gets the files attached to an item
	 *
	 * @param   int     $pk     The item's id
	 *
	 * @param   string  $table  The table's name
	 *
	 * @param   string  $field  The field's name
	 *
	 * @return  array  The files
	 */
	public static function getFiles($pk, $table, $field)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select($field)
			->from($table)
			->where('id = ' . (int) $pk);

		$db->setQuery($query);

		return explode(',', $db->loadResult());
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return    JObject
	 *
	 * @since    1.6
	 */
	public static function getActions()
	{
		$user   = JFactory::getUser();
		$result = new JObject;

		$assetName = 'com_inventory';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
		public static function getGroupNameByGroupId($group_id) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select('title')
			->from('#__usergroups')
			->where('id = ' . intval($group_id));

		$db->setQuery($query);
		return $db->loadResult();
	}
}


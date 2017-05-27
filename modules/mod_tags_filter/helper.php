<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_tags_popular
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_tags_popular
 *
 * @package     Joomla.Site
 * @subpackage  mod_tags_popular
 * @since       3.1
 */
abstract class ModTagsFilter
{
	/**
	 * Get list of popular tags
	 *
	 * @param   \Joomla\Registry\Registry  &$params  module parameters
	 *
	 * @return  mixed
	 *
	 * @since   3.1
	 */
	public static function getList()
	{
		$db = JFactory::getDbo();
 
// Create a new query object.
$query = $db->getQuery(true);
 
// Select all records from the user profile table where key begins with "custom.".
// Order it by the ordering field.
$query->select($db->quoteName(array('id', 'title', 'type', 'ordering','fieldparams')));
$query->from($db->quoteName('#__fields'));
$query->where($db->quoteName('group_id') . ' = '. $db->quote('1'));
$query->where($db->quoteName('required') . ' = '. $db->quote('1'));
$query->where($db->quoteName('state') . ' = '. $db->quote('1'));
$query->where($db->quoteName('context') . ' = '. $db->quote('com_content.article'));
$query->order('ordering ASC');
 
// Reset the query using our newly populated query object.
$db->setQuery($query);
 
// Load the results as a list of stdClass objects (see later for more options on retrieving data).
$fieds = $db->loadObjectList();

		return $fieds;
	}
}

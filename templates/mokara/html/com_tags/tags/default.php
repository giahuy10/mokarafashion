<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_tags
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Note that there are certain parts of this layout used only when there is exactly one tag.

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
$description = $this->params->get('all_tags_description');
$descriptionImage = $this->params->get('all_tags_description_image');
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select('a.*');

		$query->from($db->quoteName('#__fields_values','a'));
		$query->join('INNER', $db->quoteName('#__content','b') . ' ON (' . $db->quoteName('a.item_id') . ' = ' . $db->quoteName('b.id') . ')');
		$query->join('INNER', $db->quoteName('#__ucm_content','u') . ' ON (' . $db->quoteName('a.item_id') . ' != ' . $db->quoteName('u.core_content_item_id') . ')');
		//$query->where($db->quoteName('field_id') . ' = '. $field_id);
		$query->group($db->quoteName('item_id'));
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$items = $db->loadObjectlist();
		$list_item = "0";
		 foreach ($items as $key=> $item) {
			 $list_item.=",".$item->item_id;
			$profile[$key] = new stdClass();
			$profile[$key]->core_type_alias = "com_content.article";
			$profile[$key]->core_title = $item->title;
			$profile[$key]->core_alias = $item->alias;
			$profile[$key]->core_state =  $item->state;
			$profile[$key]->core_access =$item->access;
			$profile[$key]->core_params =  $item->attribs;
			$profile[$key]->core_featured =$item->featured;
			$profile[$key]->core_metadata =  $item->metadata;
			$profile[$key]->core_created_user_id =  $item->created_by;
			$profile[$key]->core_created_time =  $item->created;
			$profile[$key]->core_language =  $item->language;
			$profile[$key]->core_publish_up =  $item->publish_up;
			$profile[$key]->core_content_item_id =  $item->id;
			$profile[$key]->asset_id =  $item->asset_id;
			$profile[$key]->core_urls =  $item->urls;
			$profile[$key]->core_hits =  $item->hits;
			$profile[$key]->core_version =  $item->version;
			$profile[$key]->core_ordering =  $item->ordering;
			$profile[$key]->core_metakey =  $item->metakey;
			$profile[$key]->core_metadesc =  $item->metadesc;
			$profile[$key]->core_catid =  $item->catid;
			$profile[$key]->core_xreference =  $item->xreference;
			$profile[$key]->core_type_id =  1;
			//$result = JFactory::getDbo()->insertObject('#__ucm_content', $profile[$key]);
	
			//echo "<pre>";
			//var_dump($profile[$key]);
			//echo "</pre>";
		
		}
?>
<?php 

$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select(array('a.*', 'u.core_content_id'));

		$query->from($db->quoteName('#__fields_values','a'));
		$query->join('INNER', $db->quoteName('#__ucm_content','u') . ' ON (' . $db->quoteName('a.item_id') . ' = ' . $db->quoteName('u.core_content_item_id') . ')');
		$query->where($db->quoteName('field_id') . ' != 7');
		$query->where($db->quoteName('field_id') . ' > 4');
		$query->where($db->quoteName('a.item_id') . ' in ('.$list_item.')');
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$items = $db->loadObjectlist();
		foreach ($items as $key=> $item) {
			$profile[$key] = new stdClass();
			$profile[$key]->type_alias =  "com_content.article";
			$profile[$key]->core_content_id =  $item->core_content_id;
			$profile[$key]->content_item_id =  $item->item_id;
			$profile[$key]->tag_id =  $item->value;
			$profile[$key]->type_id =  1;
			$result = JFactory::getDbo()->insertObject('#__contentitem_tag_map', $profile[$key]);
			echo "<pre>";
			var_dump($profile[$key]);
			echo "</pre>";
		}
?>


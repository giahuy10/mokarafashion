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
JLoader::register('FieldsHelper', JPATH_ADMINISTRATOR . '/components/com_fields/helpers/fields.php');

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select('*');

		$query->from($db->quoteName('#__content'));
		
		//$query->where($db->quoteName('field_id') . ' = '. $field_id);
		$query->where($db->quoteName('state').'= 1');
		$query->where($db->quoteName('catid').' IN (21,22,23,24,25)');
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$items = $db->loadObjectlist();
		
	foreach ($items as $key=> $item) {
		// Create and populate an object.
		$profile[$key] = new stdClass();
		$profile[$key]->title = $item->title;
		$profile[$key]->alias = $item->alias;
		$item->jcfields    = FieldsHelper::getFields('com_content.article', $item, true);
		foreach ($item->jcfields as $field) {
			$field_with_id[$field->name] = $field;
		}
		$profile[$key]->price = $field_with_id['price']->rawvalue;
		if(isset($field_with_id['code']->rawvalue)) {
			$profile[$key]->code = $field_with_id['code']->rawvalue;
			
		$profile[$key]->code = strtolower($profile[$key]->code);
		$profile[$key]->code = str_replace(" ", "", $profile[$key]->code);
		}
		if (isset($field_with_id['old-price']->rawvalue))
			$profile[$key]->old_price = $field_with_id['old-price']->rawvalue;
		else 
			$profile[$key]->old_price = NULL;	
		
		
		
		if (is_array($field_with_id['mausac']->rawvalue)) {
			$profile[$key]->color = "[";
			$i=0; foreach ($field_with_id['mausac']->rawvalue as $value) {
				$i++;
				$profile[$key]->color .= '"'.$value.'"';
				if ($i < count($field_with_id['mausac']->rawvalue)) {
					$profile[$key]->color .= ",";
				}
			}
			$profile[$key]->color .= "]";
			
		}else {
			$profile[$key]->color = $field_with_id['mausac']->rawvalue;
		}
		
		
		if (is_array($field_with_id['chat-lieu']->rawvalue)) {
			$profile[$key]->material = "[";
			$i=0; foreach ($field_with_id['chat-lieu']->rawvalue as $value) {
				$i++;
				$profile[$key]->material .= '"'.$value.'"';
				if ($i < count($field_with_id['chat-lieu']->rawvalue)) {
					$profile[$key]->material .= ",";
				}
			}
			$profile[$key]->material .= "]";
			
		}else {
			$profile[$key]->material = $field_with_id['chat-lieu']->rawvalue;
		}
		
		
		if (is_array($field_with_id['kieu']->rawvalue)) {
			$profile[$key]->type = "[";
			$i=0; foreach ($field_with_id['kieu']->rawvalue as $value) {
				$i++;
				$profile[$key]->type .= '"'.$value.'"';
				if ($i < count($field_with_id['kieu']->rawvalue)) {
					$profile[$key]->type .= ",";
				}
			}
			$profile[$key]->type .= "]";
			
		}else {
			$profile[$key]->type = $field_with_id['kieu']->rawvalue;
		}
		
		
		
		if(isset($field_with_id['loai-co']->rawvalue)) 
			$profile[$key]->neck = $field_with_id['loai-co']->rawvalue;
		if(isset($field_with_id['loai-tay']->rawvalue)) 
			$profile[$key]->sleeve = $field_with_id['loai-tay']->rawvalue;
		
		
		if(isset($field_with_id['main-image']->rawvalue)) 
			$profile[$key]->image_1 = $field_with_id['main-image']->rawvalue;	
		if(isset($field_with_id['image-2']->rawvalue)) 
			$profile[$key]->image_2 = $field_with_id['image-2']->rawvalue;	
		
		if(isset($field_with_id['image-3']->rawvalue)) 
			$profile[$key]->image_3 = $field_with_id['image-3']->rawvalue;	
		
		if(isset($field_with_id['image-4']->rawvalue)) 
			$profile[$key]->image_4 = $field_with_id['image-4']->rawvalue;	
		
		
			
		
		
		$result = JFactory::getDbo()->insertObject('#__inventory_products', $profile[$key]);
		echo "ok".$key."<br/>";
	}


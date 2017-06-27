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
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_mokara/models', 'MokaraModel');
$productMod = JModelLegacy::getInstance('Product', 'MokaraModel', array('ignore_request' => true));
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
		$query->order('id DESC');
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$items = $db->loadObjectlist();
		
	foreach ($items as $key=> $item) {
		// Create and populate an object.
		$profile[$key] = new stdClass();
		$profile[$key] = $productMod->get_custom_field($item);
		
	}


$filename = './images/3.png';
 
// Get dimensions of the original image
list($current_width, $current_height) = getimagesize($filename);
 
// The x and y coordinates on the original image where we
// will begin cropping the image
$left = 50;
$top = 50;
 
// This will be the final size of the image (e.g. how many pixels
// left and down we will be going)
$crop_width = 200;
$crop_height = 200;
 
// Resample the image
$canvas = imagecreatetruecolor($crop_width, $crop_height);
$current_image = imagecreatefromjpeg($filename);
imagecopy($canvas, $current_image, 0, 0, $left, $top, $current_width, $current_height);
imagejpeg($canvas, 'image_croped.jpg', 100);

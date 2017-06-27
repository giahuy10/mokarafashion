<?php 
defined('_JEXEC') or die;
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_mokara/models', 'MokaraModel');
$productMod = JModelLegacy::getInstance('Product', 'MokaraModel', array('ignore_request' => true));
	$values = (json_decode($_POST['suggest']));
	$filter_where = implode(",",$values);
	

	
	$db = JFactory::getDbo();
	 
	// Create a new query object.
	$query = $db->getQuery(true);
	 
	// Select all records from the user profile table where key begins with "custom.".
	// Order it by the ordering field.
	$query->select($db->quoteName(array('title', 'id', 'alias', 'catid')));
	$query->from($db->quoteName('#__content'));
	//$query->join('INNER', $db->quoteName('#__fields_values') . ' ON (' . $db->quoteName('id') . ' = ' . $db->quoteName('item_id') . ')');
	$query->where($db->quoteName('state') . ' = '. $db->quote('1'));
	$query->where($db->quoteName('catid').' IN (20,21,22,23,24,25)');
	if ($filter_where != "") {
	$query->where($db->quoteName('id').' IN 
				(
					SELECT content_item_id
					FROM #__contentitem_tag_map 
					WHERE tag_id in ('.$filter_where.')
					GROUP BY content_item_id 
					 HAVING COUNT(*) >= '.count($values).'
					
				)
			
			' );	
	}
	$query->order('ordering ASC');
	 
	// Reset the query using our newly populated query object.
	$db->setQuery($query);
	 
	// Load the results as a list of stdClass objects (see later for more options on retrieving data).
	$items = $db->loadObjectList();
	//echo($query->__toString());
	//echo "<br/>";
	
	foreach ($items as $item) {
		
		$extraview = $productMod->get_extra_review($item->id);
		echo $item->product_thumb;
		
	}
	
	
	
?>
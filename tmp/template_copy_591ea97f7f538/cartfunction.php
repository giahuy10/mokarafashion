<?php 
	function get_custom_field ($item) {
		$db = JFactory::getDbo();
 
	// Create a new query object.
	$query = $db->getQuery(true);
	 
	// Select all records from the user profile table where key begins with "custom.".
	// Order it by the ordering field.
	$query->select($db->quoteName(array('field_id', 'value')));
	$query->from($db->quoteName('#__fields_values'));
	$query->where($db->quoteName('item_id') . ' = '. $item->id);
	// Reset the query using our newly populated query object.
	$db->setQuery($query);
	// Load the results as a list of stdClass objects (see later for more options on retrieving data).
	$results = $db->loadObjectList();
	$field = array();
	foreach ($results as $fields) {
		$field[$fields->field_id]= $fields->value;
		
	}
	$item->product_price = $field[1];
	$item->sku = $field[7];
	$item->product_old_price = $field[4];
	$item->product_label = $field[5];
	$item->product_status = $field[3];
	return $item;
	}

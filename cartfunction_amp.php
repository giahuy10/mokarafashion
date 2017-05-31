<?php 
if(!function_exists("get_categories")) {
	function get_categories($cat_id = NULL) {
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
}
if(!function_exists("get_custom_field")) {
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
	if(isset($field[7])) {
		$item->sku = $field[7];
	$item->sku = strtolower($item->sku);
	$item->sku = str_replace(" ", "", $item->sku);
	}
	
	if (isset($field[4]))
		$item->product_old_price = $field[4];
	else 
		$item->product_old_price = NULL;	
	if (isset($field[5]))
		$item->product_label = $field[5];
	if (isset($field[3]))
		$item->product_status = $field[3];
	else 
		$item->product_status = 1;
	return $item;
	}
}
if(!function_exists("get_product_image")) {
	function get_product_image ($sku) {
		
		
		$dir    = 'images/san-pham/'.$sku;
		$files2 = array_diff(scandir($dir), array('..', '.'));
		$files2 = array_values($files2);
		return ($files2);
	}
}
if(!function_exists("get_product_image_2")) {	
	function get_product_image_2($product_id) {
		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select($db->quoteName('image_name'));
		$query->from($db->quoteName('#__jshopping_products_images'));
		$query->where($db->quoteName('id') . ' = '. $db->quote($product_id));
		$query->order('ordering ASC');
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$results = $db->loadColumn();
		return($results);
	}
}
if(!function_exists("ed_number_format")) {
	function ed_number_format ($money){
		$money = '<span  itemprop="price" content='.number_format($money,0,",",".").'>'.number_format($money).'</span><sup>đ</sup>';
		return $money;
	}
}
if(!function_exists("show_product_item")) {
	function show_product_item ($item) { ?>
	<div class="ed-inner-product" itemscope itemtype="http://schema.org/Product">
	<span itemprop="brand" class="hidden">Mokara</span>
		<?php $item = get_custom_field($item);?>
		<?php 
			if ($item->id <646) {
				$pro_image = get_product_image_2($item->id);
				$img_link = "img_products/".$pro_image[0];
			}else {
				$pro_image = get_product_image($item->sku);
				$img_link = $item->sku."/".$pro_image[0];
			}
			?>
		<?php $link = JRoute::_('index.php?option=com_content&view=article&Itemid=447&id='.$item->id);?>
		<div class="ed-item-img">
			<a href="<?php echo $link?>">
				<amp-img src="images/san-pham/<?php echo $img_link?>"
					  width="300"
					  height="433"
					  layout="responsive"
					  itemprop="image"
					  alt="<?php echo $item->title?>"></amp-img>
				
		</div>
		<span itemprop="aggregateRating" class="hidden" itemscope itemtype="http://schema.org/AggregateRating">
					Average rating: <span itemprop="ratingValue">4.4</span>, based on
					<span itemprop="ratingCount">89</span> reviews
				  </span>
		<div class="ed-product-content">
		<div class="page-header">
			<h2 itemprop="name">
				<a href="<?php echo $link ?>" itemprop="url"><?php echo $item->title?></a>
			</h2>
		</div>
		<?php echo $item->introtext; ?>
			<div class="ed-price-block" itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer">
			<meta itemprop="priceCurrency" content="VND" />
			<span itemprop="lowPrice" class="hidden"><?php echo $item->product_price?></span>
				<div class="price pull-left">
					<?php echo ed_number_format($item->product_price)?>
				</div>
				<?php if ($item->product_old_price) {?>
					<span itemprop="highPrice" class="hidden"><?php echo $item->product_old_price?></span>
					<div class="old_price pull-right"><s><?php echo ed_number_format($item->product_old_price)?></s></div>
				<?php }?>
				<div class="clearfix"></div>
			</div>	
				<form action-xhr="/<?php echo JRoute::_('index.php?option=com_mokara&view=orders&Itemid=502')?>" method="post" class="pull-left" target="_top">
				<input type="hidden" min="1" name="quantity" value="1" />
					<button type="submit" name="submit" class="btn btn-buy"><i class="fa fa-shopping-cart"></i> <?php echo JText::_('COM_CONTENT_ADD_TO_CART')?></button>
					<input type="hidden" name="product_id" value="<?php echo $item->id?>"/>
					<input type="hidden" name="option" value="com_mokara"/>
					<input type="hidden" name="view" value="orders"/>
					<input type="hidden" name="task" value="add2cart"/>
					<input type="hidden" name="Itemid" value="502"/>
					<input type="hidden" name="product_name" value="<?php echo $item->title?>"/>
					<input type="hidden" name="product_price" value="<?php echo $item->product_price?>"/>
					<input type="hidden" name="product_img" value="<?php echo "images/san-pham/".$img_link?>"/>
					<input type="hidden" name="product_old_price" value="<?php echo $item->product_old_price?>"/>
					<input type="hidden" name="product_category_id" value="<?php echo $item->catid?>"/>
				</form>
				<a class="btn pull-right add2cart-btn" href="<?php echo $link ?>">
					<?php echo JText::_('COM_CONTENT_VIEW')?>
				</a>
				<div class="clearfix"></div>
		</div>
	</div>	
<?php }}?>

<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Mokara
 * @author     Eddy Nguyen <email@giahuy10.com>
 * @copyright  2017 Mokara
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
JLoader::register('ContentHelperRoute', JPATH_SITE . '/components/com_content/helpers/route.php');
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');

use Joomla\Utilities\ArrayHelper;
/**
 * Methods supporting a list of Mokara records.
 *
 * @since  1.6
 */
class MokaraModelProduct extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	 
	public function get_items ($field_id = NULL, $value_id = NULL, $cat_id = NULL, $page = NULL) {
		$start = 0;
		$num = 20;
		if ($page) {
			$start = ($page-1)*$num;
		}
		
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
	// Select all records from the user profile table where key begins with "custom.".
	// Order it by the ordering field.
		$query->select('DISTINCT item_id');
		$query->from($db->quoteName('#__fields_values','a'));
		$query->join('INNER', $db->quoteName('#__content','b') . ' ON (' . $db->quoteName('a.item_id') . ' = ' . $db->quoteName('b.id') . ')');
		if ($cat_id) {
			$query->where($db->quoteName('catid') . ' = '. $db->quote($cat_id));
		}
		if ($field_id && $value_id) {
			$query->where($db->quoteName('field_id') . ' = '. $db->quote($field_id). ' and '.$db->quoteName('value') . ' = '. $db->quote($value_id));
		}
		$query->where($db->quoteName('state') . ' = '. $db->quote('1'));
		//echo $query;	
		//$query->order('ordering ASC');
		$query->order('b.created DESC');
		if ($page) {
			$start = ($page-1)*$num;
			$db->setQuery($query,$start,$num);
		}
		$db->setQuery($query,$start);
		$items = $db->loadColumn();
		return($items);
}
	public function get_fields($cat_id = NULL) {
		$db = JFactory::getDbo();
 
	// Create a new query object.
	$query = $db->getQuery(true);
	 
	// Select all records from the user profile table where key begins with "custom.".
	// Order it by the ordering field.
	$query->select($db->quoteName(array('id', 'title', 'type', 'ordering','fieldparams','name')));
	$query->from($db->quoteName('#__fields'));
	if ($cat_id > 0) {
		$query->join('INNER', $db->quoteName('#__fields_categories') . ' ON (' . $db->quoteName('id') . ' = ' . $db->quoteName('field_id') . ')');
		$query->where($db->quoteName('category_id') . ' = '. $db->quote($cat_id));
	}else {
		$query->join('INNER', $db->quoteName('#__fields_categories') . ' ON (' . $db->quoteName('id') . ' = ' . $db->quoteName('field_id') . ')');
		$query->where($db->quoteName('category_id') . ' = '. $db->quote(20));
	}
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


	public function get_custom_field ($item) {
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


	public function get_product_image ($sku) {
		
		
		$dir    = 'images/san-pham/'.$sku;
		$files2 = array_diff(scandir($dir), array('..', '.'));
		$files2 = array_values($files2);
		return ($files2);
	}


	public function get_product_image_2($product_id) {
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

	public function test_function () {
		echo "hello";
	}
	public function ed_number_format ($money){
		$money = number_format($money)."<sup>đ</sup>"; 
		return $money;
	}


	public function show_product_item ($item) { 
	$html ='<div class="ed-inner-product" itemscope itemtype="http://schema.org/Product">';
	$html .= '<span itemprop="brand" class="hidden">Mokara</span>';
	$item = $this->get_custom_field($item);
	
			if ($item->id <646) {
				$pro_image = $this->get_product_image_2($item->id);
				$img_link = "img_products/".$pro_image[0];
			}else {
				$pro_image = $this->get_product_image($item->sku);
				$img_link = $item->sku."/".$pro_image[0];
			}
			$item->slug    = $item->id . ':' . $item->alias;
			$link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language));
			$html .='<div class="ed-item-img">';
			$html .='	<a href="'.$link.'"><img itemprop="image" src="images/san-pham/'.$img_link.'" alt="'.$item->title.'"/></a>';
			$html .='</div>';
			$html .='<div class="ed-product-content">';
			$html .='<div class="page-header">';
			$html .='<h2 itemprop="name">';
			$html .='<a href="'.$link.' ?>" itemprop="url">'.$item->title.'</a>';
			$html .='</h2>';
			$html .='</div>';
			$html .='<span itemprop="aggregateRating" class="hidden" itemscope itemtype="http://schema.org/AggregateRating">
					Average rating: <span itemprop="ratingValue">4.4</span>, based on
					<span itemprop="ratingCount">89</span> reviews
				  </span>';
			$html .= $item->introtext; 
			$html .='<div class="ed-price-block" itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer">';
			$html .= ' <meta itemprop="priceCurrency" content="VND" />';
			$html .= '<span itemprop="lowPrice" class="hidden">'.$item->product_price.'</span>';
			$html .='<div class="price pull-left">';
			$html .=$this->ed_number_format($item->product_price);
			$html .='</div>';
			if ($item->product_old_price) {
					$html .= '<span itemprop="highPrice" class="hidden">'.$item->product_old_price.'</span>';
					$html .='<div class="old_price pull-right"><s>'.$this->ed_number_format($item->product_old_price).'</s></div>';
				 }
			$html .='<div class="clearfix"></div>';
			$html .='</div>';	
			$html .='<form action="'.JRoute::_('index.php?option=com_mokara&view=orders&Itemid=502').'" method="post" class="pull-left">';
			$html .='<input type="hidden" min="1" name="quantity" value="1" />';
			$html .='<button type="submit" name="submit" class="btn btn-buy"><i class="fa fa-shopping-cart"></i> '.JText::_('COM_CONTENT_ADD_TO_CART').'</button>';
				$html .='	<input type="hidden" name="product_id" value="'.$item->id.'"/>';
				$html .='	<input type="hidden" name="option" value="com_mokara"/>';
				$html .='	<input type="hidden" name="view" value="orders"/>';
				$html .='	<input type="hidden" name="task" value="add2cart"/>';
				$html .='	<input type="hidden" name="Itemid" value="502"/>';
				$html .='	<input type="hidden" name="product_name" value="'.$item->title.'"/>';
				$html .='	<input type="hidden" name="product_price" value="'.$item->product_price.'"/>';
				$html .='	<input type="hidden" name="product_img" value="images/san-pham/'.$img_link.'"/>';
				$html .='	<input type="hidden" name="product_old_price" value="'.$item->product_old_price.'"/>';
				$html .='	<input type="hidden" name="product_category_id" value="'.$item->catid.'>"/>';
				$html .='</form>';
				$html .='<a class="btn pull-right add2cart-btn" href="'.$link.'">';
				$html .= JText::_('COM_CONTENT_VIEW');
				$html .='</a>';
				$html .='<div class="clearfix"></div>';
		$html .='</div>';
	$html .='</div>	';
	echo $html;
 }
 public function show_product_item_amp ($item) { 
	$html ='<div class="ed-inner-product" itemscope itemtype="http://schema.org/Product">';
	$html .= '<span itemprop="brand" class="hidden">Mokara</span>';
	$item = $this->get_custom_field($item);
	
			if ($item->id <646) {
				$pro_image = $this->get_product_image_2($item->id);
				$img_link = "img_products/".$pro_image[0];
			}else {
				$pro_image = $this->get_product_image($item->sku);
				$img_link = $item->sku."/".$pro_image[0];
			}
		
			$link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language));
			$html .='<div class="ed-item-img">';
			$html .='	<a href="'.$link.'"><amp-img src="images/san-pham/'.$img_link.'"
					  width="300"
					  height="433"
					  layout="responsive"
					  itemprop="image"
					  alt="'.$item->title.'"></amp-img></a>';
			$html .='</div>';
			$html .='<div class="ed-product-content">';
			$html .='<div class="page-header">';
			$html .='<h2 itemprop="name">';
			$html .='<a href="'.$link.' ?>" itemprop="url">'.$item->title.'</a>';
			$html .='</h2>';
			$html .='</div>';
			$html .='<span itemprop="aggregateRating" class="hidden" itemscope itemtype="http://schema.org/AggregateRating">
					Average rating: <span itemprop="ratingValue">4.4</span>, based on
					<span itemprop="ratingCount">89</span> reviews
				  </span>';
			$html .= $item->introtext; 
			$html .='<div class="ed-price-block" itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer">';
			$html .= ' <meta itemprop="priceCurrency" content="VND" />';
			$html .= '<span itemprop="lowPrice" class="hidden">'.$item->product_price.'</span>';
			$html .='<div class="price pull-left">';
			$html .=$this->ed_number_format($item->product_price);
			$html .='</div>';
			if ($item->product_old_price) {
					$html .= '<span itemprop="highPrice" class="hidden">'.$item->product_old_price.'</span>';
					$html .='<div class="old_price pull-right"><s>'.$this->ed_number_format($item->product_old_price).'</s></div>';
				 }
			$html .='<div class="clearfix"></div>';
			$html .='</div>';	
			$html .='<form action-xhr="'.JRoute::_('index.php?option=com_mokara&view=orders&Itemid=502').'" method="post" class="pull-left" target="_top">';
			$html .='<input type="hidden" min="1" name="quantity" value="1" />';
			$html .='<button type="submit" name="submit" class="btn btn-buy"><i class="fa fa-shopping-cart"></i> '.JText::_('COM_CONTENT_ADD_TO_CART').'</button>';
				$html .='	<input type="hidden" name="product_id" value="'.$item->id.'"/>';
				$html .='	<input type="hidden" name="option" value="com_mokara"/>';
				$html .='	<input type="hidden" name="view" value="orders"/>';
				$html .='	<input type="hidden" name="task" value="add2cart"/>';
				$html .='	<input type="hidden" name="Itemid" value="502"/>';
				$html .='	<input type="hidden" name="product_name" value="'.$item->title.'"/>';
				$html .='	<input type="hidden" name="product_price" value="'.$item->product_price.'"/>';
				$html .='	<input type="hidden" name="product_img" value="images/san-pham/'.$img_link.'"/>';
				$html .='	<input type="hidden" name="product_old_price" value="'.$item->product_old_price.'"/>';
				$html .='	<input type="hidden" name="product_category_id" value="'.$item->catid.'>"/>';
				$html .='</form>';
				$html .='<a class="btn pull-right add2cart-btn" href="'.$link.'">';
				$html .= JText::_('COM_CONTENT_VIEW');
				$html .='</a>';
				$html .='<div class="clearfix"></div>';
		$html .='</div>';
	$html .='</div>	';
	echo $html;
 }

	
}

<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Mokara
 * @author     Eddy Nguyen <email@giahuy10.com>
 * @copyright  2017 Mokara
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
jimport('joomla.oauth2.client');
jimport('joomla.application.component.modellist');
JLoader::register('ContentHelperRoute', JPATH_SITE . '/components/com_content/helpers/route.php');
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');
JLoader::register('FieldsHelper', JPATH_ADMINISTRATOR . '/components/com_fields/helpers/fields.php');
$articleMod = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));
$appParams = JFactory::getApplication()->getParams();
$articleMod->setState('params', $appParams);
use Joomla\Utilities\ArrayHelper;
require_once JPATH_SITE . '/plugins/content/imgresizecache/resize.php';


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
	 public function check_filter_value ($product_ids, $field_id, $value) {
		 $product_ids = implode(",",$product_ids);
		 $db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select($db->quoteName('item_id'));
		
		$query->from($db->quoteName('#__fields_values'));
		
		$query->where($db->quoteName('field_id') . ' = '. $field_id);
		$query->where($db->quoteName('value') . ' = '. $value);
		$query->where($db->quoteName('item_id') . ' in ('. $product_ids.')');
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();
		 $num_rows = $db->getNumRows();
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		
		return $num_rows;
	 }
	 public function get_tag_title ($tag_id) {
		    // Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select($db->quoteName('title'));
		
		$query->from($db->quoteName('#__tags'));
		
		$query->where($db->quoteName('id') . ' = '. $tag_id);
		
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$tag_title = $db->loadResult();
		return $tag_title;
		
	 }
	 public function get_alias_url ($link) {
		   // Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select($db->quoteName('oldurl'));
		
		$query->from($db->quoteName('#__sh404sef_urls'));
		
		$query->where($db->quoteName('newurl') . ' = '. $db->quote($link));
		
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$alias = $db->loadResult();
		return ($alias);
	 }
	 public function order_history ($order_id) {
		  // Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select($db->quoteName(array('order_id', 'status', 'updated', 'comment')));
		
		$query->from($db->quoteName('#__inventory_sale_history'));
		
		$query->where($db->quoteName('order_id') . ' = '. $db->quote($order_id));
		$query->order('updated DESC');
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$histories = $db->loadObjectList();
		return ($histories);
	 }
	 public function order_detail ($order_id) {
		 // Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select($db->quoteName(array('order_id', 'product_id', 'quantity', 'product_price','size','title','alias','catid','language')));
		
		$query->from($db->quoteName('#__inventory_order_detail','a'));
		$query->join('INNER', $db->quoteName('#__content', 'b') . ' ON (' . $db->quoteName('a.product_id') . ' = ' . $db->quoteName('b.id') . ')');
		$query->where($db->quoteName('order_id') . ' = '. $db->quote($order_id));
		$query->order('b.id ASC');
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$products = $db->loadObjectList();
		$html="";
		$html.='<table id="cart" class="table table-hover table-condensed">
    				<thead>
						<tr>
							<th style="width:50%">Sản phẩm</th>
							<th style="width:10%">Giá</th>
							<th style="width:8%">Số lượng</th>
							<th style="width:22%" class="text-center">Thành tiền</th>
							<th style="width:10%"></th>
						</tr>
					</thead>
					<tbody>';
		$total = 0;foreach($products as $key => $product) {
		$product->slug    = $product->product_id . ':' . $product->alias;
			$link = JRoute::_(ContentHelperRoute::getArticleRoute($product->slug, $product->catid, $product->language));
		$total += $product->quantity*$product->product_price;
		$html.='<tr>
					<td data-th="Product">
							
									
										<h4 class="nomargin">
										<a href="'.$link.'">'.$product->title.'</a>';
									
										
		$html.='</h4>
										<strong>Size: </strong> '.$product->size;
										
		
								
			$html.='				</td>
							<td data-th="Price">';
							if (isset($product->old_price))
							$html.='	<s>'.$this->ed_number_format($product->old_price).'</s>';
		$html.='<br/>
								'.$this->ed_number_format($product->product_price).'</td>
							<td data-th="Quantity" class="text-center">
								'.$product->quantity.'
							</td>
							<td data-th="Subtotal" class="text-center">'.$this->ed_number_format($product->quantity*$product->product_price).'</td>
						
						</tr>';
						}
		$html.='</tbody>
					<tfoot>
						<tr class="visible-xs">
							<td class="text-center"><strong>Tổng: '.$this->ed_number_format($total).'</strong></td>
						</tr>
						<tr>
							
							<td colspan="2" class="hidden-xs"></td>
							<td  class="hidden-xs text-center"><strong>Tổng: </strong></td>
							<td class="hidden-xs text-center"><strong>'.$this->ed_number_format($total).'</strong></td>
				
						</tr>
					</tfoot>
				</table>';
		return ($html);
	 }
	public function save_user_filter ($user_id = "", $ip, $field_id, $value) {
		// Create and populate an object.
			$profile = new stdClass();
			$profile->user_id = $user_id;
			$profile->ip = $ip;
			
			$profile->field_id = $field_id;
			$profile->value = $value;

		
			 
			// Insert the object into the user profile table.
			$result = JFactory::getDbo()->insertObject('#__user_filter', $profile);
	}
	public function save_user_log ($user_id = "", $ip, $component, $view, $layout="", $task="", $item, $ref, $mobile=0) {
		
		// Create and populate an object.
			$profile = new stdClass();
			$profile->user_id = $user_id;
			$profile->ip = $ip;
			$profile->component = $component;
			$profile->view = $view;
			$profile->layout = $layout;
			$profile->task = $task;
			$profile->item = $item;
			$profile->ref = $ref;
			$profile->mobile = $mobile;

		
			 
			// Insert the object into the user profile table.
			$result = JFactory::getDbo()->insertObject('#__user_logs', $profile);
		
	} 
	public function save_user_phone ($user_id = "", $ip, $item, $phone, $name) {
		
		// Create and populate an object.
			$profile = new stdClass();
			$profile->user_id = $user_id;
			$profile->ip = $ip;
			
			$profile->item_id = $item;
			$profile->name = $name;
			$profile->phone_number = $phone;

		
			 
			// Insert the object into the user profile table.
			$result = JFactory::getDbo()->insertObject('#__user_phones', $profile);
		
	} 
	public function get_coupon_detail ($code, $user, $total) {
		$user_groups = $user->groups;
		$user_groups[1] = "1";
		
		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select('*');
		$query->from($db->quoteName('#__inventory_coupon'));
		$query->where($db->quoteName('coupon_code') . ' = '.$db->quote($code));
		$query->where($db->quoteName('state') . ' = 1');
		
		$query->where('('.$db->quoteName('coupon_limit') . ' = 0 or '.$db->quoteName('coupon_limit').'>'.$this->check_coupon_limit($code).')');
		
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$coupon_detail = $db->loadObject();
		if (!$coupon_detail) {
			$coupon_detail->error = 6;
			$coupon_detail->error_message="Mã Coupon ".$code." không tồn tại. Quý khách vui lòng kiểm tra lại.";
		}else {
		$coupon_detail->error = 0;
		$coupon_detail->error_message="";
		$current_date = date("Y-m-d h:i:s");
		if ($coupon_detail->coupon_from) {
			if ($current_date < $coupon_detail->coupon_from) {
				$coupon_detail->error = 1;
				$coupon_detail->error_message = "Mã Coupon chỉ có hiệu lực sau ".$coupon_detail->coupon_from;
			}
		}
		
		if (!in_array($coupon_detail->coupon_for_group_user,$user_groups)) {
			$coupon_detail->error = 2;
			$coupon_detail->error_message = "Mã Coupon chỉ dành cho nhóm khách hàng ".$this->get_group_name($coupon_detail->coupon_for_group_user);
		}
		if ($coupon_detail->coupon_to) {
			if ($current_date > $coupon_detail->coupon_to && $coupon_detail->coupon_to!="0000-00-00 00:00:00") {
				$coupon_detail->error = 3;
				$coupon_detail->error_message = "Mã Coupon đã hết hạn sử dụng.";
			}
		}
		if ($coupon_detail->coupon_one_time == 0) {
			if ($this->check_coupon_for_user($coupon_detail->coupon_code, $user->id)) {
				$coupon_detail->error = 4;
				$coupon_detail->error_message = "Mã Coupon chỉ được sử dụng 1 lần cho mỗi khách hàng.";	
			}
		}
		if ($coupon_detail->coupon_limit <= $this->check_coupon_limit($code) && $coupon_detail->coupon_limit != 0) {
			$coupon_detail->error = 5;
			$coupon_detail->error_message = "Mã Coupon chỉ được sử dụng ".$coupon_detail->coupon_limit." lần.";	
			
		}
		if ($coupon_detail->coupon_for_order && $coupon_detail->coupon_for_order > $total) {
			$coupon_detail->error = 7;
			$coupon_detail->error_message = "Mã Coupon chỉ được áp dụng cho đơn hàng có giá trị trên ".$this->ed_number_format($coupon_detail->coupon_for_order);	
			
		}
	}
	return($coupon_detail);
		
		
	}
	public function get_group_name ($group_id) {
		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select($db->quoteName('title'));
		$query->from($db->quoteName('#__usergroups'));
		$query->where($db->quoteName('id') . ' = '. $group_id);
		
	
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$results = $db->loadResult();
		return($results);
	}
	public function get_coupon_value ($product_id, $product_category_id, $product_price, $coupon_detail, $user) {
		if ($coupon_detail->coupon_for_categories) {
			if (!in_array($product_category_id,explode(",",$coupon_detail->coupon_for_categories)))
				return 1;		
		}
		if ($coupon_detail->coupon_for_products) {
			if (!in_array($product_id,explode(",",$coupon_detail->coupon_for_products)))
				return 2;		
		}
		
		
		
		if ($coupon_detail->coupon_type) {
			$coupon['amount'] = $coupon_detail->coupon_value;
			$coupon['type'] = "";
		}else {
			$coupon['amount'] = $coupon_detail->coupon_value/100*$product_price;
			$coupon['type'] = "%";
		}
		return ($coupon);
		
		
	}
	public function check_coupon_for_user ($coupon_code, $user_id) {
		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select($db->quoteName('id'));
		$query->from($db->quoteName('#__inventory_sales'));
		$query->where($db->quoteName('coupon_code') . ' = '. $db->quote($coupon_code));
		$query->where($db->quoteName('user_id') . ' = '. $db->quote($user_id));
	
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		$db->execute();
		$num_rows = $db->getNumRows();
		return ($num_rows);
	}
	public function check_coupon_limit ($coupon_code) {
		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select($db->quoteName('coupon_code'));
		$query->from($db->quoteName('#__inventory_sales'));
		$query->where($db->quoteName('coupon_code') . ' = '. $db->quote($coupon_code));
	
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		$db->execute();
		$num_rows = $db->getNumRows();
		return ($num_rows);
	}
	
	public function get_saving_money ($user_id) {
		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select($db->quoteName('points'));
		$query->from($db->quoteName('#__user_points'));
		$query->where($db->quoteName('user_id') . ' = '. $user_id);
		
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$results = $db->loadResult();
		return($results);
	}
	public function get_related_products ($field_id, $item_id, $cat_id, $price, $template = NULL) {
		$db = JFactory::getDbo();
		$query2 = $db->getQuery(true);
		//$query="SELECT item_id FROM `#__fields_values` WHERE `field_id` = ".$field_id." and item_id != ".$item_id." and value in ( SELECT value FROM `#__fields_values` WHERE `field_id` = ".$field_id." AND `item_id` = ".$item_id." ) group by item_id";
		
		$query2->select(array('b.*', 'a.item_id'));
		$query2->from($db->quoteName('#__fields_values','a'));
		$query2->join('INNER', $db->quoteName('#__content', 'b') . ' ON (' . $db->quoteName('a.item_id') . ' = ' . $db->quoteName('b.id') . ')');
		$query2->where($db->quoteName('state') . ' = '. $db->quote('1'));
		if ($cat_id)
			$query2->where($db->quoteName('catid') . ' = '. $db->quote($cat_id));
		$query2->where($db->quoteName('field_id') . ' = '. $db->quote($field_id));
		$query2->where($db->quoteName('item_id') . ' != '. $db->quote($item_id));
		if ($field_id == 15)
			$query2->where($db->quoteName('value') . ' != 12');
		if ($field_id == 1) {
			$min = $price-100000;
			$max = $price+100000;
			$query2->where($db->quoteName('value') . ' > '.$min);
			$query2->where($db->quoteName('value') . ' < '.$max);
		}else {
			$query2->where($db->quoteName('value') . ' IN ( SELECT value FROM `#__fields_values` WHERE `field_id` = '.$field_id.' AND `item_id` ='.$item_id.')');
		}
		
		
		$query2->group($db->quoteName('item_id'));
		$query2->order('ordering ASC');
		$db->setQuery($query2,0,4);
		$items = $db->loadObjectList();
		$clear = 0;
		foreach ($items as $item) {
			$item->jcfields    = FieldsHelper::getFields('com_content.article', $item, true);
			echo '<div  class="col-xs-12 col-sm-6 col-md-4 col-lg-3 items-on-row">';	
			if (isset($template) && $template = "amp") {
				$this->show_product_item_amp($item);
			}else {
				$this->show_product_item($item);
			}
			
			echo '</div>';
			$clear++;
					if ($clear%2==0) {
					
						echo '<div class="clearfix visible-sm"></div>';
					}
					if ($clear%3==0) {
					echo ' <div class="clearfix visible-md-block"></div>';
					
				}
				if ($clear%4==0) {
					
					echo ' <div class="clearfix visible-lg-block"></div>';
				}
		}
		echo '<div class="clearfix"></div>';
	} 
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

	public function generate_json_file ($category, $file_name) {
		
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
		$money = '<span  itemprop="price" content='.$money.'>'.number_format($money).'</span><sup>đ</sup>';
		return $money;
	}
	public function get_combo_offer ($id) {
			// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select('b.*');
		$query->from($db->quoteName('#__fields_values','a'));
		$query->join('INNER', $db->quoteName('#__content','b') . ' ON (' . $db->quoteName('a.item_id') . ' = ' . $db->quoteName('b.id') . ')');
		$query->where($db->quoteName('value') . ' = '. $db->quote($id));
		$query->where($db->quoteName('state') . ' = 1');
		$query->group($db->quoteName('item_id'));
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$results = $db->loadObjectList();
		foreach ($results as $key=>$item) {
			$items[$key] =$item;
			$items[$key]->jcfields = FieldsHelper::getFields('com_content.article', $item, true);
		}
		return ($items);
	}
	public function get_extra_review($id) {
		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select($db->quoteName(array('rating_sum', 'rating_count')));
		$query->from($db->quoteName('#__content_extravote'));
		$query->where($db->quoteName('content_id') . ' = '. $db->quote($id));
		
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$results = $db->loadObject();
		$result=array();
		$result['count'] = $results->rating_count;
		$result['value'] = $results->rating_sum/$results->rating_count;
		$result['percent'] = $result['value']/5*100;
		return ($result);
	}
		public function get_custom_field ($item) {
		$app      = JFactory::getApplication();
		$cparams = $app->getParams('com_inventory');
		$savemoney=$cparams->get('savemoney');
		$item->label = array();
		if ($savemoney) {
			$savemoney_on_order=$cparams->get('savemoney_on_order');
			if ($savemoney_on_order > 0)
			$item->save_money = $savemoney_on_order;
			
		}
		
		
			$itemjcfields    = FieldsHelper::getFields('com_content.article', $item, true);
		foreach ($itemjcfields as $field) {
			$field_with_id[$field->name] = $field;
		}
		$item->product_price = $field_with_id['price']->rawvalue;
		if(isset($field_with_id['code']->rawvalue)) {
			$item->sku = $field_with_id['code']->rawvalue;
			
		$item->sku = strtolower($item->sku);
		$item->sku = str_replace(" ", "", $item->sku);
		}
		if(isset($field_with_id['combo-products']) && $field_with_id['combo-products']->rawvalue) {
			$item->combo_product =  $field_with_id['combo-products']->rawvalue; 
			$item->label['combo'] = "COMBO";
		}
			
		if(isset($field_with_id['main-image']->rawvalue)) 
			$item->product_thumb = $field_with_id['main-image']->rawvalue;	
		
		if(isset($field_with_id['hot-deal']) && $field_with_id['hot-deal']) {
			$item->hot_deal_type = $field_with_id['hot-deal']->rawvalue;
			if ($item->hot_deal_type != 0) {
				date_default_timezone_set("UTC");
				$item->deal_day = $field_with_id['hot-deal-day']->rawvalue;
				$item->deal_day_value = $field_with_id['hot-deal-day']->value;
				$item->deal_time_start = $field_with_id['time-start']->rawvalue;
				$item->deal_time_end = $field_with_id['time-end']->rawvalue;
				
				$item->deal_date_start = $field_with_id['date-start']->rawvalue;
				$item->deal_date_start = strtotime($item->deal_date_start);
				$item->deal_date_start = date("Y-m-d", strtotime('+7 hours', $item->deal_date_start));
			
				$item->deal_date_end = $field_with_id['date-end']->rawvalue;
				$item->deal_date_end = strtotime($item->deal_date_end);
				$item->deal_date_end = date("Y-m-d", strtotime('+7 hours', $item->deal_date_end));
		
		
				$item->deal_price = $field_with_id['hot-deal-price']->rawvalue;
				$item->deal_info = $field_with_id['deal-intro']->value;
				$item->deal_start = strtotime($item->deal_date_start." ".$item->deal_time_start);
				$item->deal_end = strtotime($item->deal_date_end." ".$item->deal_time_end);
				$current_date = date("Y-m-d h:i:s");
				$current_day = date("w");
				$item->hot_deal= 0;
				$item->deal_active = 0;
				if (strtotime($current_date) <= $item->deal_end) {
					$item->hot_deal= 1;
					$item->label['hot_deal'] = "DEAL";
				}
				if ($item->hot_deal_type == 1) {
					if (strtotime($current_date) >= $item->deal_start && strtotime($current_date) <= $item->deal_end) {
						$item->deal_active = 1;
					}
				}else { 
					
					
					$time_current = explode(" ",$current_date);
					$item->time_current = $time_current[1];
					if (strtotime($current_date) >= $item->deal_start && strtotime($current_date) <= $item->deal_end) {
						if(strtotime($item->time_current) <= strtotime($item->deal_time_end) && strtotime($item->time_current) >= strtotime($item->deal_time_start) && ($current_day == $item->deal_day || $item->deal_day == 0 )){
							$item->deal_active = 1;
						}
						
					}
				}
			}
		}
			if ($item->deal_active) {
				$item->count_down_text= JText::_('COM_CONTENT_DEAL_WILL_BE_END');
					if ($item->hot_deal_type == 2) {
												
							$item->deal_stop_temporary = date("Y-m-d");
							$item->deal_stop_temporary.=" ".$item->deal_time_end; 
						} else {
							$item->deal_stop_temporary = $item->deal_date_end." ".$item->deal_time_end;
						}
			} else { 
					$item->count_down_text= JText::_('COM_CONTENT_DEAL_WILL_BE_START');
					if ($item->hot_deal_type == 2) 
						{
						if ($item->deal_day) 
							{
							switch ($item->deal_day) {
												case 1:
													$next_day = "next Monday";
													break;
												case 2:
													$next_day = "next Tuesday";
													break;
												case 3:
													$next_day = "next Wednesday";
													break;
												case 4:
													$next_day = "next Thursday";
													break;
												case 5:
													$next_day = "next Friday";
													break;
												case 6:
													$next_day = "next Saturday";
													break;
												case 7:
													$next_day = "next Sunday";
													break;	
											}
											$item->deal_stop_temporary = date("Y-m-d", strtotime($next_day));
											
							} else {
										$datetime = new DateTime('tomorrow');
										$item->deal_stop_temporary = $datetime->format('Y-m-d');
											
									}
										$item->deal_stop_temporary.=" ".$item->deal_time_start; 
										
						} else { 
									$item->deal_stop_temporary = $item->deal_date_start." ".$item->deal_time_start;
						}
				}
		if ($savemoney) {
			if(isset($field_with_id['savemoney']->rawvalue) && $field_with_id['savemoney']->rawvalue > 0) {
				$item->save_money = $field_with_id['savemoney']->rawvalue;	
			}
			if ($item->save_money) {
				if ($item->deal_active == 1) {
					$item->save_money_value = round($item->deal_price*$item->save_money/100,-3);
				}else {
					$item->save_money_value = round($item->product_price*$item->save_money/100,-3);
				}
				
			}
				if ($item->save_money_value > 0)
					$item->label['save_money'] = "WALLET";
			
		}
			
		
			
	
		if (isset($field_with_id['old-price']->rawvalue) && $field_with_id['old-price']->rawvalue > 0) {
			$item->label['sale_off'] = "SALE";
			$item->product_old_price = $field_with_id['old-price']->rawvalue;
			$item->discount = ($item->product_old_price - $item->product_price)/$item->product_old_price*100;
		}
			
		else 
			$item->product_old_price = NULL;	
		
		if (isset($field_with_id['status-of-stock']->rawvalue))
			$item->product_status = $field_with_id['status-of-stock']->rawvalue;
		else 
			$item->product_status = 1;
		return $item;
	}
	
	public function show_product_item ($item) { 

	$html ='<div class="ed-inner-product" itemscope itemtype="http://schema.org/Product">';
	$html .= '<span itemprop="brand" class="hidden">Mokara</span>';
	$item = $this->get_custom_field($item);
	$extraview = $this->get_extra_review($item->id);
			$resizer = new ImgResizeCache();
			$item->slug    = $item->id . ':' . $item->alias;
			$link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language));
			if ($item->label) {
				$html .='<div class="label-product">';
				foreach ($item->label as $key => $value) {
					$html.='<span class="label-product label-product-'.$key.'">'.$value.'</span>';
				}
				$html .='</div>';
			}
			$html .='<div class="ed-item-img col-xs-12 ">';
			
			$html .='	<a href="'.$link.'"><img itemprop="image" class="product-thumb-desk" src="'.htmlspecialchars($resizer->resize($item->product_thumb, array('w' => 250, 'h' => 390, 'crop' => TRUE))).'" alt="'.$item->title.'"/></a>';
			
			$html .='</div>';
			$html .='<div class="ed-product-content  col-xs-12 ">';
			$html .='<div class="page-header">';
			$html .='<h2 itemprop="name">';
			$html .='<a href="'.$link.'" itemprop="url">'.$item->title.'</a>';
			//$html .='<a href="" class="category-icon pull-right">'.$item->category_title.'</a>';
			$html .='</h2>';
			$html .='</div>';
			$html .='<div class="size-3 extravote">
				  <span class="extravote-stars" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
					<meta itemprop="ratingCount" content="1">
					<span id="rating_'.$item->id.'_0" class="current-rating" style="width:'.$extraview['percent'].'%;" itemprop="ratingValue">'.$extraview['value'].'</span>
					 
				  </span>
				  <span class="extravote-info" id="extravote_'.$item->id.'_0">('.$extraview['count'].' Đánh giá)</span>
				</div>';
			/*$html .='<span itemprop="aggregateRating" class="hidden" itemscope itemtype="http://schema.org/AggregateRating">
					Average rating: <span itemprop="ratingValue">4.4</span>, based on
					<span itemprop="ratingCount">89</span> reviews
				  </span>';*/
			//$html .= $item->introtext; 
			$html .='<div class="ed-price-block" itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer">';
			$html .= ' <meta itemprop="priceCurrency" content="VND" />';
			$html .= '<span itemprop="lowPrice" class="hidden">'.$item->product_price.'</span>';
			if ($item->product_old_price) {
					$html .= '<span itemprop="highPrice" class="hidden">'.$item->product_old_price.'</span>';
					$html .='<div class="old_price"><strike>'.$this->ed_number_format($item->product_old_price).'</strike></div>';
				 }
			$html .='<div class="price">';
			$html .=$this->ed_number_format($item->product_price);
			if (isset($item->discount)) {
				$html .='<span class="discount-label">';
				$html .= '-'.round($item->discount)."%";
				$html .='</span>';
			}
			$html .='</div>';
			/*$html .='<div class="promotion">';
			if ($item->hot_deal) {
			$html .='<div class="deal-block-main text-center">
						<h4>Hot Deal</h4>
						<div class="deal-info">
							'.$item->deal_info.'
						</div>
						<div class="deal_price">'.
							$item->count_down_text.'<br/>
							
							<i class="fa fa-clock-o" aria-hidden="true"></i> 
							<p id="countdown_item_'.$item->id.'" class="count_down_clock"></p>
						
							
							<div class="detail_price text-center">'.$this->ed_number_format($item->deal_price).'</div>
						</div>
						
					</div>';
			}		
			if (isset($item->save_money_value) && $item->save_money_value > 0 ) {
				$html .='<div class="saving_money">';	
				$html .=JText::_('COM_CONTENT_SAVING_MONEY');
				$html.=$this->ed_number_format($item->save_money_value);
				$html.=' <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Số tiền tích lũy dùng để thanh toán cho đơn hàng tiếp theo."></i>';
				$html .='</div>';
			}
			$html .='</div>';
			*/
		
				$html .='<div class="clearfix"></div>';
			$html .='</div>';	
			
				
				$html .='<div class="clearfix"></div>';
		$html .='</div>';
			/*$html .='<form action="'.JURI::root(true).JRoute::_('index.php?option=com_mokara&view=orders&Itemid=502').'" method="post" class="add2cart-box">';
			$html .='<input type="hidden" min="1" name="quantity" value="1" />';
			$html .='<button type="submit" name="submit" class="btn btn-buy"><i class="fa fa-shopping-cart"></i> '.JText::_('COM_CONTENT_ADD_TO_CART').'</button>';
				$html .='	<input type="hidden" name="product_id" value="'.$item->id.'"/>';
				$html .='	<input type="hidden" name="option" value="com_mokara"/>';
				$html .='	<input type="hidden" name="view" value="orders"/>';
				$html .='	<input type="hidden" name="task" value="add2cart"/>';
				$html .='	<input type="hidden" name="Itemid" value="502"/>';
				$html .='	<input type="hidden" name="product_name" value="'.$item->title.'"/>';
				if ($item->deal_active) {
					$item->product_old_price = $item->product_price;
					$item->product_price = $item->deal_price;
					
				}
				$html .='	<input type="hidden" name="product_price" value="'.$item->product_price.'"/>';
				 if ($item->save_money) {
					$html .='<input type="hidden" name="save_money_value" value="'.$item->save_money_value.'"/>';
						}
				$html .='	<input type="hidden" name="product_img" value="'.$item->product_thumb.'"/>';
				$html .='	<input type="hidden" name="product_old_price" value="'.$item->product_old_price.'"/>';
				$html .='	<input type="hidden" name="product_category_id" value="'.$item->catid.'>"/>';
				$html .='<a class="btn pull-right add2cart-btn" href="'.$link.'">';
				$html .= JText::_('COM_CONTENT_VIEW');
				$html .='</a>';
				$html .='</form>';*/
		$html .='<div class="clearfix"></div>';
	$html .='</div>	';
	echo $html;
	if ($item->hot_deal == 10) {
	?>
				<script>
							
								var countDownDate_<?php echo $item->id?> = new Date("<?php echo $item->deal_stop_temporary?>").getTime();

								// Update the count down every 1 second
								var x = setInterval(function() {

								  // Get todays date and time
								  var now = new Date().getTime();
								 
								  // Find the distance between now an the count down date
								  var distance = countDownDate_<?php echo $item->id?> - now;

								  // Time calculations for days, hours, minutes and seconds
								  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
								  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
								  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
								  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
									var count_time_<?php echo $item->id?> = "";
								  // Display the result in the element with id="demo"
								  if (days > 0) {
									   count_time_<?php echo $item->id?> = count_time_<?php echo $item->id?> + days + " ngày ";
								  }
								  if (hours > 0) {
									  count_time_<?php echo $item->id?> = count_time_<?php echo $item->id?> + hours + " giờ ";
								  }
								  if (minutes > 0) {
									  count_time_<?php echo $item->id?> = count_time_<?php echo $item->id?> + minutes + " ' ";
								  }
								  count_time_<?php echo $item->id?> = count_time_<?php echo $item->id?> + seconds + " '' ";
								  document.getElementById("countdown_item_<?php echo $item->id?>").innerHTML = count_time_<?php echo $item->id?>;

								  // If the count down is finished, write some text 
								  if (distance < 0) {
									clearInterval(x);
									document.getElementById("countdown_item_<?php echo $item->id?>").innerHTML = "EXPIRED";
								  }
								}, 1000);
								</script>	
	
<?php 		
	}
 }
 public function show_product_item_amp ($item) { 
	$html ='<div class="ed-inner-product amp" itemscope itemtype="http://schema.org/Product">';
	$html .= '<span itemprop="brand" class="hidden">Mokara</span>';
	$item = $this->get_custom_field($item);
		$resizer = new ImgResizeCache();
		
		
			$item->slug    = $item->id . ':' . $item->alias;
			$link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language));
			$html .='<div class="ed-item-img">';
			$html .='	<a href="'.$link.'"><amp-img src="'.htmlspecialchars($resizer->resize($item->product_thumb, array('w' => 300, 'h' => 433, 'crop' => TRUE))).'"
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
			/*$html .='<form action-xhr="'.JURI::root(true).JRoute::_('index.php?option=com_mokara&view=orders&Itemid=502').'" method="post" class="pull-left" target="_top">';
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
			*/	
				$html .='<div class="clearfix"></div>';
		$html .='</div>';
	$html .='</div>	';
	echo $html;
 }

	
}

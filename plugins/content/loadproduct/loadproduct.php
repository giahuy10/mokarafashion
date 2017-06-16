<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.loadmodule
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Plugin to enable loading modules into content (e.g. articles)
 * This uses the {loadmodule} syntax
 *
 * @since  1.5
 */


class PlgContentLoadproduct extends JPlugin
{


	/**
	 * Plugin that loads module positions within content
	 *
	 * @param   string   $context   The context of the content being passed to the plugin.
	 * @param   object   &$article  The article object.  Note $article->text is also available
	 * @param   mixed    &$params   The article params
	 * @param   integer  $page      The 'page' number
	 *
	 * @return  mixed   true if there is an error. Void otherwise.
	 *
	 * @since   1.6
	 */
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		// Don't run this plugin when the content is being indexed
		if ($context === 'com_finder.indexer')
		{
			return true;
		}

		 preg_match_all('/{product}(.*?){\/product}/is', $article->text, $matches);
			 $str = $matches[1][0];
			 $text = explode("|",$str);
			 $product_ids = explode(",",$text[0]);
			
			 switch ($text[1]) {
				 case 1:
					$class="col-xs-12";
					break;
				case 2:
					$class="col-xs-12 col-sm-6";
					break;	
				case 3:
					$class="col-xs-12 col-sm-4";
					break;	
				case 4:
					$class="col-xs-12 col-sm-3";
					break;	
				default:
					$class="col-xs-12 col-sm-3";
			 }
			 $html ='<div class="row">';
				foreach ($product_ids as $id) {
					$item = $this->getItembyId($id);
					$html.='<div class="'.$class.'">';
					$html.='<div class="ed-inner-product desk" itemscope itemtype="http://schema.org/Product">';
	$html .= '<span itemprop="brand" class="hidden">Mokara</span>';
	
	
			
			$item->slug    = $item->id . ':' . $item->alias;
			$link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language));
			$html .='<div class="ed-item-img">';
			$html .='	<a href="'.$link.'"><img itemprop="image" class="product-thumb-desk" src="'.$item->product_thumb.'" alt="'.$item->title.'"/></a>';
			$html .='</div>';
			$html .='<div class="ed-product-content">';
			$html .='<div class="page-header">';
			$html .='<h2 itemprop="name">';
			$html .='<a href="'.$link.'" itemprop="url">'.$item->title.'</a>';
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
					$html .='<div class="old_price pull-right"><strike>'.$this->ed_number_format($item->product_old_price).'</strike></div>';
				 }
			$html .='<div class="clearfix"></div>';
			$html .='</div>';	
			
				
				$html .='<div class="clearfix"></div>';
		$html .='</div>';
	$html .='</div>	';
					$html.='</div>';
				}
			 $html .='</div>';
			 $article->text = str_replace($matches[0][0], $html, $article->text);
	}
	public function ed_number_format ($money){
		$money = '<span  itemprop="price" content='.$money.'>'.number_format($money).'</span><sup>đ</sup>';
		return $money;
	}
	public function getItembyId($id) {
		$db = JFactory::getDbo();
			$query2 = $db->getQuery(true);
			//$query="SELECT item_id FROM `#__fields_values` WHERE `field_id` = ".$field_id." and item_id != ".$item_id." and value in ( SELECT value FROM `#__fields_values` WHERE `field_id` = ".$field_id." AND `item_id` = ".$item_id." ) group by item_id";
			
			$query2->select('*');
			$query2->from($db->quoteName('#__content'));
			
			$query2->where($db->quoteName('id') . ' = '. $db->quote($id));
			
	
			$db->setQuery($query2);
			$item = $db->loadObject();
			$item->jcfields    = FieldsHelper::getFields('com_content.article', $item, true);
			$item = $this->get_custom_field_for_id($item);
			return ($item);
	}
	public function get_custom_field_for_id ($item) {
		$app      = JFactory::getApplication();
		$cparams = $app->getParams('com_inventory');
		$savemoney=$cparams->get('savemoney');
		if ($savemoney) {
			$savemoney_on_order=$cparams->get('savemoney_on_order');
			if ($savemoney_on_order > 0)
			$item->save_money = $savemoney_on_order;
		}
		
		
		
		foreach ($item->jcfields as $field) {
			$field_with_id[$field->name] = $field;
		}
		$item->product_price = $field_with_id['price']->rawvalue;
		if(isset($field_with_id['code']->rawvalue)) {
			$item->sku = $field_with_id['code']->rawvalue;
			
		$item->sku = strtolower($item->sku);
		$item->sku = str_replace(" ", "", $item->sku);
		}
		if(isset($field_with_id['main-image']->rawvalue)) 
			$item->product_thumb = $field_with_id['main-image']->rawvalue;	
		if ($savemoney) {
			if(isset($field_with_id['savemoney']->rawvalue) && $field_with_id['savemoney']->rawvalue > 0) 
			$item->save_money = $field_with_id['savemoney']->rawvalue;	
			if ($item->save_money) {
				$item->save_money_value = round($item->product_price*$item->save_money/100,-3);
			}
		}
		
			
	
		if (isset($field_with_id['old-price']->rawvalue))
			$item->product_old_price = $field_with_id['old-price']->rawvalue;
		else 
			$item->product_old_price = NULL;	
		if (isset($field_with_id['label']->rawvalue))
			$item->product_label =$field_with_id['label']->rawvalue;
		if (isset($field_with_id['status-of-stock']->rawvalue))
			$item->product_status = $field_with_id['status-of-stock']->rawvalue;
		else 
			$item->product_status = 1;
		return $item;
	}
	/**
	 * Loads and renders the module
	 *
	 * @param   string  $position  The position assigned to the module
	 * @param   string  $style     The style assigned to the module
	 *
	 * @return  mixed
	 *
	 * @since   1.6
	 */

}

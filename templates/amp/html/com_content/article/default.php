<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

// Create shortcuts to some parameters.
$params  = $this->item->params;
$images  = json_decode($this->item->images);
$urls    = json_decode($this->item->urls);
$canEdit = $params->get('access-edit');
$user    = JFactory::getUser();
$info    = $params->get('info_block_position', 0);

// Check if associations are implemented. If they are, define the parameter.
$assocParam = (JLanguageAssociations::isEnabled() && $params->get('show_associations'));

JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_mokara/models', 'MokaraModel');
$productMod = JModelLegacy::getInstance('Product', 'MokaraModel', array('ignore_request' => true));
require_once JPATH_SITE . '/plugins/content/imgresizecache/resize.php';
$resizer = new ImgResizeCache();
?>



	<?php if ($params->get('access-view')) : ?>	
	<?php if ($this->item->jcfields) { // Product layout?>
	<?php
	
	

	$this->item = $productMod->get_custom_field($this->item);

	$description = $productMod->get_categories($this->item->catid)[0]->title.': '.$this->escape($this->item->title).' ('.$this->item->sku.') | Giá: '.$productMod->ed_number_format($this->item->product_price);
	$title = $productMod->get_categories($this->item->catid)[0]->title.': '.$this->escape($this->item->title).' ('.$this->item->sku.') | '.$productMod->ed_number_format($this->item->product_price);
	$code = $this->item->sku;
	?>
	
		<div itemscope itemtype="http://schema.org/Product">
		 <span itemprop="brand" class="hidden">Mokara</span>
		 <h2 class="product-title-detail">
					Sản phẩm: <span itemprop="name"><?php echo $this->escape($this->item->title); ?></span> (<span itemprop="mpn"><?php echo $code?></span>)
				</h2>
		<div class="product-detail">
			<div class="col-xs-12 col-sm-6 col-md-6 ed-media-block">
				
					
				<div class="row ">
					<div class="col-xs-12 col-sm-2 thumb-list">
						<?php foreach ($this->item->jcfields as $field) { ?>
							<?php if ($field->id > 23 &&  $field->id < 28 && $field->rawvalue) {?>
								<div class="thumb_img">
								<amp-img 
								on="tap:lightbox<?php echo $field->id?>"
								  role="button"
								  tabindex="0"
									src="<?php echo htmlspecialchars($resizer->resize($field->rawvalue, array('w' => 330, 'h' => 433, 'crop' => TRUE)))?>" alt="<?php echo $this->item->title?>"
								  width="330"
								  height="433"
								  layout="responsive"
								  itemprop="image"
								  alt="<?php echo $item->title?>"></amp-img>
								
							</div>
							<amp-image-lightbox id="lightbox<?php echo $field->id?>"
  layout="nodisplay"></amp-image-lightbox>
							<?php }}?>
						
					</div>
					<div class="col-xs-12 col-sm-10" id="main_image">
		
						<br/><br/>
						<div class="fb-like" data-href="<?php echo JUri::getInstance();?>" data-layout="button_count" data-action="like" data-size="large" data-show-faces="true" data-share="true"></div>
						<div class="fb-save" data-uri="<?php echo JUri::getInstance();?>"></div>
						<div class="fb-send" data-href="<?php echo JUri::getInstance();?>"></div>
					</div>
					
				</div>
				
				
				
			</div>
			<div class="clearfix"></div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 ed-shopping-block">
					
	<h1 class="hidden"><?php echo strip_tags($title)?></h1>
				
				 <span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="hidden">
				<span itemprop="ratingValue">4.4</span> trên <span itemprop="reviewCount">89
				  </span> đánh giá
				</span>
				<div class="bottom-10">
				<strong>Danh mục: </strong><a href="<?php echo JRoute::_('index.php?option=com_content&view=category&layout=blog&id='.$this->item->catid)?>"><?php echo $productMod->get_categories($this->item->catid)[0]->title?></a>
				</div>
				<?php foreach ($this->item->jcfields as $field) : ?>
					<?php if ($field->id > 7 && $field->id != 14 && $field->id < 24) {?>
					<?php $description .= ' | '.$field->label.': '.$field->value;?>
					<?php 
					
					if (is_array($field->rawvalue)) {
						$field_value = explode(", ",$field->value);
						$c=array_combine($field->rawvalue,$field_value);
						echo '<div class="product-custom-field"><strong>'.$field->label . ': </strong>' ;
						foreach ($c as $key=>$value) {
							$link = 'index.php?option=com_content&filter_tag='.$key.'&id='.$this->item->catid.'&lang=en&layout=blog&view=category';
							
							$link = $productMod->get_alias_url($link);
							echo ' <a title="Xem thêm các sản phẩm '.$productMod->get_categories($this->item->catid)[0]->title.' cùng '.$field->label . ': '.$value.'" href="'.$link.'">'.$value.'</a> ';
						}
						echo '</div>';
					}else {
						echo '<div class="product-custom-field"><strong>'.$field->label . ': </strong>' ;
						$link = 'index.php?option=com_content&filter_tag='.$field->rawvalue.'&id='.$this->item->catid.'&lang=en&layout=blog&view=category';
							$link = $productMod->get_alias_url($link);
						echo ' <a title="Xem thêm các sản phẩm '.$productMod->get_categories($this->item->catid)[0]->title.' cùng '.$field->label . ': '.$field->value.'" href="'.$link.'">'.$field->value.'</a> ';
						echo '</div>';
					}
					
					?>
					<?php }?>
				<?php endforeach ?>
			
				
				
			
				<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
				<meta itemprop="priceCurrency" content="VND" />
					<?php if ($this->item->product_old_price) {?>
						<div class="old_price bottom-10"><strong><?php echo JText::_('COM_CONTENT_OLD_PRICE'); ?>: </strong><s><?php echo $productMod->ed_number_format($this->item->product_old_price)?></s></div>
					<?php }?>
					<div class="price bottom-10">
						<strong><?php if ($this->item->product_old_price) {
							echo JText::_('COM_CONTENT_SALE_PRICE');
						}
							else {
							echo JText::_('COM_CONTENT_PRICE');
							}
						?>: </strong> 
						<span class="detail_price"><?php echo $productMod->ed_number_format($this->item->product_price)?></span>
					</div>
					<span itemprop="seller" itemscope itemtype="http://schema.org/Organization" class="hidden">
                      <span itemprop="name">Mokara</span>
					 </span> 
					  <link itemprop="itemCondition" href="http://schema.org/New"/>
					  <div class="stock bottom-10">
						<strong>Trạng thái:</strong> <?php if ($this->item->product_status == 1) echo "Còn hàng"; else echo "Hết hàng"?>
						<link itemprop="availability" href="http://schema.org/<?php if ($this->item->product_status == 1) echo "InStock"; else echo "OutOfStock"?>"/>
					</div>
				</div>	
				<form action="<?php echo JURI::root(true).JRoute::_('index.php?option=com_mokara&view=orders&Itemid=502')?>" method="get" class="pull-left" target="_top">
					<div class="size bottom-10">
					
					<strong>Vui lòng chọn: </strong>
					
					<select name="size" required>
						<option value="">Size</option>
						<option value="S">S</option>
						<option value="M">M</option>
						<option value="L">L</option>
						<option value="XL">XL</option>
					</select>
					</div>
					<strong>Số lượng:</strong> <input type="number" min="1" name="quantity" value="1" class="bottom-10 quantity-box"/>
						<button type="submit" name="submit" class="btn btn-buy bottom-10"><i class="fa fa-shopping-cart"></i> <?php echo JText::_('COM_CONTENT_ADD_TO_CART')?></button>
						<input type="hidden" name="product_id" value="<?php echo $this->item->id?>"/>
						<input type="hidden" name="option" value="com_mokara"/>
						<input type="hidden" name="view" value="orders"/>
						<input type="hidden" name="task" value="add2cart"/>
						<input type="hidden" name="Itemid" value="502"/>
						<input type="hidden" name="product_name" value="<?php echo $this->item->title?>"/>
						<input type="hidden" name="product_img" value="<?php echo "images/san-pham/".$this->item->sku.$full.$pro_image[0]?>"/>
						<input type="hidden" name="product_price" value="<?php echo $this->item->product_price?>"/>
						<input type="hidden" name="product_old_price" value="<?php echo $this->item->product_old_price?>"/>
						<input type="hidden" name="product_category_id" value="<?php echo $this->item->catid?>"/>
					</form>
					<div class="clearfix"></div>
					<div class="support">
						<a	target="_blank" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=647&Itemid=530')?>" class="btn btn-warning"><i class="fa fa-bar-chart" aria-hidden="true"></i> Xem bảng size</a> 
						<a target="_blank" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=646&Itemid=529')?>" class="btn btn-success"><i class="fa fa-info-circle" aria-hidden="true"></i> Hướng dẫn mua hàng</a> 		
						
					</div>
					
					 
			</div><!--END ART TO CART SECTION-->
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 ed-loyalty-block hidden">
				<div class="ed-loyalty-inner">
				
				<amp-img src="images/Special-Offer-Banner.png"
					  width="300"
					  height="433"
					  layout="responsive"
					  itemprop="image"
					  alt="Ưu đãi đặc biệt"></amp-img>
				<h3 class="text-center">Ưu đãi đặc biệt</h3>
					<ul class="special-list">
					<li>Tặng ngay <span>50.000<sup>đ</sup></span> vào tài khoản. <a href="">Xem chi tiết!</a></li>
					<li>Nhận ngay <span>2</span> mã số dự thưởng may mắn. <a href="">Xem chi tiết!</a> </li>
					<li class="margin-top-10">Giao hàng tận nơi miễn phí trên toàn quốc. <a href="">Xem chi tiết!</a> </li>
					<li class="margin-top-10">1 đổi 1 trong 1 tháng với sản phẩm lỗi. <a href="">Xem chi tiết!</a></li>
					</ul>
					</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 ed-description-block"  itemprop="description">
				
				<?php echo $this->item->text; ?>
				<div class="fb-comments" data-href="<?php echo JUri::getInstance();?>" data-numposts="5"></div>
			</div>
		</div>
		</div>
		
		<?php 
			
				$doc = JFactory::getDocument();
				$doc->setDescription(strip_tags($description));
				$doc->setTitle(strip_tags($title));
				$doc->addCustomTag( '
				<meta property="og:title" content="'.strip_tags($title).'"/>
				<meta property="og:type" content="product"/>
				<meta property="og:email" content="info@mokara.com.vn"/>
				<meta property="og:url" content="'.JURI::current().'"/>
				<meta property="og:image" content="'.JURI::base().'images/san-pham/'.$this->item->sku.$full.$pro_image[0].'"/>
				<meta property="og:site_name" content="Thời trang công sở cao cấp Mokara"/>
				<meta property="fb:admins" content="Eddy Nguyen"/>
				<meta property="og:description" content="'.strip_tags($description).'"/>
				');
				?>
					<div class="related-product" id="related-product">
		
			<?php foreach ($this->item->jcfields as $field) : ?>
					<?php if ((($field->id > 7 && $field->id != 14 && $field->id < 24) || $field->id == 1) && $field->value) {?>
					
				
							 <h3>Sản phẩm cùng <?php echo $field->title?> (<?php if ($field->id == 1) echo $productMod->ed_number_format($field->value); else echo $field->value?>)</h3>
							
							<?php $productMod->get_related_products($field->id,$this->item->id, $this->item->catid, $this->item->product_price,'amp');?>
						 
					  
					<?php }?>
				<?php endforeach ?>
			
			
		
		 


		
		</div>
	<?php }	else { //News layout?>
		<div class="item-page<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Article">
		<meta itemprop="inLanguage" content="<?php echo ($this->item->language === '*') ? JFactory::getConfig()->get('language') : $this->item->language; ?>" />
		<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
		</div>
		<?php endif;?>
			<h2 itemprop="headline" >
					<?php echo $this->escape($this->item->title); ?>
				</h2>	
		<div itemprop="articleBody">
			<?php 
			
			$this->item->text = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $this->item->text);
			echo $this->item->text; ?>
		</div>
		<div class="hidden">
		 <h3 itemprop="author" itemscope itemtype="https://schema.org/Person">
			<span itemprop="name">Mokara Fashion</span>
		  </h3>
		  <span itemprop="description" ><?php echo $item->introtext?></span>
		   <div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
			
			<meta itemprop="url" content="https://mokara.com.vn/images/logo-mokara-black.png">
			<meta itemprop="width" content="360">
			<meta itemprop="height" content="90">
		  </div>
		   <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
			<div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
			  
			  <meta itemprop="url" content="https://mokara.com.vn/images/logo-mokara-black.png">
			  <meta itemprop="width" content="360">
			  <meta itemprop="height" content="90">
			</div>
			<meta itemprop="name" content="Mokara">
		  </div>
		  <meta itemprop="datePublished" content="<?php echo $item->created?>"/>
		  <meta itemprop="dateModified" content="<?php echo $item->modified?>"/>
		 </div> 
		</div>
	<?php }?>
	

		
		
	<?php endif; ?>

	

	

	<?php // Content is generated by content plugin event "onContentAfterDisplay" ?>
	<?php echo $this->item->event->afterDisplayContent; ?>
	


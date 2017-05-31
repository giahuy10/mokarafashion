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
JHtml::_('behavior.caption');


?>


	<?php if ($params->get('access-view')) : ?>	
	<?php if ($this->item->jcfields) { // Product layout?>
	<?php include ("./cartfunction.php"); $this->item = get_custom_field($this->item);?>
	
		<div itemscope itemtype="http://schema.org/Product">
		 <span itemprop="brand">Mokara</span>
	
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-4 ed-media-block">
				<?php 
					if ($this->item->id <646) {
						$pro_image = get_product_image_2($this->item->id);
						
						$this->item->sku = "img_products/";
						$full ="full_";
					}else {
						$pro_image = get_product_image($this->item->sku);
						$this->item->sku .="/";
						$full = "";
					}
					?>
				<div class="row">
					<div class="col-xs-12 col-sm-2 thumb-list">
						<?php for ($i = 0; $i< count($pro_image); $i++) {?>
							<div class="thumb_img">
								
								<img  class="" src="images/san-pham/<?php echo $this->item->sku.$pro_image[$i]?>" alt="<?php echo $this->item->title?>"/>
							</div>
						<?php }?>
					</div>
					<div class="col-xs-12 col-sm-10" id="main_image">
						<img itemprop="image" class="main-img" src="images/san-pham/<?php echo $this->item->sku.$full.$pro_image[0]?>" alt="<?php echo $this->item->title?>"/>
						<br/><br/><div class="fb-like" data-href="<?php echo JUri::getInstance();?>" data-layout="button_count" data-action="like" data-size="large" data-show-faces="true" data-share="true"></div>
					</div>
					
				</div>
			
				<script>
					jQuery(function($) {
						$('.thumb_img').click(function(){
							var imgelem = $(this).find('img').attr('src');
							<?php if ($this->item->id <646) {?>
							imgelem = imgelem.replace("<?php echo $this->item->sku?>", "<?php echo $this->item->sku?>/full_");
							<?php }?>
							$('#main_image').html('<img src="'+imgelem+'"/>' );

						});

						});
				</script>
				
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-5 ed-shopping-block">
					
	
				<h2 class="product-title-detail">
					Sản phẩm: <span itemprop="name"><?php echo $this->escape($this->item->title); ?></span> (<span itemprop="mpn"><?php echo $this->item->sku?></span>)
				</h2>
				 <span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
				<span itemprop="ratingValue">4.4</span> trên <span itemprop="reviewCount">89
				  </span> đánh giá
			  </span>
				<strong>Danh mục: </strong><a href="<?php echo JRoute::_('index.php?option=com_mokara&view=filter&Itemid=528&cat_id='.$this->item->catid)?>"><?php echo get_categories($this->item->catid)[0]->title?></a>
				<?php foreach ($this->item->jcfields as $field) : ?>
					<?php if ($field->id > 7 && $field->id != 14) {?>
					<div class="product-custom-field"><strong><?php echo $field->label . ': </strong>' . $field->value; ?></div>
					<?php }?>
				<?php endforeach ?>
				
				
				
			
				<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
				<meta itemprop="priceCurrency" content="VND" />
					<?php if ($this->item->product_old_price) {?>
						<div class="old_price"><strong><?php echo JText::_('COM_CONTENT_OLD_PRICE'); ?>: </strong><s><?php echo ed_number_format($this->item->product_old_price)?></s></div>
					<?php }?>
					<div class="price">
						<strong><?php if ($this->item->product_old_price) {
							echo JText::_('COM_CONTENT_SALE_PRICE');
						}
							else {
							echo JText::_('COM_CONTENT_PRICE');
							}
						?>: </strong> 
						<span class="detail_price"><?php echo ed_number_format($this->item->product_price)?></span>
					</div>
					<span itemprop="seller" itemscope itemtype="http://schema.org/Organization" class="hidden">
                      <span itemprop="name">Mokara</span>
					 </span> 
					  <link itemprop="itemCondition" href="http://schema.org/New"/>
					  <div class="stock">
						<strong>Trạng thái:</strong> <?php if ($this->item->product_status == 1) echo "Còn hàng"; else echo "Hết hàng"?>
						<link itemprop="availability" href="http://schema.org/<?php if ($this->item->product_status == 1) echo "InStock"; else echo "OutOfStock"?>"/>
					</div>
				</div>	
					<form action="<?php echo JRoute::_('index.php?option=com_mokara&view=orders&Itemid=502')?>" method="post" class="buy-section">
				
					<div class="size">
					
					<strong>Vui lòng chọn: </strong>
					
					<select name="size" required>
						<option value="">Size</option>
						<option value="S">S</option>
						<option value="M">M</option>
						<option value="L">L</option>
						<option value="XL">XL</option>
					</select>
					</div>
					<strong>Số lượng:</strong> <input type="number" min="1" name="quantity" value="1" />
						<button type="submit" name="submit" class="btn btn-buy"><i class="fa fa-shopping-cart"></i> <?php echo JText::_('COM_CONTENT_ADD_TO_CART')?></button>
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
					<div class="support">
						<a href="" class="btn btn-warning"><i class="fa fa-bar-chart" aria-hidden="true"></i> Xem bảng size</a> 
						<a href="" class="btn btn-success"><i class="fa fa-phone" aria-hidden="true"></i> Hướng dẫn mua hàng</a> 	
						
					</div>
			</div><!--END ART TO CART SECTION-->
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 ed-loyalty-block">
				<div class="ed-loyalty-inner">
			<img src="http://www.shotmechanics.com/wp-content/uploads/2014/05/Special-Offer-Banner.png" alt="Ưu đãi đặc biệt" class="special-banner hidden-xs">
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
			</div>
		</div>
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
			<?php echo $this->item->text; ?>
		</div>
		</div>
	<?php }?>
	

		
		
	<?php endif; ?>

	

	

	<?php // Content is generated by content plugin event "onContentAfterDisplay" ?>
	<?php echo $this->item->event->afterDisplayContent; ?>


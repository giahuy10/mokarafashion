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

?>



	<?php if ($params->get('access-view')) : ?>	
	<?php if ($this->item->jcfields) { // Product layout?>
	<?php
	
	
	
	$this->item = $productMod->get_custom_field($this->item);

	$description = $productMod->get_categories($this->item->catid)[0]->title.': '.$this->escape($this->item->title).' ('.$this->item->sku.') | Giá: '.$productMod->ed_number_format($this->item->product_price);
	$title = $productMod->get_categories($this->item->catid)[0]->title.': '.$this->escape($this->item->title).' ('.$this->item->sku.') | '.$productMod->ed_number_format($this->item->product_price);
	
	?>
	
		<div itemscope itemtype="http://schema.org/Product">
		 <span itemprop="brand" class="hidden">Mokara</span>
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 ed-media-block">
				<?php 
					$code = $this->item->sku;
					if ($this->item->id <646) {
						$pro_image = $productMod->get_product_image_2($this->item->id);
						
						$this->item->sku = "img_products/";
						$full ="full_";
					}else {
						$pro_image = $productMod->get_product_image($this->item->sku);
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
						
					
					</div>
					
				</div>
				
				<script>
					jQuery(function($) {
						$('.thumb_img').click(function(){
							var imgelem = $(this).find('img').attr('src');
							<?php if ($this->item->id <646) {?>
							imgelem = imgelem.replace("<?php echo $this->item->sku?>", "<?php echo $this->item->sku?>full_");
							<?php }?>
							$('#main_image').html('<img src="'+imgelem+'"/>' );

						});

						});
				</script>
				
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 ed-shopping-block">
					
				<h1 class="hidden"><?php echo strip_tags($title)?></h1>
				<h2 class="product-title-detail">
					Sản phẩm: <span itemprop="name"><?php echo $this->escape($this->item->title); ?></span> (<span itemprop="mpn"><?php echo $code?></span>)
				</h2>
				 <span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="hidden">
				<span itemprop="ratingValue"><?php echo ($this->item->hits-1)*5/$this->item->hits?></span> trên <span itemprop="reviewCount"><?php echo $this->item->hits?>
				  </span> đánh giá
				</span>
				<strong>Danh mục: </strong><a href="<?php echo JRoute::_('index.php?option=com_content&view=category&layout=blog&id='.$this->item->catid)?>"><?php echo $productMod->get_categories($this->item->catid)[0]->title?></a>
				<?php foreach ($this->item->jcfields as $field) : ?>
					<?php 
					
					if ($field->id > 7 && $field->id != 14 && $field->value) {?>
					<?php $description .= ' | '.$field->label.': '.$field->value;?>
					
					
				
					
					<?php 
					if (is_array($field->rawvalue)) {
						$field_value = explode(", ",$field->value);
						$c=array_combine($field->rawvalue,$field_value);
						echo '<div class="product-custom-field"><strong>'.$field->label . ': </strong>' ;
						foreach ($c as $key=>$value) {
							echo '<a rel="nofollow" href="'.JRoute::_('index.php?option=com_content&view=category&layout=blog&id='.$this->item->catid.'&field_'.$field->id.'='.$key).'"> '.$value.' </a>';
						}
						echo '</div>';
					}else {
						echo '<div class="product-custom-field"><strong>'.$field->label . ': </strong>' ;
						echo '<a rel="nofollow" href="'.JRoute::_('index.php?option=com_content&view=category&layout=blog&id='.$this->item->catid.'&field_'.$field->id.'='.$field->rawvalue).'"> '.$field->value.' </a>';
						echo '</div>';
					}
					
					?>
					<?php }?>
				<?php endforeach ?>
			
				
				
			
				<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
				<meta itemprop="priceCurrency" content="VND" />
					<?php if ($this->item->product_old_price) {?>
						<div class="old_price"><strong><?php echo JText::_('COM_CONTENT_OLD_PRICE'); ?>: </strong><s><?php echo $productMod->ed_number_format($this->item->product_old_price)?></s></div>
					<?php }?>
					<div class="price">
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
						<a target="_blank" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=647&Itemid=530')?>" class="btn btn-warning"><i class="fa fa-bar-chart" aria-hidden="true"></i> Xem bảng size</a> 
						<a target="_blank" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=646&Itemid=529')?>" class="btn btn-success"><i class="fa fa-info-circle" aria-hidden="true"></i> Hướng dẫn mua hàng</a> 	
						<div class="call-to-action hidden">
						
							<a href="tel:0906147557">
								<i class="fa fa-phone" aria-hidden="true"></i>
							</a>	
						</div>	
					</div>
					<br/>
						<div class="fb-like" data-href="<?php echo JUri::getInstance();?>" data-layout="button_count" data-action="like" data-size="large" data-show-faces="true" data-share="true"></div>
						<div class="fb-save" data-uri="<?php echo JUri::getInstance();?>"></div>
						<div class="fb-send" data-href="<?php echo JUri::getInstance();?>"></div>
						
			</div><!--END ART TO CART SECTION-->
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 ed-loyalty-block hidden">
				<div class="ed-loyalty-inner">
				<img src="images/Special-Offer-Banner.png" alt="Ưu đãi đặc biệt" class="special-banner hidden-xs">
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
				<meta property="og:email" content="web@mokara.com.vn"/>
				<meta property="og:url" content="'.JURI::current().'"/>
				<meta property="og:image" content="'.JURI::base().'images/san-pham/'.$this->item->sku.$full.$pro_image[0].'"/>
				<meta property="og:site_name" content="Thời trang công sở cao cấp Mokara"/>
				<meta property="fb:admins" content="Eddy Nguyen"/>
				<meta property="og:description" content="'.strip_tags($description).'"/>
				');
				?>
		<div class="related-product" id="related-product">
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			<?php foreach ($this->item->jcfields as $field) : ?>
					<?php if ((($field->id > 7 && $field->id != 14) || $field->id == 1) && $field->value) {?>
					
					 <div class="panel panel-default">
						<div class="panel-heading" role="tab" id="heading<?php echo $field->id?>">
						  <h4 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $field->id?>" aria-expanded="true" aria-controls="collapse<?php echo $field->id?>">
							 Sản phẩm cùng <?php echo $field->title?> (<?php if ($field->id == 1) echo $productMod->ed_number_format($field->value); else echo $field->value?>)
							</a>
						  </h4>
						</div>
						<div id="collapse<?php echo $field->id?>" class="panel-collapse collapse <?php if ($field->id == 1) echo "in"?>" role="tabpanel" aria-labelledby="heading<?php echo $field->id?>">
						  <div class="panel-body">
							<?php $productMod->get_related_products($field->id,$this->item->id, $this->item->catid, $this->item->product_price);?>
						  </div>
						</div>
					  </div>
					<?php }?>
				<?php endforeach ?>
			
			
		
		 


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
			<?php 
			
			echo $this->item->text; 
			
			?>
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
			  <img src="https://mokara.com.vn/images/logo-mokara-black.png"/>
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


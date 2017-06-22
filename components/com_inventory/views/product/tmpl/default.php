<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Inventory
 * @author     Eddy Nguyen <contact@eddynguyen.com>
 * @copyright  2017 Eddy Nguyen
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_inventory/models', 'InventoryModel');
$productMod = JModelLegacy::getInstance('Product', 'InventoryModel', array('ignore_request' => true));

	$description =$this->item->category.': '.$this->escape($this->item->title).' ('.$this->item->code.') | Giá: '.$productMod->ed_number_format($this->item->product_price);
	$title = $this->item->category.': '.$this->escape($this->item->title).' ('.$this->item->code.') | '.$productMod->ed_number_format($this->item->product_price);
?>

<div itemscope itemtype="http://schema.org/Product">
		 <span itemprop="brand" class="hidden">Mokara</span>
		<div class="row product-info-block">
			
			<div class="col-xs-12 col-sm-6 col-md-6 ed-media-block">
			
					
				<div class="row">
					<div class="col-xs-12 col-sm-2 thumb-list">
						<?php for($i=1; $i<5 ;$i++) { ?>
							<?php $thumb = "image_".$i?>
								<div class="thumb_img">
									
									<img class="" src="<?php echo $this->item->$thumb?>" alt="<?php echo $this->item->title?>"/>
								</div>
							<?php }?>
					</div>
					<div class="col-xs-12 col-sm-10" id="main_image">
						<img itemprop="image" class="main-img" src="<?php echo $this->item->image_1;?>" alt="<?php echo $this->item->title?>"/>
						
					
					</div>
					
				</div>
					<script>
					jQuery(function($) {
						$('.thumb_img').click(function(){
							var imgelem = $(this).find('img').attr('src');
						
							$('#main_image').html('<img src="'+imgelem+'"/>' );

						});

						});
				</script>
			
				
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 ed-shopping-block">
				
				
				
				<h1 class="product-title-detail">
					<span itemprop="name"><?php echo $this->escape($this->item->title); ?></span> (<span itemprop="mpn"><?php echo $this->item->alias?></span>)
				</h1>
					<?php echo $this->item->event->afterDisplayTitle; ?>
			
				
				<strong>Danh mục: </strong><a class="more-product" data-toggle="tooltip" title="Xem thêm các sản phẩm trong danh mục <?php echo $this->item->category?>" href="<?php echo JRoute::_('index.php?option=com_inventory&view=products&id='.$this->item->catslug)?>"><?php echo $this->item->category?></a>
				
			
				
					
					<?php 
					foreach ($this->item->fields as $field) {
						foreach ($field as $key=>$value) {
							echo $key."-".$value." | ";
						}
					}
					
					/*if (is_array($field->rawvalue)) {
						$field_value = explode(", ",$field->value);
						$c=array_combine($field->rawvalue,$field_value);
						echo '<div class="product-custom-field"><strong>'.$field->label . ': </strong>' ;
						foreach ($c as $key=>$value) {
							$link = 'index.php?option=com_content&filter_tag='.$key.'&id='.$this->item->catid.'&lang=en&layout=blog&view=category';
							
							$link = $productMod->get_alias_url($link);
							echo ' <a class="more-product" data-toggle="tooltip" title="Xem thêm các sản phẩm '.$category->title.' cùng '.$field->label . ': '.$value.'" href="'.$link.'">'.$value.'</a> ';
						}
						echo '</div>';
					}else {
						echo '<div class="product-custom-field"><strong>'.$field->label . ': </strong>' ;
						$link = 'index.php?option=com_content&filter_tag='.$field->rawvalue.'&id='.$this->item->catid.'&lang=en&layout=blog&view=category';
							$link = $productMod->get_alias_url($link);
						echo ' <a class="more-product" data-toggle="tooltip" title="Xem thêm các sản phẩm '.$category->title.' cùng '.$field->label . ': '.$field->value.'" href="'.$link.'">'.$field->value.'</a> ';
						echo '</div>';
					}*/
					
					?>
				
				
			
				<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
				<meta itemprop="priceCurrency" content="VND" />
					<?php if ($this->item->old_price) {?>
						<div class="old_price"><strong><?php echo JText::_('COM_CONTENT_OLD_PRICE'); ?>: </strong><s><?php echo $productMod->ed_number_format($this->item->old_price)?></s></div>
					<?php }?>
					<div class="price">
						<strong><?php if ($this->item->old_price) {
							echo JText::_('COM_CONTENT_SALE_PRICE');
						}
							else {
							echo JText::_('COM_CONTENT_PRICE');
							}
						?>: </strong> 
						<span class="detail_price"><?php echo $productMod->ed_number_format($this->item->price)?></span>
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
		<form action="<?php echo JRoute::_('index.php?option=com_mokara&view=orders&Itemid=502')?>" method="post" class="buy-section">
						<div class="cta">
							<div class="container">
								<!--<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal"><i class="fa fa-phone-square" aria-hidden="true"></i> Cần tư vấn?</button>-->
								<div class="support inline-block">
									<a target="_blank" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=647&Itemid=530')?>" class="btn btn-warning"><i class="fa fa-bar-chart" aria-hidden="true"></i> Xem bảng size</a> 
									<a target="_blank" href="<?php echo JRoute::_('index.php?option=com_content&view=article&id=646&Itemid=529')?>" class="btn btn-success"><i class="fa fa-info-circle" aria-hidden="true"></i> Hướng dẫn mua hàng</a> 	
									<div class="call-to-action hidden">
									
										<a href="tel:0906147557">
											<i class="fa fa-phone" aria-hidden="true"></i>
										</a>	
									</div>	
								</div>
								<button type="submit" name="submit" class="btn btn-buy pull-right"><i class="fa fa-shopping-cart"></i> <?php echo JText::_('COM_CONTENT_ADD_TO_CART')?></button>
								<div class="size inline-block pull-right">
								
								
								
								<select name="size" required class="form-control select-size">
									<option value="">Vui lòng chọn Size</option>
									<option value="S">S</option>
									<option value="M">M</option>
									<option value="L">L</option>
									<option value="XL">XL</option>
								</select>
								
								</div>
								<div class="pull-right product-info-cart">
									<strong><?php echo $this->item->title?> - <?php echo $productMod->ed_number_format($this->item->product_price)?></strong>
								</div>
								 <input type="hidden"  name="quantity" value="1" />
								
							</div>		
						</div>
					
					
						<input type="hidden" name="product_id" value="<?php echo $this->item->id?>"/>
						<input type="hidden" name="option" value="com_mokara"/>
						<input type="hidden" name="view" value="orders"/>
						<input type="hidden" name="task" value="add2cart"/>
						<input type="hidden" name="Itemid" value="502"/>
						<input type="hidden" name="product_name" value="<?php echo $this->item->title?>"/>
						<?php if ($this->item->save_money) {?>
						<input type="hidden" name="save_money_value" value="<?php echo $this->item->save_money_value?>"/>
						<?php }?>
						<input type="hidden" name="product_img" value="<?php echo $this->item->image_1?>"/>
						<input type="hidden" name="product_price" value="<?php echo $this->item->price?>"/>
						<input type="hidden" name="product_old_price" value="<?php echo $this->item->old_price?>"/>
						<input type="hidden" name="product_category_id" value="<?php echo $this->item->category?>"/>
					</form>
		<div class="row">
			<div class="col-xs-12 ed-description-block"  itemprop="description">
				
				<?php echo $this->item->text; ?>
			
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
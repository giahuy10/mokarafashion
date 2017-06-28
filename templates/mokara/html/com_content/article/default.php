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

require_once JPATH_SITE . '/plugins/content/imgresizecache/resize.php';
$resizer = new ImgResizeCache();
$user    = JFactory::getUser();

$userId     = $user->get('id');
$userProfile = JUserHelper::getProfile( $userId );

// Check if associations are implemented. If they are, define the parameter.
$assocParam = (JLanguageAssociations::isEnabled() && $params->get('show_associations'));

JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_mokara/models', 'MokaraModel');
$productMod = JModelLegacy::getInstance('Product', 'MokaraModel', array('ignore_request' => true));
$this->item = $productMod->get_custom_field($this->item);
?>




	<?php if ($this->item->product_price) { // Product layout?>
	<?php
	//echo $this->item->jcfields[24]->rawvalue;
	//$this->item->product_price = FieldsHelper::render('com_content.article','field.render',array('field'  => $this->item->jcfields[1]));
	
	
	$category = $productMod->get_categories($this->item->catid)[0];
	$description = $category->title.': '.$this->escape($this->item->title).' ('.$this->item->sku.') | Giá: '.$productMod->ed_number_format($this->item->product_price);
	$title = $category->title.': '.$this->escape($this->item->title).' ('.$this->item->sku.') | '.$productMod->ed_number_format($this->item->product_price);
	
	?>
	
	<!-- Trigger the modal with a button -->


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

										<!-- Modal content-->
										<div class="modal-content">
										 
										  <div class="modal-body text-center">
											<h3 >Vui lòng để lại số điện thoại, chúng tôi sẽ liên lạc lại với quý khách trong thời gian sớm nhất.</h3>
											<form action="#" method="post" name="leave_phone">
											<input type="hidden" name="product_id" value="<?php echo $this->item->id?>"/>
											<input type="hidden" name="option" value="com_content"/>
											<input type="hidden" name="view" value="article"/>
											<input type="text" class="form-control" name="phone_leave" placeholder="Ví dụ: 0912-345-678" value="<?php echo $userProfile->profile['phone']?>"/><br/>
											<input type="text" class="form-control" name="name_leave" placeholder="Ví dụ: Nguyễn Thị A" value="<?php echo $user->name?>"/><br/>
											<button type="submit" name="submit" class="btn btn-black"><?php echo JText::_('COM_CONTENT_LEAVE_PHONE')?></button>
											</form>
										  </div>
										  <div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										  </div>
										</div>

									  </div>
</div>
		<div itemscope itemtype="http://schema.org/Product">
		 <span itemprop="brand" class="hidden">Mokara</span>
		<div class="row product-info-block">
			
			<?php 
				if ($this->item->combo_product) {
					include_once ('combo-product-info-block.php');
				}
				else {
					include_once ('product-info-block.php');
				}
				?>
		</div>
		<?php
		/*date_default_timezone_set("UTC");
		$date = "2017-06-27 17:00:00";
		$nextsat = strtotime($date);
		$saturday = date("Y-m-d H:i:s", strtotime('+7 hours', $nextsat));
		echo $this->item->deal_date_start."<br/>";
		echo $this->item->deal_date_end;
		
		echo "deal type: ".$this->item->hot_deal_type."<br/>";
		echo "hotdeal =".$this->item->hot_deal."<br/>";
			echo "deal active = ".$this->item->deal_active;
			if(strtotime($this->item->time_current)<=strtotime($this->item->deal_time_end) && strtotime($this->item->time_current)>=strtotime($this->item->deal_time_start) ){
						echo "hello";
					}*/
		?>
		<form action="<?php echo JRoute::_('index.php?option=com_mokara&view=orders&Itemid=502')?>" method="post" class="buy-section">
						<div class="cta">
							<div class="container">
								<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal"><i class="fa fa-phone-square" aria-hidden="true"></i> Cần tư vấn?</button>
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
									<strong><?php echo $this->item->title?> - <?php 
									 if ($this->item->deal_active) 
										 echo $productMod->ed_number_format($this->item->deal_price);
									 else 
										echo $productMod->ed_number_format($this->item->product_price)?></strong>
								</div>
								 <input type="hidden"  name="quantity" value="1" />
								
							</div>		
						</div>
					
					
						<input type="hidden" name="product_id" value="<?php echo $this->item->id?>"/>
						<input type="hidden" name="option" value="com_mokara"/>
						<input type="hidden" name="view" value="orders"/>
						<input type="hidden" name="task" value="add2cart"/>
						<input type="hidden" name="hot_deal" value="<?php if ($this->item->deal_active) echo "1"; else echo "0"; ?>"/>
						<input type="hidden" name="Itemid" value="502"/>
						<input type="hidden" name="product_name" value="<?php echo $this->item->title?>"/>
						<?php if ($this->item->save_money) {?>
						<input type="hidden" name="save_money_value" value="<?php echo $this->item->save_money_value?>"/>
						<?php }?>
						<input type="hidden" name="product_img" value="<?php echo $this->item->product_thumb?>"/>
						<input type="hidden" name="product_price" value="<?php if ($this->item->deal_active) echo $this->item->deal_price; else echo $this->item->product_price?>"/>
						<input type="hidden" name="product_old_price" value="<?php if ($this->item->deal_active) echo $this->item->product_price; else echo $this->item->product_old_price?>"/>
						<input type="hidden" name="product_category_id" value="<?php echo $this->item->catid?>"/>
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
		<div class="related-product" id="related-product">
		<?php $combo_offer = $productMod->get_combo_offer($this->item->id);?>
			
			<?php if ($this->item->combo_product) {?>
				<?php // RELATED COMBO PRODUCT?>
				
				
				
			<?php } else {?>
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			<?php
			$combo_offer = $productMod->get_combo_offer($this->item->id);
			if ($combo_offer) { ?>
				<?php // RELATED OFFER PRODUCT?>
				 <div class="panel panel-default">
						<div class="panel-heading" role="tab" id="heading_offer">
						  <h4 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_offer" aria-expanded="true" aria-controls="collapse_offer">
							Ưu đãi đặc biệt
							</a>
						  </h4>
						</div>
						<div id="collapse_offer" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_offer">
						  <div class="panel-body">
						  
							<?php 
								foreach ($combo_offer as $id) {
								echo '<div  class="col-xs-6 col-sm-4 col-md-3 col-lg-3 items-on-row">';	
									$productMod->show_product_item ($id);
									echo '</div>';
								}
							?>
							<div class="clearfix"></div>
						  </div>
						</div>
					  </div>
				
			<?php }// RELATED OFFER PRODUCT?>
			<?php foreach ($this->item->jcfields as $field) : ?>
					<?php if ((($field->id > 7 && $field->id != 14 && $field->id < 24) || $field->id == 1) && $field->value) {?>
					
					 <div class="panel panel-default">
						<div class="panel-heading" role="tab" id="heading<?php echo $field->id?>">
						  <h4 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $field->id?>" aria-expanded="true" aria-controls="collapse<?php echo $field->id?>">
							Sản phẩm cùng
							  <?php echo $field->title?> (<?php if ($field->id == 1) echo $productMod->ed_number_format($field->value); else echo $field->value?>)
							</a>
						  </h4>
						</div>
						<div id="collapse<?php echo $field->id?>" class="panel-collapse collapse <?php if ($field->id == 1 && !$combo_offer) echo "in"?>" role="tabpanel" aria-labelledby="heading<?php echo $field->id?>">
						  <div class="panel-body">
						  
							<?php
						
							$productMod->get_related_products($field->id,$this->item->id, $this->item->catid, $this->item->product_price);?>
						  </div>
						</div>
					  </div>
					<?php }?>
				<?php endforeach ?>
			
			
		
		 


		</div>
			<?php }?>
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
	

					<?php 
							if (JRequest::getVar('phone_leave')) {
								$productMod->save_user_phone($user->id,$_SERVER['REMOTE_ADDR'],JRequest::getVar('product_id'),JRequest::getVar('phone_leave'),JRequest::getVar('name_leave'));
								echo '
								<script>
									alert("Chúng tôi đã nhận được yêu cầu của quý khách. Xin cảm ơn!");
								</script>
								';	
							}
						?>		
		


	
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>
	

	


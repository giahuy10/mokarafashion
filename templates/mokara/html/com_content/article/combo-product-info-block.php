<h1 class="product-title-detail">
					<span itemprop="name"><?php echo $this->escape($this->item->title); ?></span> (<span itemprop="mpn"><?php echo $this->item->sku?></span>)
				</h1>
					<?php echo $this->item->event->afterDisplayTitle; ?>
<?php 
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');

$articleMod = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));
$appParams = JFactory::getApplication()->getParams();
$articleMod->setState('params', $appParams);
if (count($this->item->combo_product)%2 == 0) {
	$class="col-sm-6";
}else {
	$class="col-sm-4";
}
$old_price = 0;
	foreach ($this->item->combo_product as $product) {
		
		$product = $articleMod->getItem($product);
		$product = $productMod->get_custom_field($product);
		$old_price +=$product->product_price;
		?>
		<div class="col-xs-12 <?php echo $class?>">
			<div class="row">
					<div class="col-xs-12 col-sm-2 thumb-list">
						<?php foreach ($product->jcfields as $field) { ?>
							<?php if ($field->id > 23 &&  $field->id < 28) {?>
								<div class="thumb_img thumb_img_<?php echo $product->id?>">
									
									<img class="" src="<?php echo $field->rawvalue?>" alt="<?php echo $product->title?>"/>
								</div>
							<?php }}?>
					</div>
					<div class="col-xs-12 col-sm-10" id="main_image_<?php echo $product->id?>">
						<img itemprop="image" class="main-img" src="<?php echo $product->product_thumb;?>" alt="<?php echo $product->title?>"/>
						
					
					</div>
					
			</div>
			<div class="combo-info text-center">
			<?php 
				$product->slug    = $product->id . ':' . $product->alias;
				$link = JRoute::_(ContentHelperRoute::getArticleRoute($product->slug, $product->catid, $product->language));
			?>
				<h2><strong><a href="<?php echo $link?>"><?php echo $product->title?> </a>- <?php echo $productMod->ed_number_format($product->product_price)?></strong></h2>
			</div>
		</div>
			<script>
					jQuery(function($) {
						$('.thumb_img_<?php echo $product->id?>').click(function(){
							var imgelem = $(this).find('img').attr('src');
						
							$('#main_image_<?php echo $product->id?>').html('<img src="'+imgelem+'"/>' );

						});

						});
				</script>
	<?php }
	$description .=" ".$this->item->introtext;
?>


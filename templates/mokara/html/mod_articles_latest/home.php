<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_popular
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_mokara/models', 'MokaraModel');
$model = JModelLegacy::getInstance('Product', 'MokaraModel', array('ignore_request' => true));

?>
<div class="headline text-center">
	<a href="#"><span class="label-product label-product-new" data-toggle="tooltip" title="Xem thêm những sản phẩm mới nhất!">NEW</span></a>
	<a href="#"><span class="label-product label-product-sale_off" data-toggle="tooltip" title="Xem thêm những sản phẩm đang khuyến mại - sale off!">SALE OFF</span></a>
	<a href="#"><span class="label-product label-product-combo" data-toggle="tooltip" title="Xem thêm những gói combo sản phẩm!">COMBO</span></a>
	<a href="#"><span class="label-product label-product-hot_deal" data-toggle="tooltip" title="Xem thêm những sản phẩm hot deal - giờ vàng!">HOT DEAL</span></a>
	<a href="#"><span class="label-product label-product-save_money" data-toggle="tooltip" title="Xem thêm những sản phẩm được tích lũy khi mua!">WALLET</span></a>
</div>
<div class="ed-mostread <?php echo $moduleclass_sfx; ?> row">
<?php 
$clear =0;
foreach ($list as $item) : ?>
	
	<div class="col-xs-12 col-sm-6 items-on-row">
	
	<?php 

	//var_dump($item->jcfields)?>
	
		<?php $model->show_product_item($item)?>
		
	</div>
	<?php $clear ++;
		if ($clear%2==0)
			echo '<div class="clearfix"></div>';
	?>

<?php endforeach; ?>
</div>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});

</script>

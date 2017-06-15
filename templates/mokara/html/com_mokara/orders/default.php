<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Mokara
 * @author     Eddy Nguyen <email@giahuy10.com>
 * @copyright  2017 Mokara
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
$user       = JFactory::getUser();
$userId     = $user->get('id');
$update = 0;
$total = 0;
$total_save = 0;

JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_mokara/models', 'MokaraModel');
$productMod = JModelLegacy::getInstance('Product', 'MokaraModel', array('ignore_request' => true));
?>

<?php
	if(JRequest::getVar('removeitem') >=0 && $_SESSION['itemcart']) {
		 unset($_SESSION['itemcart'][JRequest::getVar('removeitem')]);
		 $_SESSION['itemcart'] = array_values($_SESSION['itemcart']);
	}
 ?>
 <?php
	if(JRequest::getVar('updatequantity') >=0 && JRequest::getVar('quantity_updated') >0) {
		echo "
		<script>
			alert('Số lượng sản phẩm đã được cập nhật.');
		</script>
		";
		$_SESSION["itemcart"][JRequest::getVar('updatequantity')]['quantity']=JRequest::getVar('quantity_updated');
	}
 ?>

<?php 
	if ($_SESSION['itemcart']) {
	foreach($_SESSION["itemcart"] as $key => $cart) {
		if ($cart['product_id'] == JRequest::getVar('product_id') && $cart['size'] == JRequest::getVar('size')) {
			$_SESSION["itemcart"][$key]['quantity']=$_SESSION["itemcart"][$key]['quantity']+JRequest::getVar('quantity');
			$update = 1;
			break;
		}
		
	}} ?>
<?php 
if(!empty(JRequest::getVar('quantity')) && $update == 0) {
	$i = count($_SESSION["itemcart"]);
	$_SESSION["itemcart"][$i]['product_id']=JRequest::getVar('product_id');
	$_SESSION["itemcart"][$i]['quantity']=JRequest::getVar('quantity');
	$_SESSION["itemcart"][$i]['size']=JRequest::getVar('size');
	$_SESSION["itemcart"][$i]['title']=JRequest::getVar('product_name');
	$_SESSION["itemcart"][$i]['product_price']=JRequest::getVar('product_price');
	$_SESSION["itemcart"][$i]['save_money_value']=JRequest::getVar('save_money_value');
	$_SESSION["itemcart"][$i]['product_old_price']=JRequest::getVar('product_old_price');
	$_SESSION["itemcart"][$i]['product_category_id']=JRequest::getVar('product_category_id');
	$_SESSION["itemcart"][$i]['product_img']=JRequest::getVar('product_img');
	
}
	

?>


<?php if ($_SESSION["itemcart"] && count($_SESSION["itemcart"]) >= 1) { ?>
	<table id="cart" class="table table-hover table-condensed">
    				<thead>
						<tr>
							<th style="width:40%">Sản phẩm</th>
							<th style="width:10%">Giá</th>
							<th style="width:8%">Số lượng</th>
							<th style="width:22%" class="text-center">Thành tiền</th>
							<th style="width:12%" class="text-center">Tích lũy</th>
							<th style="width:5%"></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($_SESSION["itemcart"] as $key => $cart) {?>
						<?php $total += $cart['quantity']*$cart['product_price'];
						if($cart['save_money_value']) $total_save += $cart['quantity']*$cart['save_money_value'];?>
						<tr>
							<td data-th="Sản phẩm">
								<div class="row">
									<div class="col-sm-2 hidden-xs"><img src="<?php echo $cart['product_img']?>" alt="<?php echo $cart['title']?>" class="img-responsive"></div>
									<div class="col-sm-10">
										<h4 class="nomargin">
										<a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$cart['product_id'].'&catid='.$cart['product_category_id']);?>">
										<?php echo $cart['title']?> </h4>
										<strong>Size: </strong> <?php echo $cart['size']?>
										<?php if($cart['save_money_value']) {?>
										<br/>
									Tích lũy: <?php echo $productMod->ed_number_format($cart['save_money_value'])?>
								<?php }?>
									</div>
								</div>
							</td>
							<td data-th="Giá">
								<?php if ($cart['product_old_price']) {?>
								<span class="old_price"><s><?php echo $productMod->ed_number_format($cart['product_old_price'])?></s></span>
								<br/>
								<?php }?>
								<?php echo $productMod->ed_number_format($cart['product_price'])?>
							
								
								</td>
							<td data-th="Số lượng" class="text-center">
								<input type="number" id="quantity_input_<?php echo $key?>" onchange="update_url(<?php echo $key?>)" class="form-control text-center" value="<?php echo $cart['quantity']?>">
								<a id="quantity_<?php echo $key?>" href="#" data-toggle="tooltip" title="Cập nhật số lượng"><i class="fa fa-retweet"></i>		</a>
								
							</td>
							<td data-th="Thành tiền" class="text-center"><?php echo $productMod->ed_number_format($cart['quantity']*$cart['product_price'])?></td>
							<td data-th="Tích lũy" class="text-center"><?php echo $productMod->ed_number_format($cart['quantity']*$cart['save_money_value'])?></td>
							<td class="actions" data-th="">
								
								<a href="<?php echo JRoute::_('index.php?option=com_mokara&view=orders&Itemid=502&removeitem='.$key)?>"><i class="fa fa-trash-o"></i>		</a>						
							</td>
						</tr>
						<?php }?>
					</tbody>
					<tfoot>
						<tr class="visible-xs">
							<td class="text-center"><strong>Tổng: <?php echo $productMod->ed_number_format($total)?></strong></td>
						</tr>
						
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td class="text-center text-bold"><?php echo $productMod->ed_number_format($total)?></td>
							<td class="text-center text-bold"><?php echo $productMod->ed_number_format($total_save)?></td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
						
							
							<td class="text-center text-bold">Giảm giá:</td>
							<td class="text-center text-bold"><?php echo $productMod->ed_number_format($discount)?></td>
							<td></td>
						</tr>
						
						<tr>
							<td></td>
							<td></td>
							<td></td>
						
							
							<td class="text-center text-bold">Tích lũy:</td>
							<td class="text-center text-bold"><?php echo $productMod->ed_number_format($total_save)?></td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
						
							
							<td class="text-center text-bold">Tổng thanh toán:</td>
							<td class="text-center text-bold"><?php echo $productMod->ed_number_format($total)?></td>
							<td></td>
						</tr>
						<tr>
							<td><a href="#" class="btn btn-warning"><i class="fa fa-angle-left"></i> Quay lại mua hàng</a></td>
							<td colspan="2" class="hidden-xs"></td>
							<td class="hidden-xs text-center"><strong> </strong></td>
							<td><a href="<?php echo JRoute::_('index.php?option=com_mokara&view=checkout&Itemid=503')?>" class="btn btn-success btn-block">Thanh toán <i class="fa fa-angle-right"></i></a></td>
						</tr>
					</tfoot>
				</table>
<?php } else {?>
	Quý khách chưa lựa chọn sản phẩm nào. Hãy xem các sản phẩm
	<a href="/khuyen-mai-giam-gia" class="btn btn-danger"> đang giảm giá</a> <a href="/ban-chay" class="btn btn-warning"> bán chạy</a> 
	<a href="/hang-moi-ve" class="btn btn-success"> mới nhất</a> tại đây.
<?php }?>	
<script>
	function update_url(item) {
		 var x = document.getElementById("quantity_input_"+item).value;
		document.getElementById("quantity_"+item).href = "<?php echo 'index.php?option=com_mokara&view=orders&Itemid=502&updatequantity='?>" + item + "&quantity_updated="+x;
	}
</script>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>
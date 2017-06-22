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
$app = JFactory::getApplication();
$jinput = $app->input;
$jcookie  = $jinput->cookie;


JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
$user       = JFactory::getUser();
$userId     = $user->get('id');
$update = 0;
$total = 0;
$total_save = 0;
$total_coupon = 0;
$session = JFactory::getSession();
$cookie = isset($_COOKIE['cart_items_cookie']) ? $_COOKIE['cart_items_cookie'] : "";
$cookie = stripslashes($cookie);
$saved_cart_items = json_decode($cookie, true);
if(!$saved_cart_items){
    $saved_cart_items=array();
	
}
//$cart = $session->get('cart', array());
$cart = json_decode($jcookie->get('cart2'));
if(!$cart){
    $cart =array();
}
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_mokara/models', 'MokaraModel');
$productMod = JModelLegacy::getInstance('Product', 'MokaraModel', array('ignore_request' => true));
$saved = $productMod->get_saving_money($userId);
$user_groups = $user->groups;
$user_groups[1] = "1";

$coupon_detail = $productMod->get_coupon_detail($session->get('coupon_code'), $user, $session->get('total'));

?>

<?php
	if(JRequest::getVar('removeitem') >=0 && $saved_cart_items) {
		 unset($saved_cart_items[JRequest::getVar('removeitem')]);
		
		$saved_cart_items = array_values($saved_cart_items);
		$json = json_encode($saved_cart_items, true);
		setcookie("cart_items_cookie", $json, time() + (86400 * 30), '/'); // 86400 = 1 day
		$_COOKIE['cart_items_cookie']=$json;
	}
 ?>
 <?php
	if(JRequest::getVar('updatequantity') >=0 && JRequest::getVar('quantity_updated') >0) {
		echo "
		<script>
			alert('Số lượng sản phẩm đã được cập nhật.');
		</script>
		";
		
		$saved_cart_items[JRequest::getVar('updatequantity')]['quantity']=JRequest::getVar('quantity_updated');
		//$session->set('cart', $cart);
		$json = json_encode($saved_cart_items, true);
		setcookie("cart_items_cookie", $json, time() + (86400 * 30), '/'); // 86400 = 1 day
		$_COOKIE['cart_items_cookie']=$json;
	}


if ($saved_cart_items) {
	foreach($saved_cart_items as $key => $carttest) {
		if ($carttest['product_id'] == JRequest::getVar('product_id') && $carttest['size'] == JRequest::getVar('size')) {

			
			$saved_cart_items[$key]['quantity']+=JRequest::getVar('quantity');
			
			//$session->set('cart', $cart);
			$json = json_encode($saved_cart_items, true);
			setcookie("cart_items_cookie", $json, time() + (86400 * 30), '/'); // 86400 = 1 day
			$_COOKIE['cart_items_cookie']=$json;
			
			$update = 1;
			break;
		}
		
	}
}
	
if(!empty(JRequest::getVar('quantity')) && $update == 0) {	
	//$k = count($session->get('cart'));
	$k = count($saved_cart_items);

	$saved_cart_items[$k]['product_id']=JRequest::getVar('product_id');
	$saved_cart_items[$k]['quantity']=JRequest::getVar('quantity');
	$saved_cart_items[$k]['size']=JRequest::getVar('size');
	$saved_cart_items[$k]['title']=JRequest::getVar('product_name');
	$saved_cart_items[$k]['product_price']=JRequest::getVar('product_price');
	$saved_cart_items[$k]['save_money_value']=JRequest::getVar('save_money_value');
	$saved_cart_items[$k]['product_old_price']=JRequest::getVar('product_old_price');
	$saved_cart_items[$k]['product_category_id']=JRequest::getVar('product_category_id');
	$saved_cart_items[$k]['product_img']=JRequest::getVar('product_img');
	$saved_cart_items[$k]['hot_deal']=JRequest::getVar('hot_deal');
	
	//$session->set('cart', $cart);
	
	$json = json_encode($saved_cart_items, true);
    setcookie("cart_items_cookie", $json, time() + (86400 * 30), '/'); // 86400 = 1 day
    $_COOKIE['cart_items_cookie']=$json;
	
	
}

//$cart_array = json_decode($jcookie->get($name = 'cart2'), $true);


	//$cookie = isset($_COOKIE['cart_items_cookie']) ? $_COOKIE['cart_items_cookie'] : "";
	//setcookie("cart_items_cookie", $cart_text, time() + (86400 * 30), '/'); // 86400 = 1 day
	//$cookie = stripslashes($cookie);
	//$saved_cart_items = json_decode($cookie, true);
	
	
	$carts = json_decode($_COOKIE['cart_items_cookie']);
	


?>


<?php if ($carts && count($carts) >= 1) { ?>
	<table id="cart" class="table table-hover table-condensed">
    				<thead>
						<tr>
							<th style="width:30%">Sản phẩm</th>
							<th style="width:10%">Giá</th>
							<th style="width:15%" class="text-center">Số lượng</th>
							<th style="width:12%" class="text-center">Thành tiền</th>
							<th style="width:12%" class="text-center">Tích lũy</th>
							<?php if ($coupon_detail->error == 0) {
								echo '<th style="width:12%" class="text-center">Coupon</th>';
							}?>
							<th style="width:5%"></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($carts as $key => $cart_item) {?>
						<?php 
						$total += $cart_item->quantity*$cart_item->product_price;
						$session->set('total',$total);
						if($cart_item->save_money_value) {
								$total_save += $cart_item->quantity*$cart_item->save_money_value;
								$session->set('total_save',$total_save);
							}
						?>
						<tr>
							<td data-th="Sản phẩm">
								<div class="row">
									<div class="col-sm-2 hidden-xs"><img src="<?php echo $cart_item->product_img?>" alt="<?php echo $cart_item->title?>" class="img-responsive"></div>
									<div class="col-sm-10">
										<h4 class="nomargin">
										<a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$cart_item->product_id.'&catid='.$cart_item->product_category_id);?>">
										<?php echo $cart_item->title?> </h4>
										<strong>Size: </strong> <?php echo $cart_item->size?>
										<?php if($cart_item->save_money_value) {?>
										<br/>
									Tích lũy: <?php echo $productMod->ed_number_format($cart_item->save_money_value)?>
								<?php }?>
									</div>
								</div>
							</td>
							<td data-th="Giá">
								<?php if ($cart_item->product_old_price) {?>
								<span class="old_price"><s><?php echo $productMod->ed_number_format($cart_item->product_old_price)?></s></span>
								<br/>
								<?php }?>
								<?php echo $productMod->ed_number_format($cart_item->product_price)?>
							
								
								</td>
							<td data-th="Số lượng" class="text-center">
								<input type="number" style="
    width: 55px;
    display: inline-block;
" id="quantity_input_<?php echo $key?>" onchange="update_url(<?php echo $key?>)" class="form-control text-center" value="<?php echo $cart_item->quantity?>">
								<a id="quantity_<?php echo $key?>" href="#" data-toggle="tooltip" title="Cập nhật số lượng"><i class="fa fa-retweet"></i>		</a>
								
							</td>
							<td data-th="Thành tiền" class="text-center"><?php echo $productMod->ed_number_format($cart_item->quantity*$cart_item->product_price)?></td>
							<td data-th="Tích lũy" class="text-center"><?php echo $productMod->ed_number_format($cart_item->quantity*$cart_item->save_money_value)?></td>
							<?php if ($coupon_detail->error == 0) {
								
								echo '<td class="text-center">';
									$get_coupon = $productMod->get_coupon_value ($cart_item->product_id, $cart_item->product_category_id, $cart_item->product_price, $coupon_detail, $user);
								
									echo "-".$productMod->ed_number_format($cart_item->quantity*$get_coupon['amount']);
								
									if ($get_coupon['type']) {
										echo "(".$coupon_detail->coupon_value."%)";
										
									}
									$total_coupon+=$cart_item->quantity*$get_coupon['amount'];
									$session->set('total_coupon',$total_coupon);
								echo '</td>';
								
							}?>
							<td class="actions" data-th="">
								
								<a href="<?php echo JRoute::_('index.php?option=com_mokara&view=orders&Itemid=502&removeitem='.$key)?>"><i class="fa fa-trash-o"></i>		</a>						
							</td>
						</tr>
						<?php }?>
					</tbody>
					<tfoot>
						<tr class="visible-xs">
							<td class="text-center"><strong>Tổng: <?php echo $productMod->ed_number_format($session->get('total'))?></strong></td>
						</tr>
						
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td class="text-center text-bold"><?php echo $productMod->ed_number_format($session->get('total'))?></td>
							<td class="text-center text-bold"><?php echo $productMod->ed_number_format($session->get('total_save'))?></td>
							<td class="text-center text-bold"><?php if ($session->get('total_coupon')) echo "-".$productMod->ed_number_format($session->get('total_coupon'))?></td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							
							<td></td>
							<td></td>
						</tr>
						<?php if ($session->get('used_wallet')) {?>
						<tr>
							<td></td>
							<td></td>
							
						
							
							<td class="text-right text-bold">Ví Mokara:</td>
							<td class="text-center text-bold">-<?php echo $productMod->ed_number_format($session->get('wallet'))?></td>
							<td></td>
							<td></td>
						</tr>
						<?php }?>
						<?php if ($coupon_detail->error == 0 && $session->get('total_coupon')) {?>
						<tr>
							<td></td>
							<td></td>
							
						
							
							<td class="text-right text-bold">Coupon:</td>
							<td class="text-center text-bold">-<?php echo $productMod->ed_number_format($session->get('total_coupon'))?></td>
							<td></td>
							<td></td>
						</tr>
						<?php }?>
						
						<tr>
							<td></td>
							
						
						
							
							<td class="text-right text-bold" colspan="2">Tổng thanh toán:</td>
							<td class="text-center text-bold"><?php echo $productMod->ed_number_format($session->get('total')-$session->get('wallet')-$session->get('total_coupon'))?></td>
							<td></td>
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
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
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user       = JFactory::getUser();
$userId     = $user->get('id');
$update = 0;
$total = 0;
//session_destroy();
include ("./cartfunction.php");
?>

<?php
	if(JRequest::getVar('removeitem') >=0) {
		 unset($_SESSION['itemcart'][JRequest::getVar('removeitem')]);
		 $_SESSION['itemcart'] = array_values($_SESSION['itemcart']);
	}
 ?>
 <?php
	if(JRequest::getVar('updateitem')) {
		 unset($_SESSION['itemcart'][JRequest::getVar('removeitem')]);
	}
 ?>

<?php foreach($_SESSION["itemcart"] as $key => $cart) {
		if ($cart['product_id'] == JRequest::getVar('product_id')) {
			$_SESSION["itemcart"][$key]['quantity']=$_SESSION["itemcart"][$key]['quantity']+JRequest::getVar('quantity');
			$update = 1;
			break;
		}
		
	} ?>
<?php 
if(!empty(JRequest::getVar('quantity')) && $update == 0) {
	$i = count($_SESSION["itemcart"]);
	$_SESSION["itemcart"][$i]['product_id']=JRequest::getVar('product_id');
	$_SESSION["itemcart"][$i]['quantity']=JRequest::getVar('quantity');
	$_SESSION["itemcart"][$i]['title']=JRequest::getVar('product_name');
	$_SESSION["itemcart"][$i]['product_price']=JRequest::getVar('product_price');
	$_SESSION["itemcart"][$i]['product_old_price']=JRequest::getVar('product_old_price');
	$_SESSION["itemcart"][$i]['product_category_id']=JRequest::getVar('product_category_id');
	$_SESSION["itemcart"][$i]['product_img']=JRequest::getVar('product_img');
	
}
	

?>


<div class="container">
<?php if ($_SESSION["itemcart"] && count($_SESSION["itemcart"]) >= 1) { ?>
	<table id="cart" class="table table-hover table-condensed">
    				<thead>
						<tr>
							<th style="width:50%">Product</th>
							<th style="width:10%">Price</th>
							<th style="width:8%">Quantity</th>
							<th style="width:22%" class="text-center">Subtotal</th>
							<th style="width:10%"></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($_SESSION["itemcart"] as $key => $cart) {?>
						<?php $total += $cart['quantity']*$cart['product_price'];?>
						<tr>
							<td data-th="Product">
								<div class="row">
									<div class="col-sm-2 hidden-xs"><img src="<?php echo $cart['product_img']?>" alt="<?php echo $cart['title']?>" class="img-responsive"></div>
									<div class="col-sm-10">
										<h4 class="nomargin">
										<a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$cart['product_id'].'&catid='.$cart['product_category_id']);?>">
										<?php echo $cart['title']?> </h4>
										
									</div>
								</div>
							</td>
							<td data-th="Price">
								<span class="old_price"><s><?php echo ed_number_format($cart['product_old_price'])?></s></span>
								<br/>
								<?php echo ed_number_format($cart['product_price'])?></td>
							<td data-th="Quantity">
								<input type="number" class="form-control text-center" value="<?php echo $cart['quantity']?>">
							</td>
							<td data-th="Subtotal" class="text-center"><?php echo ed_number_format($cart['quantity']*$cart['product_price'])?></td>
							<td class="actions" data-th="">
								
								<a href="<?php echo JRoute::_('index.php?option=com_mokara&view=orders&Itemid=502&removeitem='.$key)?>"><i class="fa fa-trash-o"></i></button>		</a>						
							</td>
						</tr>
						<?php }?>
					</tbody>
					<tfoot>
						<tr class="visible-xs">
							<td class="text-center"><strong>Total <?php echo ed_number_format($total)?></strong></td>
						</tr>
						<tr>
							<td><a href="#" class="btn btn-warning"><i class="fa fa-angle-left"></i> Continue Shopping</a></td>
							<td colspan="2" class="hidden-xs"></td>
							<td class="hidden-xs text-center"><strong>Total <?php echo ed_number_format($total)?></strong></td>
							<td><a href="<?php echo JRoute::_('index.php?option=com_mokara&view=checkout&Itemid=503')?>" class="btn btn-success btn-block">Checkout <i class="fa fa-angle-right"></i></a></td>
						</tr>
					</tfoot>
				</table>
<?php } else {?>
	There is no Item
<?php }?>	
</div>


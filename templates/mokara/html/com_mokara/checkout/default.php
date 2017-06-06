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

	if (JRequest::getVar('product_id')) {
	

		

		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select('MAX(id)');
		$query->from($db->quoteName('#__mokara_orders'));

		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$orer_id = $db->loadResult();
		
		$order = new stdClass();
		$order->id = $orer_id + 1;
		$order->status = 1;
		$order->address = JRequest::getVar('address');
		$order->user_name = JRequest::getVar('name');
		
		$order->phone = JRequest::getVar('phone');
		$order->email = JRequest::getVar('email');
		$order->comment = JRequest::getVar('comment');
		$order->user_id = JRequest::getVar('user_id');
		$order->total = JRequest::getVar('total');
		
		$order->ordering = $order->id;
		$order->state = 1;
		$order->checked_out = 0;
		$order->created_by = 481;
		$order->modified_by = 481;
		//var_dump($order);
		$result = JFactory::getDbo()->insertObject('#__mokara_orders', $order);
		
		$product_id = JRequest::getVar('product_id');
		$product_price = JRequest::getVar('product_price');
		$product_old_price = JRequest::getVar('product_old_price');
		$quantity = JRequest::getVar('quantity');
		
		foreach ($product_id as $key=>$value) {
			$order_id[$key] = new stdClass();
			$order_id[$key]->order_id = $order->id;
			$order_id[$key]->product_id = $product_id[$key];
			$order_id[$key]->product_price = $product_price[$key];
			$order_id[$key]->product_old_price = $product_old_price[$key];
			$order_id[$key]->quantity = $quantity[$key];
			$order_detail = JFactory::getDbo()->insertObject('#__mokara_order_detail', $order_id[$key]);
			
		}
		 unset($_SESSION['itemcart']);
		?>
		<p class="text-success">Quý khách đã đặt hàng thành công. Chúng tôi sẽ liên hệ với quý khách trong thời gian sớm nhất để xác nhận đơn hàng.</p>
	<?php } else {

?>
<?php

JPluginHelper::importPlugin('captcha');
    $dispatcher = JDispatcher::getInstance();

    // This will put the code to load reCAPTCHA's JavaScript file into your <head>
    $dispatcher->trigger('onInit', 'dynamic_recaptcha_1');

    // This will return the array of HTML code.
    $recaptcha = $dispatcher->trigger('onDisplay', array(null, 'dynamic_recaptcha_1', 'class=""'));

?>

<form action="<?php echo JRoute::_('index.php?option=com_mokara&view=checkout'); ?>" method="post"
      name="adminForm" id="adminForm">
	
	  <div class="container">
	<h2>Thông tin đơn hàng</h2>
	<?php if ($_SESSION["itemcart"] && count($_SESSION["itemcart"]) >= 1) { ?>
				<table id="cart" class="table table-hover table-condensed">
    				<thead>
						<tr>
							<th style="width:50%">Sản phẩm</th>
							<th style="width:10%">Giá</th>
							<th style="width:8%">Số lượng</th>
							<th style="width:22%" class="text-center">Thành tiền</th>
							<th style="width:10%"></th>
						</tr>
					</thead>
					<tbody>
						<?php $total = 0;foreach($_SESSION["itemcart"] as $key => $cart) {?>
						<?php $total += $cart['quantity']*$cart['product_price'];?>
						<input type="hidden" name="product_id[]" value="<?php echo $cart['product_id']?>">
						<input type="hidden" name="product_price[]" value="<?php echo $cart['product_price']?>">
						<input type="hidden" name="product_old_price[]" value="<?php echo $cart['product_old_price']?>">
						<input type="hidden" name="quantity[]" value="<?php echo $cart['quantity']?>">
						<tr>
							<td data-th="Product">
								<div class="row">
									<div class="col-sm-2 hidden-xs"><img src="http://placehold.it/100x100" alt="..." class="img-responsive"></div>
									<div class="col-sm-10">
										<h4 class="nomargin">
										<a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$cart['product_id'].'&catid='.$cart['product_category_id']);?>">
										<?php echo $cart['title']?> 
										
										</h4>
										
									</div>
								</div>
							</td>
							<td data-th="Price">
								<s><?php echo $cart['product_old_price']?></s>
								<br/>
								<?php echo $cart['product_price']?></td>
							<td data-th="Quantity">
								<input type="number" disabled class="form-control text-center" value="<?php echo $cart['quantity']?>">
							</td>
							<td data-th="Subtotal" class="text-center"><?php echo $cart['quantity']*$cart['product_price']?></td>
						
						</tr>
						<?php }?>
					</tbody>
					<tfoot>
						<tr class="visible-xs">
							<td class="text-center"><strong>Tổng: <?php echo $total?></strong></td>
						</tr>
						<tr>
							
							<td colspan="2" class="hidden-xs"></td>
							<td  class="hidden-xs text-center"><strong>Tổng: </strong></td>
							<td class="hidden-xs text-center"><strong><?php echo $total?></strong></td>
				
						</tr>
					</tfoot>
				</table>
	<?php } else {?>
			There is no Item
	<?php }?>	
	<h2>Thông tin khách hàng</h2>	
	<table class="table">
		<tr>
			<td><strong>Họ tên</strong></td>
			<td><input class="form-control" type="text" name="name" value="<?php echo $user->name?>" required/></td>
		</tr>
		<tr>
			<td><strong>Địa chỉ giao hàng</strong></td>
			<td><input class="form-control" type="text" name="address" value="<?php echo $user->address?>" required/></td>
		</tr>
		<tr>
			<td><strong>Điện thoại</strong></td>
			<td><input class="form-control" type="text" name="phone" value="<?php echo $user->phone?>" required/></td>
		</tr>
		<tr>
			<td><strong>Email</strong></td>
			<td><input class="form-control" type="email" name="email" value="<?php echo $user->email?>" required/></td>
		</tr>
		<tr>
			<td><strong>Ghi chú</strong></td>
			<td><textarea name="comment" class="form-control" rows="3"></textarea></td>
		</tr>
	</table>
	
</div>
	<input type="hidden" name="view" value="checkout"/>
	<input type="hidden" name="option" value="com_mokara"/>
	<input type="hidden" name="user_id" value="<?php echo $userId; ?>"/>
	<input type="hidden" name="total" value="<?php echo $total; ?>"/>

	<?php echo JHtml::_('form.token'); ?>
	
	<button type="submit" class="btn btn-primary">Đặt hàng</button>
</form>
	<?php }?>

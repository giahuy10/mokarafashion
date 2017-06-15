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
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_mokara/models', 'MokaraModel');
$productMod = JModelLegacy::getInstance('Product', 'MokaraModel', array('ignore_request' => true));
$user       = JFactory::getUser();
$userId     = $user->get('id');
$userProfile = JUserHelper::getProfile( $userId );

	if (JRequest::getVar('product_id')) {
	

		

		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select('MAX(id)');
		$query->from($db->quoteName('#__inventory_sales'));

		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$orer_id = $db->loadResult();
		
		$order = new stdClass();
		$history = new stdClass();
		$order->id = $orer_id + 1;
		$history->order_id = $order->id;
		
		$order->status = 1;
		$order->address = JRequest::getVar('address');
		$order->name = JRequest::getVar('name');
		
		$order->phone = JRequest::getVar('phone');
		$order->email = JRequest::getVar('email');
		$order->note = JRequest::getVar('comment');
		$order->user_id = JRequest::getVar('user_id');
		$order->total = JRequest::getVar('total');
		

		$order->ordering = $order->id;
		$order->state = 1;
		$order->checked_out = 0;
		$order->created_by = 481;
		$order->modified_by = 481;
		//var_dump($order);
		$result = JFactory::getDbo()->insertObject('#__inventory_sales', $order);
		$result = JFactory::getDbo()->insertObject('#__inventory_sale_history', $history);
		
		$product_id = JRequest::getVar('product_id');
		$product_price = JRequest::getVar('product_price');
		$product_old_price = JRequest::getVar('product_old_price');
		$quantity = JRequest::getVar('quantity');
		$size = JRequest::getVar('size');
		$save_money = JRequest::getVar('save_money');
		
		foreach ($product_id as $key=>$value) {
			$order_id[$key] = new stdClass();
			$order_id[$key]->order_id = $order->id;
			$order_id[$key]->product_id = $product_id[$key];
			$order_id[$key]->product_price = $product_price[$key];
			$order_id[$key]->product_old_price = $product_old_price[$key];
			$order_id[$key]->quantity = $quantity[$key];
			$order_id[$key]->size = $size[$key];
			$order_id[$key]->save_money = $save_money[$key];
			$order_detail = JFactory::getDbo()->insertObject('#__inventory_order_detail', $order_id[$key]);
			
		}
		if (JRequest::getVar('total_save') && JRequest::getVar('total_save') > 0) {
	
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select($db->quoteName(array('id', 'user_id', 'points')));
		$query->from($db->quoteName('#__user_points'));
		$query->where($db->quoteName('user_id') . ' = '. $order->user_id);
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$user_point = $db->loadObject();
		if ($user_point) {
			$user_point->points = $user_point->points + JRequest::getVar('total_save');
			$result = JFactory::getDbo()->updateObject('#__user_points', $user_point, 'id');
		}else {
			$user_point = new stdClass();
			$user_point->user_id = $order->user_id;
			$user_point->points=JRequest::getVar('total_save');
			
			 
			// Insert the object into the user profile table.
			$result = JFactory::getDbo()->insertObject('#__user_points', $user_point);
		}
		}
		 unset($_SESSION['itemcart']);
		?>
		<p class="text-success">Quý khách đã đặt hàng thành công. Chúng tôi sẽ liên hệ với quý khách trong thời gian sớm nhất để xác nhận đơn hàng.</p>
	<?php } else {

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
							<th style="width:10%">Tích lũy</th>
						
						</tr>
					</thead>
					<tbody>
						<?php $total = 0;foreach($_SESSION["itemcart"] as $key => $cart) {?>
						<?php $total += $cart['quantity']*$cart['product_price'];
						
						if($cart['save_money_value']) $total_save += $cart['quantity']*$cart['save_money_value'];?>
						<input type="hidden" name="product_id[]" value="<?php echo $cart['product_id']?>">
						<input type="hidden" name="product_price[]" value="<?php echo $cart['product_price']?>">
						<input type="hidden" name="product_old_price[]" value="<?php echo $cart['product_old_price']?>">
						<input type="hidden" name="quantity[]" value="<?php echo $cart['quantity']?>">
						<input type="hidden" name="size[]" value="<?php echo $cart['size']?>">
						<input type="hidden" name="save_money[]" value="<?php echo $cart['save_money_value']?>">
						<tr>
							<td data-th="Sản phẩm">
								<div class="row">
									<div class="col-sm-2 hidden-xs"><img src="<?php echo $cart['product_img']?>" alt="<?php echo $cart['title']?>" class="img-responsive"></div>
									<div class="col-sm-10">
										<h4 class="nomargin">
										<a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$cart['product_id'].'&catid='.$cart['product_category_id']);?>">
										<?php echo $cart['title']?> 
										
										</h4>
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
								<s><?php echo $productMod->ed_number_format($cart['product_old_price'])?></s>
								<?php }?>
								<br/>
								<?php echo $productMod->ed_number_format($cart['product_price'])?></td>
							<td data-th="Số lượng">
								<input type="number" disabled class="form-control text-center" value="<?php echo $cart['quantity']?>">
							</td>
							<td data-th="Thành tiền" class="text-center"><?php echo $productMod->ed_number_format($cart['quantity']*$cart['product_price'])?></td>
								<td data-th="Tích lũy" class="text-center"><?php echo $productMod->ed_number_format($cart['quantity']*$cart['save_money_value'])?></td>
						
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
							
						</tr>
						<tr>
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
							
						</tr>
						
						<tr>
							<td></td>
							<td></td>
							<td></td>
						
							
							<td class="text-center text-bold">Tích lũy:</td>
							<td class="text-center text-bold"><?php echo $productMod->ed_number_format($total_save)?></td>
							
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
						
							
							<td class="text-center text-bold">Tổng thanh toán:</td>
							<td class="text-center text-bold"><?php echo $productMod->ed_number_format($total)?></td>
							
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
			<td><input class="form-control" type="text" name="address" value="<?php echo $userProfile->profile['address1']?>" required/></td>
		</tr>
		<tr>
			<td><strong>Điện thoại</strong></td>
			<td><input class="form-control" type="text" name="phone" value="<?php echo $userProfile->profile['phone']?>" required/></td>
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
	<input type="hidden" name="total_save" value="<?php echo $total_save; ?>"/>

	<?php echo JHtml::_('form.token'); ?>
	
	<button type="submit" class="btn btn-primary">Đặt hàng</button>
</form>
	<?php }?>

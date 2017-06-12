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
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_mokara/models', 'MokaraModel');
$productMod = JModelLegacy::getInstance('Product', 'MokaraModel', array('ignore_request' => true));
$canEdit = JFactory::getUser()->authorise('core.edit', 'com_inventory.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_inventory' . $this->item->id))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
$user       = JFactory::getUser();
$userId     = $user->get('id');
if (JRequest::getVar('order_id')) {
		$order = new stdClass();
		$order->order_id = JRequest::getVar('order_id');
		$order->status = JRequest::getVar('status');
		$order->comment = JRequest::getVar('comment');
		$order->manager_id = $userId;
		$result = JFactory::getDbo()->insertObject('#__inventory_sale_history', $order);
}
?>

<div class="item_fields row">
	<div class="col-xs-12 col-sm-6">
		<table class="table">
			

			<tr>
				<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_SALE_USER_ID'); ?></th>
				<td><?php echo $this->item->user_id; ?></td>
			</tr>

			<tr>
				<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_SALE_CREATED'); ?></th>
				<td><?php echo $this->item->created; ?></td>
			</tr>

			<tr>
				<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_SALE_TOTAL'); ?></th>
				<td><?php echo $productMod->ed_number_format($this->item->total); ?></td>
			</tr>

			<tr>
				<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_SALE_DISCOUNT'); ?></th>
				<td><?php echo $this->item->discount; ?></td>
			</tr>

			<tr>
				<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_SALE_STATUS'); ?></th>
				<td><?php echo $this->item->status; ?></td>
			</tr>

			<tr>
				<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_SALE_COMMENT'); ?></th>
				<td><?php echo nl2br($this->item->comment); ?></td>
			</tr>

			<tr>
				<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_SALE_NOTE'); ?></th>
				<td><?php echo nl2br($this->item->note); ?></td>
			</tr>

		</table>
	</div>
	<div class="col-xs-12 col-sm-6">	
		<h3><?php echo JText::_('COM_INVENTORY_SALE_HISTORY'); ?></h3>
		<table class="table">
				<tr>
					<th><?php echo JText::_('COM_INVENTORY_HISTORY_UPDATED'); ?></th>
					<th><?php echo JText::_('COM_INVENTORY_HISTORY_STATUS'); ?></th>
					<th><?php echo JText::_('COM_INVENTORY_HISTORY_COMMENT'); ?></th>
				</tr>
				<?php $histories = $productMod->order_history($this->item->id);
					foreach ($histories as $history) {?>
						<tr>
							<td><?php echo $history->updated?></td>
							<td><?php echo $history->status?></td>
							<td><?php echo $history->comment?></td>
						</tr>
					<?php }
				?>
			</table> 
			<?php if ($canEdit) {?>
		<form method="post" action="#">
			<div class="control-group row">
			<div class="control-label"><label id="jform_status-lbl" for="jform_status" class="required col-xs-12 col-sm-6">
				<?php echo JText::_('COM_INVENTORY_FORM_LBL_SALE_STATUS'); ?><span class="star">&nbsp;*</span></label>
			</div>
			
			<div class="controls"><select id="jform_status" name="status" class="required required col-xs-12 col-sm-6" required="required" aria-required="true" >
				<option value="1" selected="selected">Chờ xử lý</option>
				<option value="2">Đã tiếp nhận</option>
				<option value="3">Đã chuyển hàng</option>
				<option value="4">Đã thanh toán</option>
				<option value="5">Thành công</option>
				<option value="6">Hủy</option>
				<option value="7">Trả lại</option>
			</select>
			</div>
			</div>
			<div class="control-group row">
			<div class="control-label"><label id="jform_status-lbl" for="jform_status" class="required col-xs-12 col-sm-6">
				<?php echo JText::_('COM_INVENTORY_FORM_LBL_SALE_COMMENT'); ?><span class="star">&nbsp;*</span></label>
			</div>
			<div class="controls">
				<textarea name="comment" id="comment" placeholder="Comment" aria-invalid="false"></textarea>
			</div>
			<input type="submit" class="btn btn-buy" name="submit" value="<?php echo JText::_('COM_INVENTORY_UPDATE_ORDER_STATUS'); ?>">
			</div>
			<input type="hidden" name="order_id" value="<?php echo $this->item->id?>"/>
		</form>
		<?php }?>
	</div>

</div>

<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_inventory&task=sale.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_INVENTORY_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_inventory.sale.'.$this->item->id)) : ?>

	<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo JText::_("COM_INVENTORY_DELETE_ITEM"); ?>
	</a>

	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('COM_INVENTORY_DELETE_ITEM'); ?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::sprintf('COM_INVENTORY_DELETE_CONFIRM', $this->item->id); ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			<a href="<?php echo JRoute::_('index.php?option=com_inventory&task=sale.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_INVENTORY_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; ?>

<?php 
	echo $productMod->order_detail($this->item->id);

?>

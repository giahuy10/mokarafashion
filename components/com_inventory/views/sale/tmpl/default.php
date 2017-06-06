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

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_inventory.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_inventory' . $this->item->id))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>

<div class="item_fields">

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
			<td><?php echo $this->item->total; ?></td>
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
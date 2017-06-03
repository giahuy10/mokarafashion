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
			<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_PRODUCT_CATEGORY'); ?></th>
			<td><?php echo $this->item->category; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_PRODUCT_NAME'); ?></th>
			<td><?php echo $this->item->name; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_PRODUCT_CODE'); ?></th>
			<td><?php echo $this->item->code; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_PRODUCT_PRICE'); ?></th>
			<td><?php echo $this->item->price; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_PRODUCT_OLD_PRICE'); ?></th>
			<td><?php echo $this->item->old_price; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_PRODUCT_COLOR'); ?></th>
			<td><?php echo $this->item->color; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_PRODUCT_MATERIAL'); ?></th>
			<td><?php echo $this->item->material; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_PRODUCT_NECK'); ?></th>
			<td><?php echo $this->item->neck; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_PRODUCT_SLEEVE'); ?></th>
			<td><?php echo $this->item->sleeve; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_PRODUCT_TYPE'); ?></th>
			<td><?php echo $this->item->type; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_PRODUCT_SKIRT'); ?></th>
			<td><?php echo $this->item->skirt; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_PRODUCT_INPUT_PRICE'); ?></th>
			<td><?php echo $this->item->input_price; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_PRODUCT_SIZE_S'); ?></th>
			<td><?php echo $this->item->size_s; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_PRODUCT_SIZE_M'); ?></th>
			<td><?php echo $this->item->size_m; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_PRODUCT_SIZE_L'); ?></th>
			<td><?php echo $this->item->size_l; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_INVENTORY_FORM_LBL_PRODUCT_SIZE_XL'); ?></th>
			<td><?php echo $this->item->size_xl; ?></td>
		</tr>

	</table>

</div>

<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_inventory&task=product.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_INVENTORY_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_inventory.product.'.$this->item->id)) : ?>

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
			<a href="<?php echo JRoute::_('index.php?option=com_inventory&task=product.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_INVENTORY_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; ?>
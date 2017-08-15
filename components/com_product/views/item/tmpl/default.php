<?php
/**
 * @version    CVS: 1.0.2
 * @package    Com_Product
 * @author     Eddy Nguyen <contact@eddynguyen.com>
 * @copyright  2017 Eddy Nguyen
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_product');

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_product'))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
var_dump($this->item->jcfields);
?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_TITLE'); ?></th>
			<td><?php echo $this->item->title; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_ALIAS'); ?></th>
			<td><?php echo $this->item->alias; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_CATID'); ?></th>
			<td><?php echo $this->item->catid_title; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_CODE'); ?></th>
			<td><?php echo $this->item->code; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_PRICE'); ?></th>
			<td><?php echo $this->item->price; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_OLD_PRICE'); ?></th>
			<td><?php echo $this->item->old_price; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_INTRO'); ?></th>
			<td><?php echo nl2br($this->item->intro); ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_DESCRIPTION'); ?></th>
			<td><?php echo nl2br($this->item->description); ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_COMBO'); ?></th>
			<td><?php echo $this->item->combo; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_COMBO_PRODUCTS'); ?></th>
			<td><?php echo $this->item->combo_products; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_HOT_DEAL'); ?></th>
			<td><?php echo $this->item->hot_deal; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_DEAL_PRICE'); ?></th>
			<td><?php echo $this->item->deal_price; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_DEAL_FROM'); ?></th>
			<td><?php echo $this->item->deal_from; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_DEAL_TO'); ?></th>
			<td><?php echo $this->item->deal_to; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_DEAL_DAY'); ?></th>
			<td><?php echo $this->item->deal_day; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_IMAGE_1'); ?></th>
			<td><?php echo $this->item->image_1; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_COLOR'); ?></th>
			<td><?php echo $this->item->color; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_COLLAR'); ?></th>
			<td><?php echo $this->item->collar; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_SLEEVE'); ?></th>
			<td><?php echo $this->item->sleeve; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_TYPE'); ?></th>
			<td><?php echo $this->item->type; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_SHAPE'); ?></th>
			<td><?php echo $this->item->shape; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_TAGS'); ?></th>
			<td><?php echo $this->item->tags; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_PRODUCT_FORM_LBL_ITEM_IMAGES'); ?></th>
			<td><?php echo $this->item->images; ?></td>
		</tr>

	</table>

</div>

<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_product&task=item.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_PRODUCT_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_product.item.'.$this->item->id)) : ?>

	<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo JText::_("COM_PRODUCT_DELETE_ITEM"); ?>
	</a>

	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('COM_PRODUCT_DELETE_ITEM'); ?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::sprintf('COM_PRODUCT_DELETE_CONFIRM', $this->item->id); ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			<a href="<?php echo JRoute::_('index.php?option=com_product&task=item.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_PRODUCT_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; ?>
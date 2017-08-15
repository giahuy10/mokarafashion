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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_product', JPATH_SITE);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/media/com_product/js/form.js');

$user    = JFactory::getUser();
$canEdit = ProductHelpersProduct::canUserEdit($this->item, $user);


?>

<div class="item-edit front-end-edit">
	<?php if (!$canEdit) : ?>
		<h3>
			<?php throw new Exception(JText::_('COM_PRODUCT_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<?php if (!empty($this->item->id)): ?>
			<h1><?php echo JText::sprintf('COM_PRODUCT_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
		<?php else: ?>
			<h1><?php echo JText::_('COM_PRODUCT_ADD_ITEM_TITLE'); ?></h1>
		<?php endif; ?>

		<form id="form-item"
			  action="<?php echo JRoute::_('index.php?option=com_product&task=item.save'); ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
			
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php echo $this->form->getInput('created_by'); ?>
				<?php echo $this->form->getInput('modified_by'); ?>
	<?php echo $this->form->renderField('title'); ?>

	<?php echo $this->form->renderField('alias'); ?>

	<?php echo $this->form->renderField('catid'); ?>

	<?php echo $this->form->renderField('code'); ?>

	<?php echo $this->form->renderField('price'); ?>

	<?php echo $this->form->renderField('old_price'); ?>

	<?php echo $this->form->renderField('intro'); ?>

	<?php echo $this->form->renderField('description'); ?>

	<?php echo $this->form->renderField('combo'); ?>

	<?php echo $this->form->renderField('combo_products'); ?>

	<?php foreach((array)$this->item->combo_products as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="combo_products" name="jform[combo_productshidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />';
		<?php endif; ?>
	<?php endforeach; ?>
	<?php echo $this->form->renderField('hot_deal'); ?>

	<?php echo $this->form->renderField('deal_price'); ?>

	<?php echo $this->form->renderField('deal_from'); ?>

	<?php echo $this->form->renderField('deal_to'); ?>

	<?php echo $this->form->renderField('deal_day'); ?>

	<?php echo $this->form->renderField('image_1'); ?>

	<?php echo $this->form->renderField('color'); ?>

	<?php echo $this->form->renderField('collar'); ?>

	<?php echo $this->form->renderField('sleeve'); ?>

	<?php echo $this->form->renderField('type'); ?>

	<?php echo $this->form->renderField('shape'); ?>

	<?php echo $this->form->renderField('tags'); ?>

	<?php echo $this->form->renderField('images'); ?>

			<div class="control-group">
				<div class="controls">

					<?php if ($this->canSave): ?>
						<button type="submit" class="validate btn btn-primary">
							<?php echo JText::_('JSUBMIT'); ?>
						</button>
					<?php endif; ?>
					<a class="btn"
					   href="<?php echo JRoute::_('index.php?option=com_product&task=itemform.cancel'); ?>"
					   title="<?php echo JText::_('JCANCEL'); ?>">
						<?php echo JText::_('JCANCEL'); ?>
					</a>
				</div>
			</div>

			<input type="hidden" name="option" value="com_product"/>
			<input type="hidden" name="task"
				   value="itemform.save"/>
			<?php echo JHtml::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>

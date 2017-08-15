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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'media/com_product/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	js('input:hidden.combo_products').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('combo_productshidden')){
			js('#jform_combo_products option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_combo_products").trigger("liszt:updated");
	});

	Joomla.submitbutton = function (task) {
		if (task == 'item.cancel') {
			Joomla.submitform(task, document.getElementById('item-form'));
		}
		else {
			
			if (task != 'item.cancel' && document.formvalidator.isValid(document.id('item-form'))) {
				
	if(js('#jform_combo_products option:selected').length == 0){
		js("#jform_combo_products option[value=0]").attr('selected','selected');
	}
				Joomla.submitform(task, document.getElementById('item-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_product&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="item-form" class="form-validate">

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_PRODUCT_TITLE_ITEM', true)); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">

									<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
				<div class="row-fluid">
					<div class="span6">
						<?php echo $this->form->renderField('title'); ?>
						<?php echo $this->form->renderField('alias'); ?>
						<?php echo $this->form->renderField('catid'); ?>
						<?php echo $this->form->renderField('code'); ?>
						<?php echo $this->form->renderField('price'); ?>
						<?php echo $this->form->renderField('old_price'); ?>
					</div>
					<div class="span6">
						<?php echo $this->form->renderField('combo'); ?>
				<?php echo $this->form->renderField('combo_products'); ?>

			<?php
				foreach((array)$this->item->combo_products as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="combo_products" name="jform[combo_productshidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>				<?php echo $this->form->renderField('hot_deal'); ?>
				<?php echo $this->form->renderField('deal_price'); ?>
				<?php echo $this->form->renderField('deal_from'); ?>
				<?php echo $this->form->renderField('deal_to'); ?>
				<?php echo $this->form->renderField('deal_day'); ?>
					</div>
				</div>
				
					
				<?php echo $this->form->renderField('created_by'); ?>
				<?php echo $this->form->renderField('modified_by'); ?>				
				
				<?php echo $this->form->renderField('intro'); ?>
				<?php echo $this->form->renderField('description'); ?>
				
				<?php echo $this->form->renderField('image_1'); ?>

				<?php //echo $this->form->renderField('tags'); ?>
				<?php echo $this->form->renderField('images'); ?>


					<?php if ($this->state->params->get('save_history', 1)) : ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
					</div>
					<?php endif; ?>
				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>


		<?php $this->ignore_fieldsets = array('general', 'info', 'detail', 'jmetadata', 'item_associations'); ?>
		<?php echo JLayoutHelper::render('joomla.edit.params', $this); ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>

<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Inventory
 * @author     sugar lead <anjakahuy@gmail.com>
 * @copyright  2017 sugar lead
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
$document->addStyleSheet(JUri::root() . 'media/com_inventory/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	js('input:hidden.coupon_for_categories').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('coupon_for_categorieshidden')){
			js('#jform_coupon_for_categories option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_coupon_for_categories").trigger("liszt:updated");
	js('input:hidden.coupon_for_products').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('coupon_for_productshidden')){
			js('#jform_coupon_for_products option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_coupon_for_products").trigger("liszt:updated");
	});

	Joomla.submitbutton = function (task) {
		if (task == 'coupon.cancel') {
			Joomla.submitform(task, document.getElementById('coupon-form'));
		}
		else {
			
			if (task != 'coupon.cancel' && document.formvalidator.isValid(document.id('coupon-form'))) {
				
	if(js('#jform_coupon_for_categories option:selected').length == 0){
		js("#jform_coupon_for_categories option[value=0]").attr('selected','selected');
	}
	if(js('#jform_coupon_for_products option:selected').length == 0){
		js("#jform_coupon_for_products option[value=0]").attr('selected','selected');
	}
				Joomla.submitform(task, document.getElementById('coupon-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_inventory&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="coupon-form" class="form-validate">

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_INVENTORY_TITLE_COUPON', true)); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">

									<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php echo $this->form->renderField('created_by'); ?>
				<?php echo $this->form->renderField('modified_by'); ?>				<?php echo $this->form->renderField('coupon_code'); ?>
				<?php echo $this->form->renderField('coupon_type'); ?>
				<?php echo $this->form->renderField('coupon_value'); ?>
				<?php echo $this->form->renderField('coupon_for_categories'); ?>

			<?php
				foreach((array)$this->item->coupon_for_categories as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="coupon_for_categories" name="jform[coupon_for_categorieshidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>				<?php echo $this->form->renderField('coupon_for_products'); ?>

			<?php
				foreach((array)$this->item->coupon_for_products as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="coupon_for_products" name="jform[coupon_for_productshidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>				<?php echo $this->form->renderField('coupon_for_order'); ?>
				<?php echo $this->form->renderField('coupon_limit'); ?>
				<?php echo $this->form->renderField('coupon_for_group_user'); ?>
				<?php echo $this->form->renderField('coupon_one_time'); ?>
				<?php echo $this->form->renderField('coupon_from'); ?>
				<?php echo $this->form->renderField('coupon_to'); ?>


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

		

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>

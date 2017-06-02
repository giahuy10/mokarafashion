<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_tags_popular
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_mokara/models', 'MokaraModel');
$productMod = JModelLegacy::getInstance('Product', 'MokaraModel', array('ignore_request' => true));
$cat_id = JRequest::getVar('id');
$fieds = $productMod->get_fields($cat_id);
$filters = array();
$selected = array();
?>
<form id="myForm" action="<?php echo JRoute::_('index.php?option=com_mokara&view=filter&Itemid=528')?>">

<?php $n=0; foreach ($fieds as $field) {?>
	<?php 
	$value = JRequest::getVar('field_'.$field->id);	
	?>
	<h3><?php echo $field->title?>
		<?php if ($value) {?>
			<button class="btn btn-danger remove-selected"onclick="resetForm(<?php echo 'field_'.$field->id?>)" title="Xóa lựa chọn <?php echo $field->title?>"><i class="fa fa-times" aria-hidden="true"></i></button>
		<?php }?>
	</h3>
	
	<div class="filer-box">
		<?php  
			if ($value > 0) {
				$selected[$field->id]['check'] = 1; 
				$n = $n+count($productMod->get_items($field->id, $value, $cat_id));
				$filters[]=$productMod->get_items($field->id, $value, $cat_id, $page);
				$selected[$field->id]['value'] = $value;
		
			}
			$options = json_decode($field->fieldparams)->options;
			$selected[$field->id]['options'] = $options;
		?>
		<?php if ($field->id == 15) {?>
			<?php foreach ($options as $option) {?>
				<label title="<?php echo $option->name?>" for="color_<?php echo $option->value?>"><span class="btn color-box color_<?php echo $option->value?> <?php if ($option->value == $value) echo "active";?>"></span></label>
				<input class="hidden" type="radio" name="field_<?php echo $field->id?>" value="<?php echo $option->value?>" id="color_<?php echo $option->value?>" <?php if ($option->value == $value) echo "checked";?> onchange="this.form.submit()"/>
				
			<?php }?>
		<?php } elseif($field->id == 14) {?>
			<?php foreach ($options as $option) {?>
				<label for="price_<?php echo $option->value?>"><span class="btn btn-price <?php if ($option->value == $value) echo "active";?>"><?php echo $option->name?></span></label>
				<input class="hidden" type="radio" name="field_<?php echo $field->id?>" value="<?php echo $option->value?>" id="price_<?php echo $option->value?>" <?php if ($option->value == $value) echo "checked";?> onchange="this.form.submit()"/>
				
			<?php }?>
		<?php } elseif($field->id == 5) {?>
			<?php foreach ($options as $option) {?>
				<label for="label_<?php echo $option->value?>"><span class="btn btn-label label_<?php echo $option->value?> <?php if ($option->value == $value) echo "active";?>"><?php echo $option->name?></span></label>
				<input class="hidden" type="radio" name="field_<?php echo $field->id?>" value="<?php echo $option->value?>" id="label_<?php echo $option->value?>" <?php if ($option->value == $value) echo "checked";?> onchange="this.form.submit()"/>
				
			<?php }?>	
		<?php } else {?>
			<?php foreach ($options as $option) {?>
				<label for="field_<?php echo $field->id."_".$option->value?>"><span class="btn btn-eddy  <?php if ($option->value == $value) echo "active";?>"><?php echo $option->name?></span></label>
				<input class="hidden" type="radio" name="field_<?php echo $field->id?>" value="<?php echo $option->value?>" id="field_<?php echo $field->id."_".$option->value?>" <?php if ($option->value == $value) echo "checked";?> onchange="this.form.submit()"/>
				
			<?php }?>
		<?php }?>
	</div>
<?php } ?>
<br/>
<input type="hidden" name="option" value="com_mokara"/>
<input type="hidden" name="view" value="filter"/>
<input type="hidden" name="Itemid" value="528"/>
<input type="hidden" name="page" value="1"/>
<input type="hidden" name="cat_id" value="<?php echo $cat_id?>"/>
		</form>

<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_tags_popular
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$fieds = ModTagsFilter::getList();
$filters = array();
?>
<form id="myForm">
<?php foreach ($fieds as $field) {?>
	<h3><?php echo $field->title?></h3>
	<div class="filer-box">
		
		<select name="field_<?php echo $field->id?>" onchange="this.form.submit()">
			<option value="">Vui lòng chọn</option>
		<?php 
			
			$options = json_decode($field->fieldparams)->options;
			foreach ($options as $option) {?>
				<option value="<?php echo $option->value?>"><?php echo $option->name?></option>
			<?php }
			
		?>
		</select>
	</div>
<?php } ?>

<input type="hidden" name="option" value="com_mokara"/>
<input type="hidden" name="view" value="filter"/>
<input type="hidden" name="Itemid" value="528"/>
</form>

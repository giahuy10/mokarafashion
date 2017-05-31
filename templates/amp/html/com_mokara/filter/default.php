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
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_content/models', 'MokaraModel');
$productMod = JModelLegacy::getInstance('Product', 'MokaraModel', array('ignore_request' => true));

JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');
$articleMod = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));
$appParams = JFactory::getApplication()->getParams();
$articleMod->setState('params', $appParams);
$user       = JFactory::getUser();
$userId     = $user->get('id');
$cat_id = JRequest::getVar('cat_id');
$fieds = $productMod->get_fields($cat_id);
$filters = array();

$selected = array();
if (JRequest::getVar('page')) {
	$page = JRequest::getVar('page');
}
else {
	$page = 1;
}
?>

<?php 


?>

<div class="row">
<div class="col-xs-12 col-sm-3 filter-module" >
<button class="btn btn-info visible-xs" id="fillter-button"><i class="fa fa-search"></i>Tìm kiếm sản phẩm</button>
<script>
jQuery(function($) {
    $("button#fillter-button").click(function(){
        $("#search-box").toggle();
    });
});
</script>
<div class="" id="search-box">
<div class="sticky-fillter" >
<form id="myForm" action="<?php echo JRoute::_('index.php?option=com_mokara&view=filter&Itemid=528')?>">
	<h3 class="field-title">Danh mục</h3>
	<div class="filer-box">
	<?php $cats = $productMod->get_categories();

		foreach ($cats as $cat) { ?>

			<label for="cat_<?php echo $cat->id?>"><img title="<?php echo $cat->title?>" class="cat_img <?php if ($cat_id == $cat->id) echo "active";?>" src="images/category-icon/<?php echo $cat->alias?>.png"/></label>
			
				<input class="hidden" type="radio" name="cat_id" value="<?php echo $cat->id?>" id="cat_<?php echo $cat->id?>" <?php if ($cat_id == $cat->id) echo "checked";?> onchange="this.form.submit()"/>
		<?php }
		?>
	</div>
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
<a class="btn btn-danger" href="<?php echo JRoute::_('index.php?option=com_mokara&view=filter&Itemid=528')?>">Xóa bộ lọc</a>
<?php 

		
		if (count($filters) > 1 ){
			
			$items = $filters[0];
			for ($i = 1; $i< count($filters); $i++) {
				$items = array_intersect($items,$filters[$i]);
			}
			$n = count($items);
		}elseif (count($filters) == 1) {
			
			$items = $filters[0];
			$n = count($items);
		}else {
		
			$items = $productMod->get_items(0, 0, $cat_id, $page);
			$n = count($productMod->get_items(0, 0, $cat_id));
		}
		
		
	?>

<?php 
			 $limit = 20;


			$pages = ceil($n / $limit);
			echo '<select name="page" onchange="this.form.submit()">';
				for ($p = 1; $p<=$pages; $p++) {
		?>	
			
				<option value="<?php echo $p?>" <?php if ($p == $page) echo "selected";?>>Trang <?php echo $p?></option>
				<?php }?>
				</select>
		</form>
</div>
</div>
</div>
<div class="col-xs-12 col-sm-9">
	
	
	<h3 class="field-title">Hiện có <?php echo $n?> sản phẩm
		<?php
			if (isset($selected[5]['check'])){
				foreach ($selected[5]['options'] as $selected_option) {
					if ($selected_option->value == $selected[5]['value']) {
						echo '<span class="btn btn-label label_'.$selected_option->value.'">'.$selected_option->name.'</span>';
						break;
					}
				}
			}
			?>
		<?php
			if (isset($selected[15]['check'])){
				foreach ($selected[15]['options'] as $selected_option) {
					if ($selected_option->value == $selected[15]['value']) {
						echo ' màu <span class="btn color-box color_'.$selected_option->value.' text-color">'.$selected_option->name.'</span>';
						break;
					}
				}
			}
			?>	
		<?php
			if (isset($selected[12]['check'])){
				foreach ($selected[12]['options'] as $selected_option) {
					if ($selected_option->value == $selected[12]['value']) {
						echo ' chất liệu <span class="btn btn-eddy active">'.$selected_option->name.'</span>';
						break;
					}
				}
			}
			?>	
		<?php if ($cat_id) {
				echo " trong danh mục ".$productMod->get_categories($cat_id)[0]->title;
			}
		?>
		<?php
			if (isset($selected[14]['check'])){
				foreach ($selected[14]['options'] as $selected_option) {
					if ($selected_option->value == $selected[14]['value']) {
						echo ' giá từ <span class="btn btn-price ">'.$selected_option->name.'</span>';
						break;
					}
				}
			}
			?>	
		
	</h3>
	 <div class="row items-row">
	<?php 
		$clear = 0;
			foreach ($items as  $item) : ?>
					
					
					
						<div  class="col-xs-12 col-sm-6 col-md-6 col-lg-4 items-on-row">
							
							
							<?php 
								
								
								$content = $articleMod->getItem($item);
								
								$productMod->show_product_item($content);
							?>
							
						</div>
						
				<?php 
					$clear++;
					if ($clear%3==0) {
						echo '<div class="clearfix visible-lg"></div>';
					}
					if ($clear%2==0) {
						echo '<div class="clearfix visible-md"></div>';
						echo '<div class="clearfix visible-sm"></div>';
					}
				?>
					
				<?php endforeach;
					
		
		
				?>
				</div>
</div>		

</div>
<script>
	function resetForm(ele) {
    for(var i=0;i<ele.length;i++)
      ele[i].checked = false;
}
</script>
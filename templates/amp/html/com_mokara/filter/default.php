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

JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_mokara/models', 'MokaraModel');
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

<div class="" id="search-box">
<div class="sticky-fillter" >
<h3 class="text-center">Tìm kiếm sản phẩm</h3>
<form id="myForm" method="post" action-xhr="<?php echo JURI::root(true).JRoute::_('index.php?option=com_mokara&view=filter&Itemid=528')?>" target="_top">
	
	<div class="filer-box">
	<select name="cat_id" class="filter-select">
		<option value="">Chọn danh mục</option>
	
		<?php $cats = $productMod->get_categories();
		foreach ($cats as $cat) { ?>
			
				<option value="<?php echo $cat->id?>" <?php if ($cat_id == $cat->id) echo "selected";?>><?php echo $cat->title?></option>
				
		<?php }?>
		</select>
	</div>
<?php $n=0; foreach ($fieds as $field) {?>
	<?php $value = JRequest::getVar('field_'.$field->id);?>

	
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
		<select name="<?php echo 'field_'.$field->id?>" class="filter-select">
			<option value="">Chọn <?php echo $field->title?></option>
		
			<?php foreach ($options as $option) {?>
				<option value="<?php echo $option->value?>" <?php if ($value == $option->value) echo "selected";?>><?php echo $option->name?></option>
				
			<?php }?>	
		</select>		
		
	</div>
<?php } ?>
<br/>
<button type="submit" class="btn btn-buy" name="submit">Tìm kiếm</button> | <a class="btn btn-danger" href="<?php echo JRoute::_('index.php?option=com_mokara&view=filter&Itemid=528')?>">Xóa bộ lọc</a>
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
			echo '<select name="page">';
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
	
	

	 <div class="row items-row">
	<?php 
		$clear = 0;
			foreach ($items as  $item) : ?>
					
					
					
						<div  class="items-on-row">
							
							
							<?php 
								
								
								$content = $articleMod->getItem($item);
								
								$productMod->show_product_item_amp($content);
							?>
							
						</div>
						
				
					
				<?php endforeach;
					
		
		
				?>
				</div>
</div>		

</div>

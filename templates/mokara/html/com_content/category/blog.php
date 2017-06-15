<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$user = JFactory::getUser();
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_mokara/models', 'MokaraModel');
$productMod = JModelLegacy::getInstance('Product', 'MokaraModel', array('ignore_request' => true));

$cat_id = JRequest::getVar('id');
$fieds = $productMod->get_fields($cat_id);

$dispatcher = JEventDispatcher::getInstance();

$this->category->text = $this->category->description;
$dispatcher->trigger('onContentPrepare', array($this->category->extension . '.categories', &$this->category, &$this->params, 0));
$this->category->description = $this->category->text;

$results = $dispatcher->trigger('onContentAfterTitle', array($this->category->extension . '.categories', &$this->category, &$this->params, 0));
$afterDisplayTitle = trim(implode("\n", $results));

$results = $dispatcher->trigger('onContentBeforeDisplay', array($this->category->extension . '.categories', &$this->category, &$this->params, 0));
$beforeDisplayContent = trim(implode("\n", $results));

$results = $dispatcher->trigger('onContentAfterDisplay', array($this->category->extension . '.categories', &$this->category, &$this->params, 0));
$afterDisplayContent = trim(implode("\n", $results));

$tag_id = JRequest::getVar('filter_tag');
$doc = JFactory::getDocument();
$title = $this->category->title;
if ($tag_id > 0) {
	
	$title = $this->category->title.' - '.$productMod->get_tag_title($tag_id);
	$description = $this->category->description.' - '.$productMod->get_tag_title($tag_id);
	
				$doc->setDescription(strip_tags($description));
				$doc->setTitle(strip_tags($title));
}
				
				$doc->addCustomTag( '
				<meta property="og:title" content="'.strip_tags($title).'"/>
				<meta property="og:type" content="product"/>
				<meta property="og:email" content="web@mokara.com.vn"/>
				<meta property="og:url" content="'.JURI::current().'"/>
				<meta property="og:image" content="'.JURI::base().'images/san-pham/img_products/DK_1712_-_2140000.jpg"/>
				<meta property="og:site_name" content="Thời trang công sở cao cấp Mokara"/>
				<meta property="fb:admins" content="Eddy Nguyen"/>
				<meta property="og:description" content="'.strip_tags($description).'"/>
				');		
?>
<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">
<div class="row">
<div class="product<?php echo $this->pageclass_sfx; ?> col-xs-12 col-sm-9">
	
			<h1> <?php echo $title?> </h1>
		

	
	<?php echo $afterDisplayTitle; ?>

	



	<?php if (empty($this->lead_items) && empty($this->link_items) && empty($this->intro_items)) : ?>
		<?php if ($this->params->get('show_no_articles', 1)) : ?>
			<p><?php echo JText::_('COM_CONTENT_NO_PRODUCTS'); ?></p>
		<?php endif; ?>
	<?php endif; ?>



	<?php
	$introcount = count($this->intro_items);
	$counter = 0;
	$product_ids = array();
	//echo count($this->items);
	?>

	<?php if (!empty($this->intro_items)) : ?>
		<?php foreach ($this->intro_items as $key => &$item) : ?>
			<?php $rowcount = ((int) $key % (int) $this->columns) + 1; ?>
			<?php if ($rowcount === 1) : ?>
				<?php $row = $counter / $this->columns; ?>
				<div class="items-row cols-<?php echo (int) $this->columns; ?> <?php echo 'row-' . $row; ?> row-fluid clearfix">
			<?php endif; ?>
			<div class="col-xs-12 col-sm-<?php echo round(12 / $this->columns); ?>">
				<div class="items-on-row item column-<?php echo $rowcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>"
					>
					<?php
					$this->item = & $item;
					$product_ids[]=$this->item->id;
					$productMod->show_product_item($this->item);
					?>
				</div>
				<!-- end item -->
				<?php $counter++; ?>
			</div><!-- end span -->
			<?php if (($rowcount == $this->columns) or ($counter == $introcount)) : ?>
				</div><!-- end row -->
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>

	
	<?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
		<div class="pagination">
			<?php if ($this->params->def('show_pagination_results', 1)) : ?>
				<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
			<?php endif; ?>
			<?php echo $this->pagination->getPagesLinks(); ?> </div>
	<?php endif; ?>
</div>
<?php if ($product_ids) {?>
	<div class="col-xs-12 col-sm-3">

		<?php foreach ($fieds as $field) {?>
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

			$options = json_decode($field->fieldparams)->options;
			
		?>
		<?php foreach ($options as $option) {?>
			<?php $existed = $productMod->check_filter_value($product_ids, $field->id, $option->value)?>
				
				<label for="field_<?php echo $field->id."_".$option->value?>" class="<?php if (!$existed) echo "hidden"?>"  >
				
				<span class="<?php echo 'btn btn_field btn_field_'.$field->name.' btn_field_value_'.$option->value?> <?php if ($option->value == $value) echo "active";?>"><?php echo $option->name?></span>
				
				</label>
				<input class="hidden" type="radio" name="field_<?php echo $field->id?>" value="<?php echo $option->value?>" id="field_<?php echo $field->id."_".$option->value?>" <?php if ($option->value == $value) echo "checked";?> onchange="SubmitForm('adminForm');"/>
				
			<?php }?>
	</div>
<?php } ?>

	</div>
<?php }?>
		</div>
		
</form>
<script>
	
	function resetForm(ele) {
    for(var i=0;i<ele.length;i++)
      ele[i].checked = false;
}
function SubmitForm(formId) {
    var oForm = document.getElementById(formId);
    if (oForm) {
        oForm.submit(); 
    }
    else {
        alert("DEBUG - could not find element " + formId);
    }
}
</script>

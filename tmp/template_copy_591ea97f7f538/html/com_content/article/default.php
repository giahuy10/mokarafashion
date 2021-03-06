<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

// Create shortcuts to some parameters.
$params  = $this->item->params;
$images  = json_decode($this->item->images);
$urls    = json_decode($this->item->urls);
$canEdit = $params->get('access-edit');
$user    = JFactory::getUser();
$info    = $params->get('info_block_position', 0);

// Check if associations are implemented. If they are, define the parameter.
$assocParam = (JLanguageAssociations::isEnabled() && $params->get('show_associations'));
JHtml::_('behavior.caption');


?>

<div class="item-page<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Article">
	<meta itemprop="inLanguage" content="<?php echo ($this->item->language === '*') ? JFactory::getConfig()->get('language') : $this->item->language; ?>" />
	<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page-header">
		<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
	</div>
	<?php endif;


	?>









	<?php if ($params->get('access-view')) : ?>

	

	
	<?php if ($this->item->jcfields) { // Product layout?>
	<?php 
	include ("./cartfunction.php");
$this->item = get_custom_field($this->item);
	?>
		<div class="row">
			<div class="col-xs-12 col-sm-6 ed-media-block">
				<?php $pro_image = get_product_image($this->item->sku);
				//var_dump($pro_image);
				?>
				<img class="main-img" src="images/san-pham/<?php echo $this->item->sku."/".$pro_image[0]?>" alt="<?php echo $this->item->title?>"/>
				<?php for ($i = 1; $i< count($pro_image); $i++) {?>
					<img class="thumb" src="images/san-pham/<?php echo $this->item->sku."/".$pro_image[$i]?>" alt="<?php echo $this->item->title?>"/>
				<?php }?>
			</div>
			<div class="col-xs-12 col-sm-6 ed-shopping-block">
					<div class="page-header">
	
			<h2 itemprop="headline">
				<?php echo $this->escape($this->item->title); ?>
			</h2>
		
	</div>
				<?php if ($this->item->product_old_price) {?>
					<div class="old_price"><strong><?php echo JText::_('COM_CONTENT_OLD_PRICE'); ?>: </strong><s><?php echo number_format($this->item->product_old_price)?></s></div>
				<?php }?>
				<div class="price">
					<strong><?php if ($this->item->product_old_price) {
						echo JText::_('COM_CONTENT_SALE_PRICE');
					}
						else {
						echo JText::_('COM_CONTENT_PRICE');
						}
					?>: </strong> 
					<?php echo number_format($this->item->product_price)?>
				</div>
				<form action="<?php echo JRoute::_('index.php?option=com_mokara&view=orders&Itemid=502')?>" method="post">
				<input type="number" min="1" name="quantity" value="1" />
					<button type="submit" name="submit" class="btn btn-default"><?php echo JText::_('COM_CONTENT_ADD_TO_CART')?></button>
					<input type="hidden" name="product_id" value="<?php echo $this->item->id?>"/>
					<input type="hidden" name="option" value="com_mokara"/>
					<input type="hidden" name="view" value="orders"/>
					<input type="hidden" name="task" value="add2cart"/>
					<input type="hidden" name="Itemid" value="502"/>
					<input type="hidden" name="product_name" value="<?php echo $this->item->title?>"/>
					<input type="hidden" name="product_img" value="<?php echo "images/san-pham/".$this->item->sku."/".$pro_image[0]?>"/>
					<input type="hidden" name="product_price" value="<?php echo $this->item->product_price?>"/>
					<input type="hidden" name="product_old_price" value="<?php echo $this->item->product_old_price?>"/>
					<input type="hidden" name="product_category_id" value="<?php echo $this->item->catid?>"/>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 ed-description-block">
			</div>
		</div>
	<?php }	else { //News layout?>
		<div itemprop="articleBody">
		<?php echo $this->item->text; ?>
	</div>
	<?php }?>
	

		
		<?php if ($params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
			<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
			<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
		<?php endif; ?>
	<?php endif; ?>

	

	

	<?php // Content is generated by content plugin event "onContentAfterDisplay" ?>
	<?php echo $this->item->event->afterDisplayContent; ?>
</div>

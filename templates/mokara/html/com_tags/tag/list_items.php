<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_tags
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.core');
JHtml::_('formbehavior.chosen', 'select');

$n         = count($this->items);
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
JFactory::getDocument()->addScriptDeclaration("
		var resetFilter = function() {
		document.getElementById('filter-search').value = '';
	}
");
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');
$model = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));
$appParams = JFactory::getApplication()->getParams();
$model->setState('params', $appParams);
?>

<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">
	<?php if ($this->params->get('filter_field') || $this->params->get('show_pagination_limit')) : ?>
	<fieldset class="filters btn-toolbar">
		<?php if ($this->params->get('filter_field')) : ?>
			<div class="btn-group">
				<label class="filter-search-lbl element-invisible" for="filter-search">
					<?php echo JText::_('COM_TAGS_TITLE_FILTER_LABEL') . '&#160;'; ?>
				</label>
				<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_TAGS_FILTER_SEARCH_DESC'); ?>" placeholder="<?php echo JText::_('COM_TAGS_TITLE_FILTER_LABEL'); ?>" />
				<button type="button" name="filter-search-button" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>" onclick="document.adminForm.submit();" class="btn">
					<span class="icon-search"></span>
				</button>
				<button type="reset" name="filter-clear-button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" class="btn" onclick="resetFilter(); document.adminForm.submit();">
					<span class="icon-remove"></span>
				</button>
			</div>
		<?php endif; ?>
		<?php if ($this->params->get('show_pagination_limit')) : ?>
			<div class="btn-group pull-right">
				<label for="limit" class="element-invisible">
					<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
				</label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
		<?php endif; ?>

		<input type="hidden" name="filter_order" value="" />
		<input type="hidden" name="filter_order_Dir" value="" />
		<input type="hidden" name="limitstart" value="" />
		<input type="hidden" name="task" value="" />
		<div class="clearfix"></div>
	</fieldset>
	<?php endif; ?>

	<?php if ($this->items == false || $n == 0) : ?>
		<p> <?php echo JText::_('COM_TAGS_NO_ITEMS'); ?></p>
	<?php else : ?>
		
				<?php $k=0; foreach ($this->items as $i => $item) : ?>
					
					<?php if ($k == 0) : ?>
					 <div class="row items-row">
					
					<?php endif; ?>
					
						<div  class="col-xs-12 col-sm-4">
							
							
							<?php 
								
								
								$content = $model->getItem($item->content_item_id);
								
								show_product_item($content);
							?>
							
						</div>
						

					<?php 
						$k++;
						
						if ($k == 3) {
							echo "</div>";
							$k=0;
							
						}
					?>
				<?php endforeach;
					if ($n%3 != 0)
						echo "</div>";	
				?>
			
	<?php endif; ?>

<?php // Add pagination links ?>
<?php if (!empty($this->items)) : ?>
	<?php if (($this->params->def('show_pagination', 2) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
	<div class="pagination">

		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
			<p class="counter pull-right">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
		<?php endif; ?>

		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
	<?php endif; ?>
<?php endif; ?>
</form>

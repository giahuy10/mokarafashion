<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Inventory
 * @author     Eddy Nguyen <contact@eddynguyen.com>
 * @copyright  2017 Eddy Nguyen
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_inventory/assets/css/inventory.css');
$document->addStyleSheet(JUri::root() . 'media/com_inventory/css/list.css');

$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_inventory');
$saveOrder = $listOrder == 'a.`ordering`';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_inventory&task=products.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'productList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
?>

<form action="<?php echo JRoute::_('index.php?option=com_inventory&view=products'); ?>" method="post"
	  name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>

            <?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

			<div class="clearfix"></div>
			<table class="table table-striped" id="productList">
				<thead>
				<tr>
					<?php if (isset($this->items[0]->ordering)): ?>
						<th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', '#', 'a.`ordering`', $listDirn, $listOrder); ?>
						</th>
					<?php endif; ?>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value=""
							   title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
					</th>
					<?php if (isset($this->items[0]->state)): ?>
						<th width="1%" class="nowrap center">
								<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.`state`', $listDirn, $listOrder); ?>
</th>
					<?php endif; ?>

									<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_INVENTORY_PRODUCTS_ID', 'a.`id`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_INVENTORY_PRODUCTS_CATEGORY', 'a.`category`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_INVENTORY_PRODUCTS_NAME', 'a.`title`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_INVENTORY_PRODUCTS_CODE', 'a.`code`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_INVENTORY_PRODUCTS_PRICE', 'a.`price`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_INVENTORY_PRODUCTS_COLOR', 'a.`color`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_INVENTORY_PRODUCTS_MATERIAL', 'a.`material`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_INVENTORY_PRODUCTS_NECK', 'a.`neck`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_INVENTORY_PRODUCTS_SLEEVE', 'a.`sleeve`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_INVENTORY_PRODUCTS_TYPE', 'a.`type`', $listDirn, $listOrder); ?>
				</th>

					
				</tr>
				</thead>
				<tfoot>
				<tr>
					<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
				</tfoot>
				<tbody>
				<?php foreach ($this->items as $i => $item) :
					$ordering   = ($listOrder == 'a.ordering');
					$canCreate  = $user->authorise('core.create', 'com_inventory');
					$canEdit    = $user->authorise('core.edit', 'com_inventory');
					$canCheckin = $user->authorise('core.manage', 'com_inventory');
					$canChange  = $user->authorise('core.edit.state', 'com_inventory');
					?>
					<tr class="row<?php echo $i % 2; ?>">

						<?php if (isset($this->items[0]->ordering)) : ?>
							<td class="order nowrap center hidden-phone">
								<?php if ($canChange) :
									$disableClassName = '';
									$disabledLabel    = '';

									if (!$saveOrder) :
										$disabledLabel    = JText::_('JORDERINGDISABLED');
										$disableClassName = 'inactive tip-top';
									endif; ?>
									<span class="sortable-handler hasTooltip <?php echo $disableClassName ?>"
										  title="<?php echo $disabledLabel ?>">
							<i class="icon-menu"></i>
						</span>
									<input type="text" style="display:none" name="order[]" size="5"
										   value="<?php echo $item->ordering; ?>" class="width-20 text-area-order "/>
								<?php else : ?>
									<span class="sortable-handler inactive">
							<i class="icon-menu"></i>
						</span>
								<?php endif; ?>
							</td>
						<?php endif; ?>
						<td class="hidden-phone">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						<?php if (isset($this->items[0]->state)): ?>
							<td class="center">
								<?php echo JHtml::_('jgrid.published', $item->state, $i, 'products.', $canChange, 'cb'); ?>
</td>
						<?php endif; ?>

										<td>

					<?php echo $item->id; ?>
				</td>				<td>

					<?php echo $item->category; ?>
				</td>				<td>
				<?php if (isset($item->checked_out) && $item->checked_out && ($canEdit || $canChange)) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'products.', $canCheckin); ?>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_inventory&task=product.edit&id='.(int) $item->id); ?>">
					<?php echo $this->escape($item->title); ?></a>
				<?php else : ?>
					<?php echo $this->escape($item->title); ?>
				<?php endif; ?>

				</td>				<td>

					<?php echo $item->code; ?>
				</td>				<td>

					<?php echo $item->price; ?>
				</td>				<td>

					<?php echo $item->color; ?>
				</td>				<td>

					<?php echo $item->material; ?>
				</td>				<td>

					<?php echo $item->neck; ?>
				</td>				<td>

					<?php echo $item->sleeve; ?>
				</td>				<td>

					<?php echo $item->type; ?>
				</td>

					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="list[fullorder]" value="<?php echo $listOrder; ?> <?php echo $listDirn; ?>"/>
			<?php echo JHtml::_('form.token'); ?>
		</div>
</form>
<script>
    window.toggleField = function (id, task, field) {

        var f = document.adminForm, i = 0, cbx, cb = f[ id ];

        if (!cb) return false;

        while (true) {
            cbx = f[ 'cb' + i ];

            if (!cbx) break;

            cbx.checked = false;
            i++;
        }

        var inputField   = document.createElement('input');

        inputField.type  = 'hidden';
        inputField.name  = 'field';
        inputField.value = field;
        f.appendChild(inputField);

        cb.checked = true;
        f.boxchecked.value = 1;
        window.submitform(task);

        return false;
    };
</script>
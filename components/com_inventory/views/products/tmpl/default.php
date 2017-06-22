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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_inventory/models', 'InventoryModel');
$productMod = JModelLegacy::getInstance('Product', 'InventoryModel', array('ignore_request' => true));

$user       = JFactory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_inventory') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'productform.xml');
$canEdit    = $user->authorise('core.edit', 'com_inventory') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'productform.xml');
$canCheckin = $user->authorise('core.manage', 'com_inventory');
$canChange  = $user->authorise('core.edit.state', 'com_inventory');
$canDelete  = $user->authorise('core.delete', 'com_inventory');

				
?>
<?php echo'index.php?option=com_inventory&view=products&id=29:vay-dam&color=25:mau-den&neck=54:tron&sleeve=12:sat-nach&type=6:dam-dao-pho&price_range=20:1500000-2000000'?>
<br/>
<?php echo JRoute::_('index.php?option=com_inventory&view=products&id=29:vay-dam&color=25&neck=54&sleeve=12&type=6&price_range=20'); ?>
<?php //echo $item->code; ?>
<?php //echo $item->price; ?>
<?php //echo $item->color; ?>
<?php //echo $item->material; ?>
<?php //echo $item->neck; ?>
<?php //echo $item->sleeve; ?>
<?php //echo $item->type; ?>
<form action="<?php echo JRoute::_('index.php?option=com_inventory&view=products'); ?>" method="post"
      name="adminForm" id="adminForm">

	<?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
	<?php foreach ($this->items as $i => $item) { ?>
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<?php 
			$html ='<div class="ed-inner-product " itemscope itemtype="http://schema.org/Product">';
			$html .= '<span itemprop="brand" class="hidden">Mokara</span>';
			
					
					
					$link = JRoute::_('index.php?option=com_inventory&view=product&id='.$item->slug.'&category='.$item->catslug.'&Itemid=528');
					$html .='<div class="ed-item-img">';
					$html .='	<a href="'.$link.'"><img itemprop="image" class="product-thumb-desk" src="'.$item->image_1.'" alt="'.$item->title.'"/></a>';
					$html .='</div>';
					$html .='<div class="ed-product-content">';
					$html .='<div class="page-header">';
					$html .='<h2 itemprop="name">';
					$html .='<a href="'.$link.'" itemprop="url">'.$item->title.'</a>';
					$html .='</h2>';
					$html .='</div>';
					$html .='<span itemprop="aggregateRating" class="hidden" itemscope itemtype="http://schema.org/AggregateRating">
							Average rating: <span itemprop="ratingValue">4.4</span>, based on
							<span itemprop="ratingCount">89</span> reviews
						  </span>';
					$html .= $item->introtext; 
					$html .='<div class="ed-price-block" itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer">';
					$html .= ' <meta itemprop="priceCurrency" content="VND" />';
					$html .= '<span itemprop="lowPrice" class="hidden">'.$item->price.'</span>';
					$html .='<div class="price pull-left">';
					$html .=$productMod->ed_number_format($item->price);
					$html .='</div>';
					if ($item->old_price) {
							$html .= '<span itemprop="highPrice" class="hidden">'.$item->old_price.'</span>';
							$html .='<div class="old_price pull-right"><strike>'.$productMod->ed_number_format($item->old_price).'</strike></div>';
						 }
					$html .='<div class="clearfix"></div>';
					$html .='</div>';	
					
						
						$html .='<div class="clearfix"></div>';
				$html .='</div>';
			$html .='</div>	';
			echo $html;
		?>
		</div>
	<?php }?>
	

	<?php echo $this->pagination->getListFooter(); ?>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>



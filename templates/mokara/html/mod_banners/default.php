<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_banners
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('BannerHelper', JPATH_ROOT . '/components/com_banners/helpers/banner.php');
$baseurl = JUri::base();
?>
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
	 <?php $i=0; foreach ($list as $item) : ?>
    <li data-target="#carousel-example-generic" data-slide-to="<?php echo $i?>" class="<?php if ($i==0) echo "active"?>"></li>
	<?php $i++; endforeach; ?>
  
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
	   <?php $i=0; foreach ($list as $item) : ?>
		<?php $link = JRoute::_('index.php?option=com_banners&task=click&id=' . $item->id); ?>
			
				<?php $imageurl = $item->params->get('imageurl'); ?>
				
				<?php $alt = $item->name; ?>
		 <div class="item <?php if ($i==0) echo "active"?>">
		 <a href="<?php echo $link?>">
		  <img src="<?php echo $imageurl?>" alt="<?php echo $alt?>"/>
		  </a>
		</div>
		
			
				
		
			
	<?php $i++; endforeach; ?>
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>


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

<div class="bannergroup<?php echo $moduleclass_sfx; ?>">

<amp-carousel width="767"
  height="360"
  layout="responsive"
  type="slides"
  autoplay
  delay="2000">
  <?php foreach ($list as $item) : ?>
  <?php $link = JRoute::_('index.php?option=com_banners&task=click&id=' . $item->id); ?>
  <?php $imageurl = $item->params->get('imageurl'); ?>
			<?php $width = $item->params->get('width'); ?>
			<?php $height = $item->params->get('height'); ?>
	<a href="<?php echo $link?>">			
  <amp-img src="<?php echo $imageurl?>"
    width="767"
    height="360"
    layout="responsive"
    alt="<?php echo $item->name?>"></amp-img>
	</a>
	<?php endforeach; ?>
</amp-carousel>

</div>

<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_popular
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
include ("./cartfunction_amp.php");
?>
<div class="ed-mostread <?php echo $moduleclass_sfx; ?> row">
<?php 
	$i=0;
foreach ($list as $item) : ?>
	
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3" itemscope itemtype="https://schema.org/Article">
	
		<?php show_product_item($item)?>
		
	</div>

<?php endforeach; ?>
</div>

<?php
/**
 * wbAMP - Accelerated Mobile Pages for Joomla!
 *
 * @author      Yannick Gaultier
 * @copyright   (c) Yannick Gaultier - Weeblr llc - 2017
 * @package     wbAmp
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     1.8.0.647
 * @date        2017-05-02
 */

// no direct access
defined('_JEXEC') or die;

if (empty($displayData['footer']))
{
	return;
}

?>
<div class="wbamp-container wbamp-footer">
	<?php echo $displayData['footer']; ?>
</div>

<?php
/**
 * sh404SEF - SEO extension for Joomla!
 *
 * @author      Yannick Gaultier
 * @copyright   (c) Yannick Gaultier - Weeblr llc - 2016
 * @package     sh404SEF
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     4.8.2.3492
 * @date        2016-12-20
 */

/**
 * Input:
 *
 * $displayData['url']
 * $displayData['target']
 */
// Security check to ensure this file is being included by a parent file.
if (!defined('_JEXEC')) die('Direct Access to this location is not allowed.');
?>
<!-- Google sitelinks search markup-->
<script type="application/ld+json">
<?php echo ShlSystem_Convert::jsonEncode($displayData); ?>

</script>
<!-- End of Google sitelinks search markup-->

<?php
/**
 * wbAMP - Accelerated Mobile Pages for Joomla!
 *
 * @author      Yannick Gaultier
 * @copyright   (c) Yannick Gaultier - Weeblr llc - 2017
 * @package     wbAmp
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     1.8.0.647
 * @date		2017-05-02
 */

defined('_JEXEC') or die();

class WbampHelper_Edition
{
	public static $version = '1.8.0.647';
	public static $id = 'community';
	public static $name = 'Community Edition';
	public static $url = 'https://weeblr.com/joomla-accelerated-mobile-pages/wbamp';

	public static function is($edition)
	{
		return $edition == self::id;
	}
}

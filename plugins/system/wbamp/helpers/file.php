<?php
/**
 * wbAMP - Accelerated Mobile Pages for Joomla!
 *
 * @author       Yannick Gaultier
 * @copyright    (c) Yannick Gaultier - Weeblr llc - 2017
 * @package      wbAmp
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version      1.8.0.647
 * @date        2017-05-02
 */

defined('_JEXEC') or die();

class WbampHelper_File
{
	/**
	 * Include a file, if it exists and return its content
	 *
	 * @param String $fileName Full path to file
	 * @return String
	 */
	public static function getIncludedFile($fileName)
	{
		$includedFile = '';
		if (file_exists($fileName))
		{
			ob_start();
			include $fileName;
			$includedFile = ob_get_contents();
			ob_end_clean();
		}

		return $includedFile;
	}
}

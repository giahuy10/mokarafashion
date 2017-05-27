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
defined('_JEXEC') or die;

/**
 * Route helper
 *
 */
class WbampHelper_Modules
{
	/**
	 * Loads the raw HTML content of a Joomla module from DB
	 *
	 * @param $id
	 * @return mixed|string
	 */
	static public function getHtmlModuleContent($id)
	{
		$moduleContent = '';
		$id = (int) $id;
		if (!empty($id))
		{
			try
			{
				// load from DB
				$moduleContent = ShlDbHelper::selectResult('#__modules', array('content', 'params'), array('id' => $id, 'module' => 'mod_custom', 'published' => 1));
				$moduleContent = WbampHelper_Route::sef($moduleContent);
			}
			catch (Exception $e)
			{
				ShlSystem_Log::error('wbamp', __METHOD__ . ' ' . $e->getMessage());
			}
		}
		return $moduleContent;
	}
}

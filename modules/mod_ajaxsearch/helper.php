<?php

defined('_JEXEC') or die;

/**
 * Helper for mod_ajaxsearch
 */
class ModAjaxSearchHelper
{
	/**
	 * Find Itemid considering language priority
	 */
	public static function getItemid()
	{
		$lang	= JFactory::getLanguage();
		$lang_tag = $lang->getTag();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('id')->from('#__menu')->where('link LIKE '.$db->q('index.php?option=com_search&view=search%').' AND (language='.$db->q($lang_tag).' OR language='.$db->q('*').') AND published=1 ORDER BY language DESC');
		$db->setQuery($query);
		//die($query);
		$Itemid = $db->loadResult();

		return $Itemid;
	}
}

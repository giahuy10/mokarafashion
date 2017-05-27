<?php

defined('_JEXEC') or die;

require_once __DIR__ . '/helper.php';

$lang	= JFactory::getLanguage();
$app	= JFactory::getApplication();
$config = JFactory::getConfig();
$doc	= JFactory::getDocument();

if ($params->get('opensearch', 1))
{
	$ostitle = $params->get('opensearch_title', JText::_('MOD_AJAXSEARCH_SEARCHBUTTON_TEXT') . ' ' . $app->getCfg('sitename'));
	$doc->addHeadLink(
			JUri::getInstance()->toString(array('scheme', 'host', 'port'))
			. JRoute::_('&option=com_search&format=opensearch'), 'search', 'rel',
			array(
				'title' => htmlspecialchars($ostitle),
				'type' => 'application/opensearchdescription+xml'
			)
		);
}

$upper_limit		= $lang->getUpperLimitSearchWord();
$lower_limit		= $lang->getLowerLimitSearchWord();
$button				= $params->get('button', 0);
$button_text		= htmlspecialchars($params->get('button_text', JText::_('MOD_AJAXSEARCH_SEARCHBUTTON_TEXT')));
$width				= (int) $params->get('width');
$maxlength			= $upper_limit;
$text				= htmlspecialchars($params->get('text', JText::_('MOD_AJAXSEARCH_SEARCHBOX_TEXT')));
$label				= htmlspecialchars($params->get('label', JText::_('MOD_AJAXSEARCH_LABEL_TEXT')));
$max_results		= (int) $params->get('max_results', 5);
$moduleclass_sfx	= htmlspecialchars($params->get('moduleclass_sfx'));
$pagination_limit	= $config->get('list_limit');
$mitemid			= 0;
if ($params->get('find_itemid')) {
	$mitemid		= ModAjaxSearchHelper::getItemid();
}
$include_css		= (int) $params->get('include_css', 1);

require JModuleHelper::getLayoutPath('mod_ajaxsearch', $params->get('layout', 'default'));

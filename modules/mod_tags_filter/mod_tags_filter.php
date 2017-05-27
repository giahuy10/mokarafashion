<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_tags_popular
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the tags_popular functions only once
JLoader::register('ModTagsFilter', __DIR__ . '/helper.php');
$tag_root   = $params->get('tag_root');
$cacheparams = new stdClass;
$cacheparams->cachemode = 'safeuri';
$cacheparams->class = 'ModTagsFilter';
$cacheparams->method = 'getList';
$cacheparams->methodparams = $params;
$cacheparams->modeparams = array('id' => 'array', 'Itemid' => 'int');

$list = ModTagsFilter::getList($tag_root);

if (!count($list) && !$params->get('no_results_text'))
{
	return;
}

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');


require JModuleHelper::getLayoutPath('mod_tags_filter', $params->get('layout', 'default'));

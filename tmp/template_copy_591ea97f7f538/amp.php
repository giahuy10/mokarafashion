<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/** @var JDocumentHtml $this */

$app = JFactory::getApplication();

// Output as HTML5
$this->setHtml5(true);

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');

// Add html5 shiv
JHtml::_('script', 'jui/html5.js', array('version' => 'auto', 'relative' => true, 'conditional' => 'lt IE 9'));

// Add Stylesheets
JHtml::_('stylesheet', 'template.css', array('version' => 'auto', 'relative' => true));

// Load optional rtl Bootstrap css and Bootstrap bugfixes
JHtmlBootstrap::loadCss($includeMaincss = false, $this->direction);
?>
<!DOCTYPE html>
<html amp>
<head>
	<meta charset="utf-8">
	<script async src="https://cdn.ampproject.org/v0.js"></script>
	<link rel="canonical" href="<?php echo JUri::current();?>">
	<jdoc:include type="head" />
	<style amp-custom>
	  h1 {
		color: red;
	  }
	</style>
</head>
<body class="amp">
	
	<jdoc:include type="message" />
	<jdoc:include type="component" />
</body>
</html>

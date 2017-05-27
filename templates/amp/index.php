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

$app  = JFactory::getApplication();
$user = JFactory::getUser();

// Output as HTML5
$this->setHtml5(true);

// Getting params from template
$params = $app->getTemplate(true)->params;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->get('sitename');




	
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

<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '');

?>">
	<div class="ed-header" id="ed-header">
		<div class="container">
			<div class="row">
				
				<div class="col-xs-12 col-sm-4">
					<amp-img src="/images/logo-mokara-black.png"
					  width="425"
					  height="240"
					  layout="responsive"
					  alt="AMP"></amp-img>
				</div>
				<div class="col-xs-12 col-sm-4">
					<jdoc:include type="modules" name="banner-top" style="none" />
				</div>
			</div>	
		</div>	
	</div>
	<div class="ed-main-menu " id="ed-main-menu">
		<div class="container ed-black-bg">
			<div class="row">
				<div class="col-xs-12">
					<div class="ed-navigation">			
						<jdoc:include type="modules" name="main-menu" style="none" />
					</div>
				</div>
				
			</div>
			
			
		</div>	
	</div>
	<div class="ed-slider" id="ed-slider">
		<jdoc:include type="modules" name="slider" style="none" />
	</div>
	<div class="ed-main-body" id="ed-main-body">
		<div class="container">
			<div class="row">
				
				<div class="col-xs-12 <?php if ($this->countModules( 'right-sidebar' )){
					echo "col-sm-9";
				} ?>">
					<div class="ed-mass-top">
						<jdoc:include type="modules" name="mass-top" style="xhtml" />
					</div>
					<jdoc:include type="message" />
					<jdoc:include type="component" />
					<div class="row">
						<div class="col-xs-12 col-sm-6">
							<jdoc:include type="modules" name="mass-bottom-left" style="xhtml" />
						</div>
						<div class="col-xs-12 col-sm-6">
							<jdoc:include type="modules" name="mass-bottom-right" style="xhtml" />
						</div>
					</div>
					<div class="ed-mass-bottom">
						<jdoc:include type="modules" name="mass-bottom" style="xhtml" />
					</div>
				</div>
				<?php if ($this->countModules( 'right-sidebar' )) : ?>
					<div class="col-xs-12 col-sm-3">
						<jdoc:include type="modules" name="right-sidebar" style="xhtml" />
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<footer class="ed-footer" id="ed-footer">
		<div class="container">
			<div class="col-xs-12 col-sm-3">
				<jdoc:include type="modules" name="footer1" style="xhtml" />
			</div>
			<div class="col-xs-12 col-sm-1">
				<jdoc:include type="modules" name="footer3" style="xhtml" />
			</div>
			<div class="col-xs-12 col-sm-4">
				<jdoc:include type="modules" name="footer2" style="xhtml" />
			</div>
			<div class="col-xs-12 col-sm-3">
				<jdoc:include type="modules" name="footer4" style="xhtml" />
			</div>
			
			
		</div>
		
	</footer>
	<jdoc:include type="modules" name="debug" style="none" />
	
	
</body>
</html>

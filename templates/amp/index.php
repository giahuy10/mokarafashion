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
	<meta name="viewport" content="width=device-width,minimum-scale=1">
	<script async src="https://cdn.ampproject.org/v0.js"></script>
	<script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
	<script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
	<link rel="canonical" href="<?php echo JUri::current();?>">
	
	<style amp-custom>
	  body {
		  padding: 0 10px;
	  }
	  .header-icon {
    text-align: center;
}
a {
    color: #000;
    text-decoration: none;
}
  </style>
	<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>


</head>

<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '');

?>">
	<amp-sidebar id="sidebar"
						  layout="nodisplay"
						  side="right">						 
						<jdoc:include type="modules" name="main-menu" style="none" />
						</amp-sidebar>
	<div class="ed-header" id="ed-header">
		<div class="container">
			
				<div class="logo">
					<amp-img src="/images/logo-mokara-black.png"
					  width="1051"
					  height="262"
					  layout="responsive"
					  alt="AMP"></amp-img>
				</div>
				<div class="header-icon">
				<a href="/cart?view=orders">Giỏ hàng </a> | <a href="/log-in/">Đăng nhập</a> | 	<button on="tap:sidebar.toggle" class="ampstart-btn caps m2">Menu</button>
				</div>
		
		</div>	
	</div>
	<div class="ed-main-menu " id="ed-main-menu">
		<div class="container ed-black-bg">
		
					
			
				
			
			
			
		</div>	
	</div>
	<div class="ed-slider" id="ed-slider">
		<jdoc:include type="modules" name="slider-amp" style="none" />
	</div>
	<div class="ed-main-body" id="ed-main-body">
		<div class="container">
			<div class="row">
				
				<div class="col-xs-12 <?php if ($this->countModules( 'right-sidebar' )){
					echo "col-sm-9";
				} ?>">
					<div class="ed-mass-top">
						<jdoc:include type="modules" name="mass-top-amp" style="xhtml" />
					</div>
					<jdoc:include type="message" />
					<jdoc:include type="component" />
					<div class="row">
						<div class="col-xs-12 col-sm-6">
							<jdoc:include type="modules" name="mass-bottom-left-amp" style="xhtml" />
						</div>
						<div class="col-xs-12 col-sm-6">
							<jdoc:include type="modules" name="mass-bottom-right-amp" style="xhtml" />
						</div>
					</div>
					<div class="ed-mass-bottom">
						<jdoc:include type="modules" name="mass-bottom-amp" style="xhtml" />
					</div>
				</div>
				<?php if ($this->countModules( 'right-sidebar' )) : ?>
					<div class="col-xs-12 col-sm-3">
						<jdoc:include type="modules" name="right-sidebar-amp" style="xhtml" />
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<footer class="ed-footer" id="ed-footer">
		<div class="container">
			<div class="col-xs-12 col-sm-3">
				<amp-img src="/images/logo-mokara-black.png"
					  width="1051"
					  height="262"
					  layout="responsive"
					  alt="AMP"></amp-img>
					  <div class="custom remove-p">

<p>Thời trang công sở cao cấp Mokara</p>
<p>Trụ sở: 10/51 Tôn Thất Tùng - Đống Đa</p>
<p>Hotline: 04 3710 1368 - 093 626 5775</p>
<p>Email: info@mokara.com.vn</p></div>
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

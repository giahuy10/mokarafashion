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
$template   = $app->input->getCmd('template', '');
if ($option=="com_users" || $option=="com_mokara" && $template!="mokara") {
	header('Location: '.JUri::getInstance().'&template=mokara');
	}
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->get('sitename');



$doc = JFactory::getDocument();
unset($doc->base);
$dontInclude = array(
'/media/jui/js/jquery.min.js',
'/media/system/js/caption.js',
'/media/system/js/html5fallback.js',
'/media/jui/js/jquery-migrate.min.js',
'/media/jui/js/jquery-noconflict.js',
'/media/system/js/core-uncompressed.js',
'/media/system/js/tabs-state.js',
'/media/system/js/core.js',
'/media/system/js/mootools-core.js',
'/media/jui/js/bootstrap.min.js',
'/media/system/js/multiselect.js',
'/media/jui/js/chosen.jquery.min.js'
);

foreach($doc->_scripts as $key => $script){
    if(in_array($key, $dontInclude)){
        unset($doc->_scripts[$key]);
    }
}
unset($this->_styleSheets[JURI::root(true).'/media/jui/css/chosen.css']);	
?>
<!DOCTYPE html>

<html amp>
<head>

		<jdoc:include type="head" />
	<meta name="viewport" content="width=device-width,minimum-scale=1">
	<script async src="https://cdn.ampproject.org/v0.js"></script>
	<script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
	<script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
	<script async custom-element="amp-image-lightbox" src="https://cdn.ampproject.org/v0/amp-image-lightbox-0.1.js"></script>
	<script async custom-element="amp-social-share" src="https://cdn.ampproject.org/v0/amp-social-share-0.1.js"></script>
	<script async custom-element="amp-analytics"src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
	<meta http-equiv="origin-trial" data-feature="Web Share" data-expires="2017-04-04" content="Ajcrk411RcpUCQ3ovgC8le4e7Te/1kARZsW5Hd/OCnW6vIHTs5Kcq1PaABs7SzcrtfvT0TIlFh9Vdb5xWi9LiQsAAABSeyJvcmlnaW4iOiJodHRwczovL2FtcGJ5ZXhhbXBsZS5jb206NDQzIiwiZmVhdHVyZSI6IldlYlNoYXJlIiwiZXhwaXJ5IjoxNDkxMzM3MDEwfQ==">
	
	<link rel="canonical" href="<?php echo JUri::current();?>">
	

	<style amp-custom>
	.btn {
    background: #000;
}
	  body {
		
		  font-family: arial;
		      padding-top: 65px;

	  }
	  .header-icon {
    text-align: center;
}
a {
    color: #000;
    text-decoration: none;
}
amp-sidebar#sidebar {
    background: #000;
}
amp-sidebar#sidebar ul {
    list-style: none;
    padding: 0;
}
amp-sidebar#sidebar ul li {
    padding: 5px 40px 5px 20px;
    background: #000;
    border-bottom: 1px solid #fff;
}
amp-sidebar#sidebar ul li a {
    color: #fff;
}
amp-sidebar#sidebar ul.nav-child li {
    border-bottom: 0;
  
}
.hidden {
    display: none;
}
.moduletable.home-module h3 {
    text-align: center;
}
.ed-inner-product {
    border: 1px solid #ccc;
    margin-bottom: 20px;
    padding: 10px;
    border-radius: 5px;
  
}
.pull-right {
    float: right;
}
.pull-left {
    float: left;
}
.clearfix {
    clear: both;
    
}
h2 {
    margin: 10px 0 5px;
}
.btn {
   
    border: 0;
	display: inline-block;
    padding: 6px 12px;
    color: #fff;
    border-radius: 5px;
}
.btn-buy {
    background: #a20000;
    color: #fff;
}
.add2cart-btn {
    background: #252525;
    border-color: #676565;
    color: #fff;
}
footer#ed-footer {
    background: #000;
    color: #fff;
    margin-top: 40px;
    padding-bottom: 20px;
    position: relative;
}
.moduletable > h3 {
    margin-top: 30px;
}
footer#ed-footer a {
    color: #fff;
   
    padding: 5px 10px;
}
.container {
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}
.thumb_img {
    width: 25%;
    float: left;
    padding: 5px;
    box-sizing: border-box;
}
.btn-warning {
    color: #fff;
    background-color: #f0ad4e;
    border-color: #eea236;
}
.btn-success {
    color: #fff;
    background-color: #5cb85c;
    border-color: #4cae4c;
}
.bottom-10 {
    margin-bottom: 10px;
}
div#ed-header .logo {
    position: fixed;
    background: black;
    width: 100%;
	top:0;
	    z-index: 9999;
}
button.btn-menu {
    color: #000;
    margin-top: 13px;
    background: #fff;
}
.btn-danger {
    color: #fff;
    background-color: #d9534f;
    border-color: #d43f3a;
}
select.filter-select {
    width: 100%;
    padding: 5px 10px;
    margin-bottom: 5px;
    border-radius: 5px;
}
input.quantity-box {
    width: 35px;
    padding: 10px 0;
    text-align: center;
}
ul.pagination li {
    display: inline-block;
    border: 1px solid #000;
    padding: 2px 5px;
    margin-right: 1px;
}
ul.pagination li.active {
    background: #000;
}
ul.pagination li.active a {
    color: #fff;
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
<amp-analytics type="googleanalytics">
  <script type="application/json">
  {
    "vars": {
      "account": "UA-100349674-1"
    },
    "triggers": {
      "trackPageview": {
        "on": "visible",
        "request": "pageview"
      },
      "trackEvent": {
        "selector": "#event-test",
        "on": "click",
        "request": "event",
        "vars": {
          "eventCategory": "ui-components",
          "eventAction": "click"
        }
      }
    }
  }
  </script>
  </amp-analytics>
	<amp-sidebar id="sidebar"
						  layout="nodisplay"
						  side="right">						 
						<jdoc:include type="modules" name="main-menu" style="none" />
						</amp-sidebar>
	<div class="ed-header" id="ed-header">
		
			
				<div class="logo">
					<div class="container">
					<a href="/" class="pull-left">
						<amp-img src="images/logo-mokara-white.png"
						  width="200"
						  height="50"
						
						  alt="AMP"></amp-img>
					</a>
					<button on="tap:sidebar.toggle" class="ampstart-btn caps m2 btn btn-menu pull-right">Menu</button>		
					</div>
					<div class="clearfix"></div>					
				</div>
				
				<div class="container">
					<div class="header-icon">
					<a href="index.php?option=com_mokara&view=orders&Itemid=502">Giỏ hàng </a> | <a href="index.php?option=com_users&view=login&Itemid=322">Đăng nhập</a> 	
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
	<div class="container">
	<amp-social-share type="email"></amp-social-share>
  <amp-social-share type="facebook"
    data-param-app_id="1907423642863145"></amp-social-share>
  <amp-social-share type="gplus"></amp-social-share>
  <amp-social-share type="linkedin"></amp-social-share>
  <amp-social-share type="pinterest"></amp-social-share>
  <amp-social-share type="tumblr"></amp-social-share>
  <amp-social-share type="twitter"></amp-social-share>
  <amp-social-share type="whatsapp"></amp-social-share>
  </div>
	<footer class="ed-footer" id="ed-footer">
		<div class="container">
			<div class="col-xs-12 col-sm-3">
				<amp-img src="images/logo-mokara-white.png"
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

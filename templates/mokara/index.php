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
$doc = JFactory::getDocument();

$dontInclude = array(

'/media/system/js/caption.js',
'/media/system/js/html5fallback.js',
'/media/jui/js/jquery-migrate.min.js',
'/media/jui/js/jquery-noconflict.js',
'/media/system/js/core-uncompressed.js',
'/media/system/js/tabs-state.js',
'/media/system/js/core.js',
'/media/system/js/mootools-core.js',
'/media/jui/js/bootstrap.min.js',
'/media/jui/js/bootstrap.js',
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
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<jdoc:include type="head" />
	<link rel="amphtml" href="<?php echo JUri::current();?>?template=amp" />
	<link href="<?php echo $this->baseurl . '/templates/' . $this->template . '/css/font-awesome.min.css'?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo $this->baseurl . '/templates/' . $this->template . '/css/bootstrap.min.css'?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo $this->baseurl . '/templates/' . $this->template . '/css/user.css'?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo $this->baseurl . '/templates/' . $this->template . '/css/ed-mod_article.css'?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo $this->baseurl . '/templates/' . $this->template . '/css/ed-navigation.css'?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo $this->baseurl . '/templates/' . $this->template . '/css/ed-productdetail.css'?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo $this->baseurl . '/templates/' . $this->template . '/css/ed-productblog.css'?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo $this->baseurl . '/templates/' . $this->template . '/css/ed-footer.css'?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo $this->baseurl . '/templates/' . $this->template . '/css/ed-filter.css'?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo $this->baseurl . '/templates/' . $this->template . '/css/ed-responsive.css'?>" rel="stylesheet" type="text/css" />
	<script src="<?php echo $this->baseurl . '/templates/' . $this->template . '/js/bootstrap.min.js'?>"></script>
	
	
	<meta property="fb:app_id" content="1907423642863145" />
	<meta property="fb:admins" content="100002787759779"/>

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
				<div class="col-xs-12 col-sm-4 hidden-xs">
					<div class="ed-search">		
						<jdoc:include type="modules" name="banner-top-left" style="none" />
					</div>
				</div>
				<div class="col-xs-12 col-sm-4 logo-block">
					<jdoc:include type="modules" name="logo" style="none" />
					
				</div>
				<div class="col-xs-12 col-sm-4">
					<button class="mobile-menu btn btn-primary visible-xs pull-left">Menu</button>
					<jdoc:include type="modules" name="banner-top" style="none" />
				</div>
			</div>	
		</div>	
	</div>
	<div class="ed-main-menu " id="ed-main-menu">
		<div class="container ed-black-bg">
			<div class="row">
				<div class="col-xs-12">
					
					<div class="ed-navigation hidden-xs">			
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
				<?php if ($this->countModules( 'left-sidebar' )) : ?>
					<div class="col-xs-12 col-sm-3">
						<jdoc:include type="modules" name="left-sidebar" style="xhtml" />
					</div>
				<?php endif; ?>
				<div class="col-xs-12 <?php if ($this->countModules( 'right-sidebar' ) || $this->countModules( 'left-sidebar' )){
					echo "col-sm-9";
				} ?>">
				<?php if ($this->countModules( 'mass-top' )) : ?>
					<div class="ed-mass-top">
						<jdoc:include type="modules" name="mass-top" style="xhtml" />
					</div>
				<?php endif; ?>	
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
	<p class="pull-right">
				<a href="#top" id="back-top" title="Trở lên trên">
					<i class="fa fa-hand-o-up" aria-hidden="true"></i>
				</a>
			</p>	
	</footer>
	


	<jdoc:include type="modules" name="debug" style="none" />
	<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.9&appId=1907423642863145";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


  <script>
  jQuery(document).ready(function () {
  jQuery(".chat_fb").click(function() {
jQuery('.fchat').toggle('slow');
  });
  });
  </script>
<div id="cfacebook">
  <a href="javascript:;" class="chat_fb" onclick="return:false;"><i class="fa fa-facebook-square"></i> Hỗ trợ trực tuyến</a>
  <div class="fchat">
  <div class="fb-page" data-tabs="messages" data-href="https://www.facebook.com/Mokara-Fashion-1357088934371320/" data-width="250" data-height="400" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"></div>
  </div>
  </div>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-100364839-1', 'auto');
  ga('send', 'pageview');

</script>
<script>
jQuery(function($) {
    $("button").click(function(){
        $(".ed-navigation").toggleClass('hidden-xs');
    });
});
</script>
<!-- Google Code dành cho Thẻ tiếp thị lại -->
<!--------------------------------------------------
Không thể liên kết thẻ tiếp thị lại với thông tin nhận dạng cá nhân hay đặt thẻ tiếp thị lại trên các trang có liên quan đến danh mục nhạy cảm. Xem thêm thông tin và hướng dẫn về cách thiết lập thẻ trên: http://google.com/ads/remarketingsetup
--------------------------------------------------->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 853498944;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/853498944/?guid=ON&amp;script=0"/>
</div>
</noscript>

</body>
</html>

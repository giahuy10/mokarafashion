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
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_mokara/models', 'MokaraModel');
$productMod = JModelLegacy::getInstance('Product', 'MokaraModel', array('ignore_request' => true));

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
$item   = $app->input->getCmd('id', '');
$sitename = $app->get('sitename');
$doc = JFactory::getDocument();
$session = JFactory::getSession();
if (!$session->get('ref')) {
	$session->set('ref', $_SERVER["HTTP_REFERER"]);
}
if (!$session->get($option.'-'.$view.'-'.$item.'-'.$_SERVER['REMOTE_ADDR'])) {
	$session->set($option.'-'.$view.'-'.$item.'-'.$_SERVER['REMOTE_ADDR'], 1);
	//$productMod->save_user_log ($user->id , $_SERVER['REMOTE_ADDR'], $option, $view, $layout, $task, $item, $session->get('ref'));
}

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
	

	<jdoc:include type="head" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
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

<body class="site user-<?php echo $user->id;?> <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '');

?>">

	<div class="ed-header" id="ed-header">
		<div class="container">
			<div class="row">
				
				<div class="col-xs-12 col-sm-4 logo-block">
					<jdoc:include type="modules" name="logo" style="none" />
					
				</div>
				<div class="col-xs-12 col-sm-8">
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
<script type="text/javascript">
var LHCChatOptions = {};
LHCChatOptions.opt = {widget_height:340,widget_width:300,popup_height:520,popup_width:500,domain:'<?php echo $_SERVER['SERVER_NAME']?>'};
<?php if ($user->id) {?>
LHCChatOptions.attr_online = new Array();
LHCChatOptions.attr_online.push({'name':'email','value':'<?php echo $user->email?>'});
LHCChatOptions.attr_online.push({'name':'phone','value':'<?php echo $user->phone?>'});
LHCChatOptions.attr_online.push({'name':'username','value':'<?php echo $user->name?>'});
<?php }?>
(function() {
var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
var referrer = (document.referrer) ? encodeURIComponent(document.referrer.substr(document.referrer.indexOf('://')+1)) : '';
var location  = (document.location) ? encodeURIComponent(window.location.href.substring(window.location.protocol.length)) : '';
po.src = '//<?php echo $_SERVER['SERVER_NAME']?>/livechat/index.php/vnm/chat/getstatus/(click)/internal/(position)/bottom_right/(ma)/br/(check_operator_messages)/true/(top)/350/(units)/pixels/(leaveamessage)/true/(department)/1?r='+referrer+'&l='+location;
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
</script>
<script type="text/javascript">
    var startTime = new Date();        //Start the clock!
    window.onbeforeunload = function()        //When the user leaves the page(closes the window/tab, clicks a link)...
    {
        var endTime = new Date();        //Get the current time.
        var timeSpent = (endTime - startTime);        //Find out how long it's been.
        var xmlhttp;        //Make a variable for a new ajax request.
        if (window.XMLHttpRequest)        //If it's a decent browser...
        {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();        //Open a new ajax request.
        }
        else        //If it's a bad browser...
        {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");        //Open a different type of ajax call.
        }
		//$productMod->save_user_log ($user->id , $_SERVER['REMOTE_ADDR'], $option, $view, $layout, $task, $item, $session->get('ref'));
        var url = "//<?php echo $_SERVER['SERVER_NAME']?>/cartfunction.php?time="+timeSpent+"&option=<?php echo $option?>&view=<?php echo $view?>&layout=<?php echo $layout?>&task=<?php echo $task?>&item=<?php echo $item?>&ref=<?php echo $session->get('ref')?>&user_id=<?php echo $user->id?>";        //Send the time on the page to a php script of your choosing.
        xmlhttp.open("GET",url,false);        //The false at the end tells ajax to use a synchronous call which wont be severed by the user leaving.
        xmlhttp.send(null);        //Send the request and don't wait for a response.
    }
</script>
</body>
</html>

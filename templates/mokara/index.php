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
/*
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
*/
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

	<link href="https://fonts.googleapis.com/css?family=Athiti" rel="stylesheet">
<style type="text/css">
    .table>tbody>tr>td, .table>tfoot>tr>td{
    vertical-align: middle;
}
@media screen and (max-width: 600px) {
    table#cart tbody td .form-control{
		width:20%;
		display: inline !important;
	}
	.actions .btn{
		width:36%;
		margin:1.5em 0;
	}
	
	.actions .btn-info{
		float:left;
	}
	.actions .btn-danger{
		float:right;
	}
	
	table#cart thead { display: none; }
	table#cart tbody td { display: block; padding: .6rem; min-width:320px;}
	table#cart tbody tr td:first-child { background: #333; color: #fff; }
	table#cart tbody td:before {
		content: attr(data-th); font-weight: bold;
		display: inline-block; width: 8rem;
	}
	
	
	
	table#cart tfoot td{display:block; }
	table#cart tfoot td .btn{display:block;}
	
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
					<div class="ed-search">		
						<jdoc:include type="modules" name="banner-top-left" style="none" />
					</div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<jdoc:include type="modules" name="logo" style="none" />
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

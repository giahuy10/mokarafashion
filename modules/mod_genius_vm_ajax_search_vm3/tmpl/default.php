<?php
// Licensed under the GPL v2&
/**
* @package		mod_genius_vm_ajax_search_vm3.zip
* @copyright  (C) 2015 Mikkel Olsen / Genius WebDesign, https://www.genius-webdesign.com/
* @license		see docs/LICENSE.txt
*
* Joomla 2.5+ Module
*/


// no direct access
defined('_JEXEC') or die('Restricted access');
/* Load the virtuemart main parse code */

		
		define('VM1', false); 
		define('VM2', true); 
		
		



 // load virtuemart language files
 $jlang =JFactory::getLanguage();
 $jlang->load('com_virtuemart', JPATH_SITE, $jlang->getDefault(), true);
 $jlang->load('com_virtuemart', JPATH_SITE, null, true);


jimport( 'joomla.html.parameter' );

$appmenuget = JFactory::getApplication();
$menuitemID = $appmenuget->getMenu()->getActive()->id;


$documentcheck = JFactory::getDocument();
$stylesheetscheck = array_keys($documentcheck->_styleSheets);

$scriptFound = false;
for ($i = 0; $i < count($stylesheetscheck); $i++) {
    if (stripos($stylesheetscheck[$i], 'font-awesome.min.css') !== false) {
        $scriptFound = true;
    }
}
if (!$scriptFound) {
$documentcheck->addStyleSheet('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
}



if (!function_exists('hex2rgba')) {
function hex2rgba($color, $opacity = false) {

	$default = 'rgb(0,0,0)';

	//Return default if no color provided
	if(empty($color))
          return $default; 

	//Sanitize $color if "#" is provided 
        if ($color[0] == '#' ) {
        	$color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if($opacity){
        	if(abs($opacity) > 1)
        		$opacity = 1.0;
        	$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
        	$output = 'rgb('.implode(",",$rgb).')';
        }

        //Return rgb(a) color string
        return $output;
}
}

$lang = JFactory::getLanguage();
$extension = 'com_search';
$base_dir = JPATH_SITE;
if (VM1)
$language_tag = $lang->_lang;
else $language_tag = $lang->getTag(); 

$lang->load($extension, $base_dir, $language_tag, true);
$myid = $module->id;



$clang = JRequest::getVar('lang', ''); 
//var_dump($_SESSION);die();
$conf = JFactory::getConfig();
$l = $conf->get('language');


$a = explode('-', $l); 

if (!empty($a[0]))
$clang = $a[0];
else $clang = '';


$languagetest = JFactory::getLanguage();
$clangcode2 = $languagetest->getTag();




$q = 'select params from #__modules where id = "'.$myid.'" '; 
$db =& JFactory::getDBO();
$db->setQuery($q); 
$res = $db->loadResult(); 

if (!empty($res))

$params = new JRegistry();
$params->loadString($res);

$min_height = $params->get('min_height');
$results_width = $params->get('results_width');
$placeholdertxt = $params->get('search_input_placeholder');

$margin_setting = $params->get('manualmargin_setting');

$special_scroll_bars = $params->get('special_scroll_bars');

$overlay_bg_color_enable = $params->get('overlay_bg_color_enable');
$overlay_bg_color = $params->get('overlay_bg_color');
$overlay_bg_color_opacity = $params->get('overlay_bg_color_opacity');


$initial_ajax_loader_enable = $params->get('initial_ajax_loader_enable');
$initial_ajax_loader_bg = $params->get('initial_ajax_loader_bg');
$initial_ajax_loader_width = $params->get('initial_ajax_loader_width');
				

$scrollbar_enable = $params->get('special_scroll_bars');
$special_scroll_bars_type = $params->get('special_scroll_bars_type');
$scrollsmoothe = $params->get('special_scroll_bars_smoothness');

$custom_module_default_width = $params->get('custom_module_default_width');
$prresultsspecialcalc = $custom_module_default_width + 20;

if ($scrollbar_enable == 1) {
$scrollbartouse = $special_scroll_bars_type;
} else {
$scrollbartouse = 'none';
}


if ($overlay_bg_color_enable == 1) {
$rgba_bg_color = hex2rgba($overlay_bg_color, $overlay_bg_color_opacity);
$bgoverlaystyling = 'background:'.$rgba_bg_color.';';
} else {
$bgoverlaystyling = '';
}


if (empty($min_height)) $min_height = '40'; 
if (empty($results_width)) $results_width = '200px';
else $results_width .= 'px'; 

$prods = $params->get('number_of_products'); 
// we start with zero
$prods--; 
if (empty($prods)) $prods = 4; 


$url = JURI::base().'modules/mod_genius_vm_ajax_search_vm3/ajax/index.php';
$urlnew = JURI::base().'index.php?option=com_ajax&module=genius_vm_ajax_search_vm3&method=getGeniusVMSearchResults&format=raw&Itemid='.$menuitemID;


$jversionchk = new JVersion();
$thisistheJversion = $jversionchk->getShortVersion();

if ($thisistheJversion[0] == '3') {
$url = $urlnew;
}
else {
$url = $url;
}


$document =& JFactory::getDocument();
if (!defined('search_timer')){
	 // init only once per all modules
	 // $document->addStyleSheet(JURI::base().'modules/mod_genius_vm_ajax_search/css/mod_vm_ajax_search.css'); 
	 // $document->addScript(JURI::Base().'modules/mod_genius_vm_ajax_search/js/vmajaxsearch.js'); 
	$js1 = ' 
	<script type="text/javascript">
          var search_timer = new Array(); 
		  var search_has_focus = new Array(); 
		  var op_active_el = null;
		  var op_active_row = null;
          var op_active_row_n = parseInt("0");
		  var op_last_request = ""; 
          var op_process_cmd = "href"; 
		  var op_controller = ""; 
		  var op_lastquery = "";
		  var op_maxrows = '.$prods.'; 
		  var op_lastinputid = "vm_ajax_search_search_str2'.$myid.'";
		  var op_currentlang = "'.$clangcode2.'";
		  var op_lastmyid = "'.$myid.'"; 
		  var op_ajaxurl = "'.$url.'";
		  var op_savedtext = new Array(); 
	</script>
	<link rel="stylesheet" type="text/css" href="'.JURI::base().'modules/mod_genius_vm_ajax_search_vm3/css/genius_vm_ajax_search_vm3.css" media="all" />
	<script type="text/javascript" src="'.JURI::Base().'modules/mod_genius_vm_ajax_search_vm3/js/vmajaxsearch.js"></script>
 '; 
}
else $js1 = ''; 




$countcatrow = $params->get('showcats_info') + $params->get('showmanuf_info');
$counttotalrow = $countcatrow + $params->get('shownews_info');

$masterdivclass = '';
if ($countcatrow == 1 && $params->get('shownews_info') == 0) {
$masterdivclass = 'onlyoneGeniuscat';
}  
if ($counttotalrow == 0) {
$masterdivclass = 'onlyGeniusproductsmusshow';
} 
if ($countcatrow == 0 && $params->get('shownews_info') == 1) {
$masterdivclass = 'onlyNewsInGeniuscat';
}
if ($countcatrow == 2 && $params->get('shownews_info') == 0) {
$masterdivclass = 'noNewsInGeniuscat';
}
if ($countcatrow == 1 && $params->get('shownews_info') == 1) {
$masterdivclass = 'OneNewsAndOneCatInGeniuscat';
}

$masterdivclassModuleshow = '';
if ($params->get('include_custom_pos') == 0) {
$masterdivclassModuleshow = 'noModuleItem';
}


$js = $js1.'
	<script type="text/javascript">
  /* <![CDATA[ */
  // global variable for js
  
   
   search_timer['.$myid.'] = null; 
   search_has_focus['.$myid.'] = false; 
   
   jQuery(document).ready(function() {
     //jQuery(document).keydown(function(event) { handleArrowKeys(event); }); 
     // document.onkeypress = function(e) { handleArrowKeys(e); };
     if (document.body != null)
	 {
	   var div = document.createElement(\'div\'); 
	   div.setAttribute(\'id\', "vm_ajax_search_results2'.$myid.'"); 
	   div.setAttribute(\'class\', "res_a_s geniusGroove '.$masterdivclass.' '.$masterdivclassModuleshow.'"); 
	   div.setAttribute(\'style\', "'.$results_width.';");
	   document.body.appendChild(div);
	   
	   var div2bg = document.createElement(\'div\'); 
	   div2bg.setAttribute(\'id\', "Genius_vm_ajax_search_BG");
	   div2bg.setAttribute(\'style\', "'.$bgoverlaystyling .'");
	   div2bg.setAttribute(\'onclick\', "closethedamnajax('.$myid.');");
	   div2bg.setAttribute(\'class\', "geniusbgol'.$myid.'");
	   document.body.appendChild(div2bg);
	 }
     //document.body.innerHTML += \'<div class="searchwrapper"><div class="res_a_s" id="vm_ajax_search_results2'.$myid.'" style="z-index: 9990; width: '.$results_width.';">&nbsp;</div></div>\';
   });
   /* ]]> */
   
   </script>
  '; 
$style = '<style type="text/css">
 #vm_ajax_search_results2'.$myid.' {margin-left:'.$margin_setting.'px;margin-top:'.$params->get('manualmargin_settingtop').'px;} 
 .res_a_s.geniusGroove {
  border: none!important;
}  

#vm_ajax_search_results2'.$myid.'.res_a_s.geniusGroove {
width: '.$params->get('master_ajax_window_width').'px!important;
}

#vm_ajax_search_results2'.$myid.' .GeniusCustomModuleDivWrapper {
  width: '.$custom_module_default_width.'px;
}
#vm_ajax_search_results2'.$myid.' .GeniusProductsMasterWrapperprblock .GeniusProductsMasterWrapper .innerGeniusDivResults {
  width: calc(100% - '.$prresultsspecialcalc.'px);
}

</style>';


if ($special_scroll_bars == 1) {
$js .= '<link rel="stylesheet" type="text/css" href="'.JURI::base().'modules/mod_genius_vm_ajax_search_vm3/js/jquery.mCustomScrollbar.css" media="all" />';
$js .= '<script type="text/javascript" src="'.JURI::Base().'modules/mod_genius_vm_ajax_search_vm3/js/jquery.mCustomScrollbar.concat.min.js"></script>';
}



$js .= '<script type="text/javascript">
    function doXSubmit(f, t_id)
    {
        valx = jQuery("#vm_ajax_search_search_str2"+t_id).val();
        //console.log("'.JRoute::_('index.php?option=com_search').'?searchword="+valx+"&ordering=alpha&searchphrase=any&limit=1000&areas[0]=virtuemart");
        //document.location = "'.JRoute::_('index.php?option=com_search').'?searchword="+valx+"&ordering=alpha&searchphrase=any&limit=1000&areas[0]=virtuemart";
        console.log("#");
        document.location = "#";

    }



function closethedamnajax() {
jQuery( ".GeniusAjaxModuleWrap a.GeniusCloseLinkModalPop" ).trigger( "click" );
jQuery("#Genius_vm_ajax_search_BG").hide();
}

jQuery( window ).resize(function() {
var eTop'.$myid.' = jQuery("#vm_ajax_search_search_str2'.$myid.'").offset().top;
var eTopCalc'.$myid.' = eTop'.$myid.' + 40;
var eLeft'.$myid.' = jQuery("#vm_ajax_search_search_str2'.$myid.'").offset().left;

jQuery("#vm_ajax_search_results2'.$myid.'").css({"top" : eTopCalc'.$myid.' + "px", "left" : eLeft'.$myid.' + "px"});

var ajaxwindowwidth'.$myid.' = jQuery( "#vm_ajax_search_results2'.$myid.'" ).width();
var windowwidth = jQuery( window ).width();
if (ajaxwindowwidth'.$myid.' >= windowwidth) {
jQuery("#vm_ajax_search_results2'.$myid.'").addClass( "ajaxframeismobile" );
} else {
jQuery("#vm_ajax_search_results2'.$myid.'").removeClass( "ajaxframeismobile" );
}
});



jQuery( document ).ready(function() {
var ajaxwindowwidth'.$myid.' = jQuery( "#vm_ajax_search_results2'.$myid.'" ).width();
var windowwidth = jQuery( window ).width();
if (ajaxwindowwidth'.$myid.' >= windowwidth) {
jQuery("#vm_ajax_search_results2'.$myid.'").addClass( "ajaxframeismobile" );
} else {
jQuery("#vm_ajax_search_results2'.$myid.'").removeClass( "ajaxframeismobile" );
}
});


</script>';

$documentScripts = JFactory::getDocument();
$documentScripts->addCustomTag($style.$js);

?>

<div class="GeniusAjaxInputMaster templatestyle<?php echo $params->get('template'); ?>">

<form name="pp_search<?php echo $myid ?>" id="pp_search2.<?php echo $myid ?>" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=category&search=true&limitstart=0&virtuemart_category_id=0' ); ?>" method="get">
<?php
		$search = JText::_('COM_VIRTUEMART_SEARCH');
		// can set this also to: JText::_('SEARCH');
		
		$search = addslashes($search);
		$include_but = $params->get('include_but');
		$tw = $params->get('text_box_width'); 
?>
<div class="afterspecialdiv">
<input placeholder="<?php echo JText::_($placeholdertxt); ?>" class="inputbox" maxlength="30" size="30" id="vm_ajax_search_search_str2<?php echo $myid ?>" name="keyword" type="text" value="" autocomplete="off" onfocus="javascript:search_vm_ajax_live(this, '<?php echo $params->get('number_of_products'); ?>', '<?php echo $clangcode2; ?>', '<?php echo $myid; ?>', '<?php echo $url ?>', '<?php echo $scrollbartouse ?>', '<?php echo $scrollsmoothe ?>', '<?php echo $initial_ajax_loader_enable ?>', '<?php echo $initial_ajax_loader_bg ?>', '<?php echo $initial_ajax_loader_width ?>');" onkeyup="javascript:search_vm_ajax_live(this, '<?php echo $params->get('number_of_products'); ?>', '<?php echo $clangcode2; ?>', '<?php echo $myid; ?>', '<?php echo $url ?>', '<?php echo $scrollbartouse ?>', '<?php echo $scrollsmoothe ?>', '<?php echo $initial_ajax_loader_enable ?>', '<?php echo $initial_ajax_loader_bg ?>', '<?php echo $initial_ajax_loader_width ?>');"/>
<input class="genius-search-submit" type="submit" value="">
<div class="searchabsolutegeniusclick"></div>
<div class="searchabsolutegeniusclick coverbggeniussrch"><span class="fa fa-search"></span></div>
</div>

<input type="hidden" id="saved_vm_ajax_search_search_str2<?php echo $myid ?>" value="<?php echo $search; ?>" />

<input type="hidden" name="option" value="com_virtuemart" />
<input type="hidden" name="page" value="shop.browse" />
<input type="hidden" name="search" value="true" />
<input type="hidden" name="view" value="category" />
<input type="hidden" name="limitstart" value="0" />
		
</form>
</div>
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

$baseFTPaddress = JPATH_BASE;

$baseJoomlaURL = JURI::root();
$baseJoomlaURL = str_replace('/modules/mod_genius_vm_ajax_search_vm3/ajax', '', $baseJoomlaURL);

    if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
    require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
    require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
    require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'factory.php' );
    
$mainframe = JFactory::getApplication('site');
$mainframe->initialise();

       

function fact($a){
if ($a==0) return 1;
else return $fact = $a * fact($a-1);
}

$_GLOBALS['mosConfig_absolute_path'] = JPATH_BASE;

require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');
VmConfig::loadConfig(true);
if (!defined('VMLANG')) define('VMLANG', 'en_gb');

$_REQUEST['option'] = 'com_virtuemart';

$db = JFactory::getDBO();

$myid = JRequest::getInt('myid', 0);
$prods = JRequest::getInt('prods', 1);

$cache_off = true;

$extension = 'com_virtuemart';
$base_dir = JPATH_SITE;



$query = $db->getQuery(true);
$query->select('m.*');
$query->from('#__modules AS m');
$query->where('id = '.$myid);
$db->setQuery($query);
$module = $db->loadObject();
$params = new JRegistry($module->params);

$templateofchoice = $params->get('template');
$SearchStringinfotxt = $params->get('search_string_info');
$ModalCloseTXT = $params->get('modal_close_txt');
$CategoryheaderMd = $params->get('category_header_txt');
$ManufheaderMd = $params->get('manuf_header_txt');
$ProductheaderMd = $params->get('product_header_txt');
$NewsheaderMd = $params->get('news_header_txt');
$price_type_display = $params->get('price_type_display');
$custom_manual_lang_tag = $params->get('manual_lang_code');



//Initiate language
$lang_code = JRequest::getVar('lang', '');

$langthisgettings = JLanguageHelper::getLanguages('lang_code');
$lang_code_chkings = JFactory::getLanguage()->getTag();
$CurrentSEFcode = $langthisgettings[$lang_code_chkings]->sef;
$CurrentSEFcode2 = $langthisgettings[$lang_code]->sef;

$fixsefURLtoreplacements = JURI::base( true ).'/'.$CurrentSEFcode;
$fixsefURLtoreplacements2 = JURI::base( true ).'/'.$CurrentSEFcode2;



$langcheckfinalInit = JFactory::getLanguage();
$langcheckfinalInit->setLanguage( $lang_code );
$langcheckfinalInit->load();

$ARTSlangcodeforQuery = str_replace("_","-",$lang_code);
$ARTSlangcodeforQuery = strtoupper ( $ARTSlangcodeforQuery  );
$ARTSlangcodeforQuery = strtolower(substr($ARTSlangcodeforQuery, 0, 2)) . substr($ARTSlangcodeforQuery, 2);


if(strlen($custom_manual_lang_tag) > 0) {
$lang_code = $custom_manual_lang_tag;
}
$dblangcode = str_replace("-","_",$lang_code);
$dblangcode = strtolower ( $dblangcode );




$inlclude_child_products_results = $params->get('inlclude_child_products_results');
if($inlclude_child_products_results == 1) {
$specialnonchildqry = " AND p.product_parent_id = '0' ";
}
else {
$specialnonchildqry = "";
}


$childproductlink = $params->get('child_products_link');

$K2itemheaderMd = '';
$SEOglosseryheaderMd = '';

$SKUpreTXT = $params->get('sku_pre_txt');
$buynowTXT = $params->get('buynow_txt');
$showSKUint = $params->get('vm_expo_sku');
$noresultsTXT = $params->get('noresults_txt');
$lessThanThreePostTXT = $params->get('less_than_three_txt');
$noresultsBlocksTXT = $params->get('noresults__box_txt');
$NoProductResultsAtAllTXT = $params->get('noprresults_atall_txt');
$custom_position_include = $params->get('include_custom_pos');
$custom_modulepos = $params->get('custom_module_pos');
$CustomCSS = $params->get('css_override');
$showRatingsGeniusAjax = $params->get('show_ratings_ajax');
$showcatsinfo = $params->get('showcats_info');
$showmanufinfo = $params->get('showmanuf_info');
$shownewsinfo = $params->get('shownews_info');
$includeSdescSrc = $params->get('include_sdescr');
$showhighlightprd = $params->get('highlight_prods');
$showhighlightprdSKU = $params->get('highlight_sku');
$showhighlightcat = $params->get('highlight_cats');
$showhighlightmanuf = $params->get('highlight_manuf');
$showhighlightnews = $params->get('highlight_news');
$highlightcolor = $params->get('highlight_color');


$k2filterchoice = $params->get('k2catfilter');
$k2CatSelection = $params->get('k2category_id');

$Jartfilterchoice = $params->get('joomart_filter');
$JartCatsSelection = $params->get('joomla_catid');




$ratingStarColor = $params->get('starrating_color');

$results_ordering = $params->get('results_ordering');

$matching_depth = $params->get('matching_depth');

$catslimitint = $params->get('number_of_cats');
$manslimitint = $params->get('number_of_mans');
$productlimitint = $params->get('number_of_products');
$newslimitint = $params->get('number_of_news');

$newssource = $params->get('newssource');



define('STR_HIGHLIGHT_SIMPLE', 1);
define('STR_HIGHLIGHT_WHOLEWD', 2);
define('STR_HIGHLIGHT_CASESENS', 4);
define('STR_HIGHLIGHT_STRIPLINKS', 8);
 
function str_highlight($text, $needle, $options = null, $highlight = null) {
    // Default highlighting
    if ($highlight === null) {
        $highlight = '<span class="geniusHighlight">\1</span>';
    }
 
    // Select pattern to use
    if ($options & STR_HIGHLIGHT_SIMPLE) {
        $pattern = '#(%s)#';
        $sl_pattern = '#(%s)#';
    } else {
        $pattern = '#(?!<.*?)(%s)(?![^<>]*?>)#';
        $sl_pattern = '#<a\s(?:.*?)>(%s)</a>#';
    }
 
    // Case sensitivity
    if (!($options & STR_HIGHLIGHT_CASESENS)) {
        $pattern .= 'i';
        $sl_pattern .= 'i';
    }
 
    $needle = (array) $needle;
    foreach ($needle as $needle_s) {
        $needle_s = preg_quote($needle_s);
 
        // Escape needle with optional whole word check
        if ($options & STR_HIGHLIGHT_WHOLEWD) {
            $needle_s = '\b' . $needle_s . '\b';
        }
 
        // Strip links
        if ($options & STR_HIGHLIGHT_STRIPLINKS) {
            $sl_regex = sprintf($sl_pattern, $needle_s);
            $text = preg_replace($sl_regex, '\1', $text);
        }
 
        $regex = sprintf($pattern, $needle_s);
        $text = preg_replace($regex, $highlight, $text);
    }
 
    return $text;
}





$countprodsreslts = 0;
$IntCounterCats = 0;
$IntCounterManuf = 0;
$IntCounterNews = 0;


$ic = $params->get('internal_caching');
if (!empty($ic)) {
$cache_off = false;
$_SESSION['ajax_cache'] = true;
}
else {
$cache_off = true;
$_SESSION['ajax_cache'] = false;
}


                if (!class_exists ('CurrencyDisplay')) {
                        require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'currencydisplay.php');
                }
                $currency = CurrencyDisplay::getInstance( );
                
                $CurrentCurrencyID = $currency->getId();


	    $keyword = JRequest::getVar('keyword', '');
	    
	    
	    $keyword = str_replace("-|bsq|-","&",$keyword);

      $keyword = preg_replace('~[^-.,&/ \p{L}\p{N}]++~u', '', $keyword);
      
      
      
      $keyword = preg_replace('/\s+/', ' ',$keyword);
      
      
      $keyword = trim($keyword);
      $keyword = htmlspecialchars_decode($keyword);
      
      
      $checkifsrchisempty = $keyword;
      
  if(strlen($keyword) > 0) {
      
      
      $searchArrayStrForHL = explode(' ', $keyword);
      
      
	    $cachedir = JPATH_CACHE.DS.'mod_genius_vm_ajax_search_vm3';
	    $cachefile = $cachedir.DS.md5($keyword).'.lang'.$lang_code.'.curr'.$CurrentCurrencyID.'.part.html';

	    if (empty($cache_off))
	    {

	    if (!file_exists($cachedir))
	    {
	      @mkdir($cachedir);
	    }
	    else
	    {

	      if (file_exists($cachefile))
	      {
	        $x = file_get_contents($cachefile);
	        echo $x;
	        $mainframe->close();
	      }
	      else
	      {

	      }
	    }
	    }
	    ob_start();
	    
	    

		echo '<div class="GeniusAjaxModuleWrap template'.$templateofchoice.'">
		<div class="GeniusHeaderTopAjax">'.JText::_($SearchStringinfotxt).' <span class="wordinsearchGenius">"'.$keyword.'"</span>';
		echo '<a class="GeniusCloseLinkModalPop" href="javascript:;" onclick="serc()" >'.JText::_($ModalCloseTXT).'</a>';
		echo '</div>';
		
		
		require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php'); //overrides'.DS.'vmplugin.php');
		
		
		
			$orx='';

		  $ab = explode(' ', $keyword);	 



$qSpecialOrder = '';
$qSpecialOrderDeluxe = '';

foreach ($ab as $k)  {

if ($includeSdescSrc == 1) {
$qSpecialOrder .= " (CHAR_LENGTH(LCASE(`product_name`)) - CHAR_LENGTH(REPLACE(LCASE(`product_name`), LCASE('".$k."'), ''))) / CHAR_LENGTH(LCASE('".$k."'))
+
(CHAR_LENGTH(LCASE(`product_s_desc`)) - CHAR_LENGTH(REPLACE(LCASE(`product_s_desc`), LCASE('".$k."'), ''))) / CHAR_LENGTH(LCASE('".$k."'))
+
(CHAR_LENGTH(LCASE(`product_sku`)) - CHAR_LENGTH(REPLACE(LCASE(`product_sku`), LCASE('".$k."'), ''))) / CHAR_LENGTH(LCASE('".$k."'))
+";

if ($matching_depth == 1) {
$qSpecialOrderDeluxe .= " CONCAT(l.product_name, l.product_s_desc, p.product_sku) LIKE '%".$k."%' OR";
} 
else {
$qSpecialOrderDeluxe .= " CONCAT(l.product_name, l.product_s_desc, p.product_sku) LIKE '%".$k."%' AND";
}

} 
else {
$qSpecialOrder .= " (CHAR_LENGTH(LCASE(`product_name`)) - CHAR_LENGTH(REPLACE(LCASE(`product_name`), LCASE('".$k."'), ''))) / CHAR_LENGTH(LCASE('".$k."'))
+
(CHAR_LENGTH(LCASE(`product_sku`)) - CHAR_LENGTH(REPLACE(LCASE(`product_sku`), LCASE('".$k."'), ''))) / CHAR_LENGTH(LCASE('".$k."'))
+";

if ($matching_depth == 1) {
$qSpecialOrderDeluxe .= " CONCAT(l.product_name, p.product_sku) LIKE '%".$k."%' OR";
}
else {
$qSpecialOrderDeluxe .= " CONCAT(l.product_name, p.product_sku) LIKE '%".$k."%' AND";
}

}	 
}

$qSpecialOrder = rtrim($qSpecialOrder, "+");

if ($matching_depth == 1) {
$qSpecialOrderDeluxe = rtrim($qSpecialOrderDeluxe, "OR");
}
else {
$qSpecialOrderDeluxe = rtrim($qSpecialOrderDeluxe, "AND");
}


$whereClauseSpecialDeluxe = "(".$qSpecialOrderDeluxe.")";



if ($results_ordering == 0) { 
$qProduct ="SELECT p.virtuemart_product_id, l.product_name,
".$qSpecialOrder." `occurences`
from #__virtuemart_products p join
      #__virtuemart_products_".$dblangcode." l
      on p.virtuemart_product_id = l.virtuemart_product_id
WHERE ".$whereClauseSpecialDeluxe."
AND p.published = '1' ".$specialnonchildqry." ORDER BY `occurences` DESC, l.product_name LIMIT ".$productlimitint;
} 
else if ($results_ordering == 1) {
$qProduct ="SELECT p.virtuemart_product_id, l.product_name,
".$qSpecialOrder." `occurences`
from #__virtuemart_products p join
      #__virtuemart_products_".$dblangcode." l
      on p.virtuemart_product_id = l.virtuemart_product_id
WHERE ".$whereClauseSpecialDeluxe."
AND p.published = '1' ".$specialnonchildqry." ORDER BY l.product_name LIMIT ".$productlimitint;
} 
else if ($results_ordering == 2) {
$qProduct ="SELECT p.virtuemart_product_id, l.product_name,
".$qSpecialOrder." `occurences`
from #__virtuemart_products p join
      #__virtuemart_products_".$dblangcode." l
      on p.virtuemart_product_id = l.virtuemart_product_id
WHERE ".$whereClauseSpecialDeluxe."
AND p.published = '1' ".$specialnonchildqry." ORDER BY l.product_name DESC LIMIT ".$productlimitint;
}



$qSpecialOrderCats = '';
$qSpecialOrderDeluxeCats = '';
$qSpecialOrderManuf = '';
$qSpecialOrderDeluxeManuf = '';
$qSpecialOrderNewsK2 = '';
$qSpecialOrderDeluxeNewsK2 = '';
$qSpecialOrderNewsJoomla = '';
$qSpecialOrderDeluxeNewsJoomla = '';


foreach ($ab as $k)  {

//Cats
$qSpecialOrderCats .= " (CHAR_LENGTH(LCASE(`category_name`)) - CHAR_LENGTH(REPLACE(LCASE(`category_name`), LCASE('".$k."'), ''))) / CHAR_LENGTH(LCASE('".$k."'))
+
(CHAR_LENGTH(LCASE(`category_description`)) - CHAR_LENGTH(REPLACE(LCASE(`category_description`), LCASE('".$k."'), ''))) / CHAR_LENGTH(LCASE('".$k."'))
+";
if ($matching_depth == 1) {
$qSpecialOrderDeluxeCats .= " CONCAT(l.category_name, l.category_description) LIKE '%".$k."%' OR";
}
else {
$qSpecialOrderDeluxeCats .= " CONCAT(l.category_name, l.category_description) LIKE '%".$k."%' AND";
}


//Manuf.
$qSpecialOrderManuf .= " (CHAR_LENGTH(LCASE(`mf_name`)) - CHAR_LENGTH(REPLACE(LCASE(`mf_name`), LCASE('".$k."'), ''))) / CHAR_LENGTH(LCASE('".$k."'))
+
(CHAR_LENGTH(LCASE(`mf_desc`)) - CHAR_LENGTH(REPLACE(LCASE(`mf_desc`), LCASE('".$k."'), ''))) / CHAR_LENGTH(LCASE('".$k."'))
+";
if ($matching_depth == 1) {
$qSpecialOrderDeluxeManuf .= " CONCAT(l.mf_name, l.mf_desc) LIKE '%".$k."%' OR";
}
else {
$qSpecialOrderDeluxeManuf .= " CONCAT(l.mf_name, l.mf_desc) LIKE '%".$k."%' AND";
}


//News K2
$qSpecialOrderNewsK2 .= " (CHAR_LENGTH(LCASE(`title`)) - CHAR_LENGTH(REPLACE(LCASE(`title`), LCASE('".$k."'), ''))) / CHAR_LENGTH(LCASE('".$k."'))
+";
if ($matching_depth == 1) {
$qSpecialOrderDeluxeNewsK2 .= " title LIKE '%".$k."%' OR";
}
else {
$qSpecialOrderDeluxeNewsK2 .= " title LIKE '%".$k."%' AND";
}


//News Joomla
$qSpecialOrderNewsJoomla .= " (CHAR_LENGTH(LCASE(`title`)) - CHAR_LENGTH(REPLACE(LCASE(`title`), LCASE('".$k."'), ''))) / CHAR_LENGTH(LCASE('".$k."'))
+";
if ($matching_depth == 1) {
$qSpecialOrderDeluxeNewsJoomla .= " title LIKE '%".$k."%' OR";
}
else {
$qSpecialOrderDeluxeNewsJoomla .= " title LIKE '%".$k."%' AND";
}


}

$qSpecialOrderCats = rtrim($qSpecialOrderCats, "+");
$qSpecialOrderManuf = rtrim($qSpecialOrderManuf, "+");
$qSpecialOrderNewsK2 = rtrim($qSpecialOrderNewsK2, "+");
$qSpecialOrderNewsJoomla = rtrim($qSpecialOrderNewsJoomla, "+");

if ($matching_depth == 1) {
$qSpecialOrderDeluxeCats = rtrim($qSpecialOrderDeluxeCats, "OR");
$qSpecialOrderDeluxeManuf = rtrim($qSpecialOrderDeluxeManuf, "OR");
$qSpecialOrderDeluxeNewsK2 = rtrim($qSpecialOrderDeluxeNewsK2, "OR");
$qSpecialOrderDeluxeNewsJoomla = rtrim($qSpecialOrderDeluxeNewsJoomla, "OR");
}
else {
$qSpecialOrderDeluxeCats = rtrim($qSpecialOrderDeluxeCats, "AND");
$qSpecialOrderDeluxeManuf = rtrim($qSpecialOrderDeluxeManuf, "AND");
$qSpecialOrderDeluxeNewsK2 = rtrim($qSpecialOrderDeluxeNewsK2, "AND");
$qSpecialOrderDeluxeNewsJoomla = rtrim($qSpecialOrderDeluxeNewsJoomla, "AND");
}


$whereClauseSpecialDeluxeCats = "(".$qSpecialOrderDeluxeCats.")";
$whereClauseSpecialDeluxeManuf = "(".$qSpecialOrderDeluxeManuf.")";
$whereClauseSpecialDeluxeNewsK2 = "(".$qSpecialOrderDeluxeNewsK2.")";
$whereClauseSpecialDeluxeNewsJoomle = "(".$qSpecialOrderDeluxeNewsJoomla.")";





//K2 Cats To Match
$extrak2catcondition = '';
if ($shownewsinfo == 1 && $newssource == 2) {

if ($k2filterchoice == 0) {
$extrak2catcondition = '';
} 
else {
$finalK2CatIDsToUse = $k2CatSelection;
//$finalK2CatIDsToUse = implode(', ', $finalK2CatIDsToUse);

$extrak2catcondition = "(";
foreach ($finalK2CatIDsToUse as $iditemCID) {
$extrak2catcondition .= " catid = '".$iditemCID."' OR";
}
$extrak2catcondition = substr($extrak2catcondition, 0, -3);
$extrak2catcondition .= " ) AND ";
}

}



//Joomla Cats To Match
$extrajArtcatcondition = '';
if ($shownewsinfo == 1 && $newssource == 1) {

if ($Jartfilterchoice == 0) {
$extrajArtcatcondition = '';
} 
else {
$finaljArtCatIDsToUse = $JartCatsSelection;

$extrajArtcatcondition = "(";
foreach ($finaljArtCatIDsToUse as $iditemCID) {
$extrajArtcatcondition .= " catid = '".$iditemCID."' OR";
}
$extrajArtcatcondition = substr($extrajArtcatcondition, 0, -3);
$extrajArtcatcondition .= " ) AND ";
}

}




if ($results_ordering == 0) { 
//Cats
$q_cat ="SELECT p.virtuemart_category_id, l.category_name,
".$qSpecialOrderCats." `occurences`
from #__virtuemart_categories p join
        #__virtuemart_categories_".$dblangcode." l
        on p.virtuemart_category_id = l.virtuemart_category_id
WHERE ".$whereClauseSpecialDeluxeCats."
AND p.published = '1' ORDER BY `occurences` DESC, l.category_name LIMIT ".$catslimitint;

//Manuf
$q_manf ="SELECT p.virtuemart_manufacturer_id, l.mf_name,
".$qSpecialOrderManuf." `occurences`
from #__virtuemart_manufacturers p join
                  #__virtuemart_manufacturers_".$dblangcode." l
                  on p.virtuemart_manufacturer_id = l.virtuemart_manufacturer_id
WHERE ".$whereClauseSpecialDeluxeManuf."
AND p.published = '1' ORDER BY `occurences` DESC, l.mf_name LIMIT ".$manslimitint;

//News K2
$q_k2query ="SELECT id, title, catid, alias,
".$qSpecialOrderNewsK2." `occurences`
from #__k2_items
WHERE ".$whereClauseSpecialDeluxeNewsK2."
AND published = '1' AND access = '1' AND ".$extrak2catcondition."(language = '".$ARTSlangcodeforQuery."' or language = '*') ORDER BY `occurences` DESC, title LIMIT ".$newslimitint;

//News Joomla
$q_joomlaquery ="SELECT id, title, catid, alias,
".$qSpecialOrderNewsJoomla." `occurences`
from #__content
WHERE ".$whereClauseSpecialDeluxeNewsJoomle."
AND state = '1' AND access = '1' AND ".$extrajArtcatcondition."(language = '".$ARTSlangcodeforQuery."' or language = '*') ORDER BY `occurences` DESC, title LIMIT ".$newslimitint;
} 



else if ($results_ordering == 1) {
//Cats
$q_cat ="SELECT p.virtuemart_category_id, l.category_name,
".$qSpecialOrderCats." `occurences`
from #__virtuemart_categories p join
        #__virtuemart_categories_".$dblangcode." l
        on p.virtuemart_category_id = l.virtuemart_category_id
WHERE ".$whereClauseSpecialDeluxeCats."
AND p.published = '1' ORDER BY l.category_name LIMIT ".$catslimitint;

//Manuf
$q_manf ="SELECT p.virtuemart_manufacturer_id, l.mf_name,
".$qSpecialOrderManuf." `occurences`
from #__virtuemart_manufacturers p join
                  #__virtuemart_manufacturers_".$dblangcode." l
                  on p.virtuemart_manufacturer_id = l.virtuemart_manufacturer_id
WHERE ".$whereClauseSpecialDeluxeManuf."
AND p.published = '1' ORDER BY l.mf_name LIMIT ".$manslimitint;

//News K2
$q_k2query ="SELECT id, title, catid, alias,
".$qSpecialOrderNewsK2." `occurences`
from #__k2_items
WHERE ".$whereClauseSpecialDeluxeNewsK2."
AND published = '1' AND access = '1' AND ".$extrak2catcondition."(language = '".$ARTSlangcodeforQuery."' or language = '*') ORDER BY title LIMIT ".$newslimitint;

//News Joomla
$q_joomlaquery ="SELECT id, title, catid, alias,
".$qSpecialOrderNewsJoomla." `occurences`
from #__content
WHERE ".$whereClauseSpecialDeluxeNewsJoomle."
AND state = '1' AND access = '1' AND ".$extrajArtcatcondition."(language = '".$ARTSlangcodeforQuery."' or language = '*') ORDER BY title LIMIT ".$newslimitint;
} 



else if ($results_ordering == 2) {
//Cats
$q_cat ="SELECT p.virtuemart_category_id, l.category_name,
".$qSpecialOrderCats." `occurences`
from #__virtuemart_categories p join
        #__virtuemart_categories_".$dblangcode." l
        on p.virtuemart_category_id = l.virtuemart_category_id
WHERE ".$whereClauseSpecialDeluxeCats."
AND p.published = '1' ORDER BY l.category_name DESC LIMIT ".$catslimitint;

//Manuf
$q_manf ="SELECT p.virtuemart_manufacturer_id, l.mf_name,
".$qSpecialOrderManuf." `occurences`
from #__virtuemart_manufacturers p join
                  #__virtuemart_manufacturers_".$dblangcode." l
                  on p.virtuemart_manufacturer_id = l.virtuemart_manufacturer_id
WHERE ".$whereClauseSpecialDeluxeManuf."
AND p.published = '1' ORDER BY l.mf_name DESC LIMIT ".$manslimitint;

//News K2
$q_k2query ="SELECT id, title, catid, alias,
".$qSpecialOrderNewsK2." `occurences`
from #__k2_items
WHERE ".$whereClauseSpecialDeluxeNewsK2."
AND published = '1' AND access = '1' AND ".$extrak2catcondition."(language = '".$ARTSlangcodeforQuery."' or language = '*') ORDER BY title DESC LIMIT ".$newslimitint;

//News Joomla
$q_joomlaquery ="SELECT id, title, catid, alias,
".$qSpecialOrderNewsJoomla." `occurences`
from #__content
WHERE ".$whereClauseSpecialDeluxeNewsJoomle."
AND state = '1' AND access = '1' AND ".$extrajArtcatcondition."(language = '".$ARTSlangcodeforQuery."' or language = '*') ORDER BY title LIMIT ".$newslimitint;
}




                  $db->setQuery($q_cat);
                  $ps_cat = @$db->loadAssocList();
                  $cat_found = false;
                  if(count($ps_cat) > 0 && $showSKUint == 1) {
                      $cat_found = true;
                      $masterstringcats = '';

                      
                      $IntCounterCats = 0;
                      foreach($ps_cat as $p_cat)  {               
                      
                      $IntCounterCats = $IntCounterCats + 1;
                          $href_cat = "";
                          
                            $href_cat = ('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$p_cat['virtuemart_category_id']);
						                $href_cat = JRoute::_ ($href_cat);
                            $href_cat = str_replace($fixsefURLtoreplacements, $fixsefURLtoreplacements2, $href_cat);
                          
 
                          $masterstringcats .= '<div class="single_category">';
                          
                          $stringCatname = $p_cat['category_name'];
                          
                          $CatnameHighlighted = htmlspecialchars_decode($stringCatname);
                          if ($showhighlightcat == 1 && strlen($checkifsrchisempty) > 0) {
                          $CatnameHighlighted = str_highlight($CatnameHighlighted, $searchArrayStrForHL);
                          }
                          
                          $masterstringcats .= '<div class="single_category_title"><a title="'.$p_cat['category_name'].'" href="'.$href_cat.'">'.$CatnameHighlighted.'</a></div>
                          </div>';

                      }
                  }
                  
                                   


                  $db->setQuery($q_manf);
                  $ps_manf = @$db->loadAssocList();
                  $manf_found = false;
                  if(count($ps_manf) > 0 && $showSKUint == 1) {
                      $manf_found = true;
                      
                  $masterstringmanuf = '';

                      
                      foreach($ps_manf as $p_cat) {         
                      
                      $IntCounterManuf = $IntCounterManuf + 1;
                          $href_cat = "";
                          
                            $href_cat = ('index.php?option=com_virtuemart&view=manufacturer&layout=details&virtuemart_manufacturer_id='.$p_cat['virtuemart_manufacturer_id']);
						                $href_cat = JRoute::_ ($href_cat);
						                $href_cat = str_replace($fixsefURLtoreplacements, $fixsefURLtoreplacements2, $href_cat);
						                                    
                          $masterstringmanuf .= '<div class="single_category">';
                          
                          $stringCatname = $p_cat['mf_name'];
                          
                          $CatnameHighlighted = htmlspecialchars_decode($stringCatname);
                          if ($showhighlightmanuf == 1 && strlen($checkifsrchisempty) > 0) {
                          $CatnameHighlighted = str_highlight($CatnameHighlighted, $searchArrayStrForHL);
                          }
                          
                          $masterstringmanuf .= '<div class="single_category_title"><a title="'.$p_cat['mf_name'].'" href="'.$href_cat.'">'.$CatnameHighlighted.'</a></div></div>';

                      }

                  }
                  
                  
                  
                  

$masterstringNews = '';
    
if ($newssource == 1) {
                  $db->setQuery($q_joomlaquery);
                  $ps_joomlaquery = @$db->loadAssocList();
                  $JoomlaArt_found = false;
                  if(count($ps_joomlaquery) > 0) {
                      $JoomlaArt_found = true;



                      
                      foreach($ps_joomlaquery as $p_JArtquery) {  
                      
if(!class_exists('ContentHelperRoute')) {
require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');  
}

$jArtBaseURL = JRoute::_(ContentHelperRoute::getArticleRoute(  $p_JArtquery['id'],  $p_JArtquery['catid'] ));

$jArtBaseURL = str_replace($fixsefURLtoreplacements, $fixsefURLtoreplacements2, $jArtBaseURL);

//$jArtBaseURL = 'Test...';
                      
                      $IntCounterNews = $IntCounterNews + 1;	                                    
                          $masterstringNews .= '<div class="single_category">'; 
                                                  
                          $stringJartname = $p_JArtquery['title'];
                          
                          
                          $jArtnameHighlighted = htmlspecialchars_decode($stringJartname);
                          if ($showhighlightnews == 1 && strlen($checkifsrchisempty) > 0) {
                          $jArtnameHighlighted = str_highlight($jArtnameHighlighted, $searchArrayStrForHL);
                          }
                          
                          $masterstringNews .= '<div class="single_category_title"><a title="'.$p_JArtquery['title'].'" href="'.$jArtBaseURL.'">'.$jArtnameHighlighted.'</a></div></div>';

                      }

                  }
                  
}
    
                  

if ($newssource == 2) {
                  $db->setQuery($q_k2query);
                  $ps_k2query = @$db->loadAssocList();
                  $K2f_found = false;
                  if(count($ps_k2query) > 0) {
                      $K2f_found = true;


require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'models'.DS.'model.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'utilities.php');
                      
                      
                      foreach($ps_k2query as $p_k2query) {  
                      

$sql = "SELECT alias FROM #__k2_categories WHERE id='".$p_k2query['catid']."'";
$db->setQuery($sql);
$arrK2CategoryInformation = $db->loadAssoc();

$strK2SEFURL = JRoute::_(K2HelperRoute::getItemRoute($p_k2query['id'].':'.urlencode($p_k2query['alias']),
$p_k2query['catid'].':'.urlencode($arrK2CategoryInformation['alias']
)));
$strK2SEFURL = str_replace($fixsefURLtoreplacements, $fixsefURLtoreplacements2, $strK2SEFURL);
//the K2 SEF URL will be stored in $strK2SEFURL              
                      
                      $IntCounterNews = $IntCounterNews + 1;	                                    
                          $masterstringNews .= '<div class="single_category">';                         
                          $stringK2name = $p_k2query['title'];
                          
                          
                          $K2nameHighlighted = htmlspecialchars_decode($stringK2name);
                          if ($showhighlightnews == 1 && strlen($checkifsrchisempty) > 0) {
                          $K2nameHighlighted = str_highlight($K2nameHighlighted, $searchArrayStrForHL);
                          }
                          
                          $masterstringNews .= '<div class="single_category_title"><a title="'.$p_k2query['title'].'" href="'.$strK2SEFURL.'">'.$K2nameHighlighted.'</a></div></div>';

                      }

                  }
                  
}
                  
                  
                  

	   $db->setQuery($qProduct);

		 $ps_product = @$db->loadAssocList();

	     $err = $db->getErrorMsg();
	     if (!empty($err)) {
			echo $err;
		 $mainframe->close();  }

	    include_once(JPATH_ROOT.DS.'modules'.DS.'mod_genius_vm_ajax_search_vm3'.DS.'ajax'.DS.'helper.php');
	    $ahelper = new ajaxProductHelper();
	    $prod_found = false;
		//print_r($ps);
if (!empty($ps_product) && $showSKUint == 1)		{
$dbj = JFactory::getDBO();
$prod_found = true;

		$xb = true;
		$n = 0;
		
		$masterstringproducts = '';
                //echo '<h3>Produkter</h3>';

                $productModel = VmModel::getModel('Product');



foreach ($ps_product as $row)   {

$isvailablechk = 0;


		  $ajx_single_product = $productModel->getProduct($row['virtuemart_product_id']);
		  $productModel->addImages($ajx_single_product);

$qGetPublishedint = $ajx_single_product->published;




if($ajx_single_product->virtuemart_product_id > 0) {
$isvailablechk = 1;
}


if ($qGetPublishedint == 1 && $isvailablechk == 1) {            

$countprodsreslts = $countprodsreslts + 1; 
		  
//$basePreURL = "http://" . $_SERVER['HTTP_HOST'];
$smallimageURL = $baseJoomlaURL.$ajx_single_product->images[0]->file_url_thumb;
$smallimageURL = str_replace('/modules/mod_genius_vm_ajax_search_vm3/ajax', '', $smallimageURL);
$checkfilesizeSmall = JPATH_BASE.DS.$ajx_single_product->images[0]->file_url_thumb;

$smallimageURL_LARGE = $baseJoomlaURL.$ajx_single_product->images[0]->file_url;
$smallimageURL_LARGE = str_replace('/modules/mod_genius_vm_ajax_search_vm3/ajax', '', $smallimageURL_LARGE);
$checkfilesizeBIG = JPATH_BASE.DS.$ajx_single_product->images[0]->file_url;
		  
if(@is_array(getimagesize($checkfilesizeSmall))){
$smallimageURL = $smallimageURL;
} else if(@is_array(getimagesize($checkfilesizeBIG))) {
$smallimageURL = $smallimageURL_LARGE;
}		  
else {
$smallimageURL = '';
}		  


if(count($ajx_single_product->categories) > 1)
{
    $productCategory = -1;
    $categoryModel = VmModel::getModel('category');
    foreach($ajx_single_product->categories as $cx_id)
    {
        $nim_category = $categoryModel->getCategory($cx_id);
        if($nim_category->published == 1)
        {
            $ajx_single_product->virtuemart_category_id = $cx_id;
            $productCategory = $cx_id;
        }
    }
    if($productCategory == -1)
    {
        $ajx_single_product->virtuemart_category_id = empty($ajx_single_product->categories[0]) ? '' : $ajx_single_product->categories[0];
    }
}
else
{
    $ajx_single_product->virtuemart_category_id = empty($ajx_single_product->categories[0]) ? '' : $ajx_single_product->categories[0];
}
 

		  if (isset($row['virtuemart_product_id'])) $row['product_id'] = $row['virtuemart_product_id'];



	      $pname = $row['product_name'];


                          $pnameGihlighted = htmlspecialchars_decode($pname); 
                          if ($showhighlightprd == 1 && strlen($checkifsrchisempty) > 0) {
                          $pnameGihlighted = str_highlight($pnameGihlighted, $searchArrayStrForHL);
                          }

         
         
         
         
        if ($childproductlink == 1 && $ajx_single_product->product_parent_id > 0) {
        $prIDforURL = $ajx_single_product->product_parent_id;
        $ajx_single_product_parent = $productModel->getProduct($prIDforURL);
        
        
if(count($ajx_single_product_parent->categories) > 1) {
    $productCategory = -1;
    $categoryModel = VmModel::getModel('category');
    foreach($ajx_single_product_parent->categories as $cx_id) {
        $nim_category = $categoryModel->getCategory($cx_id);
        if($nim_category->published == 1)  {
            $ajx_single_product_parent->virtuemart_category_id = $cx_id;
            $productCategory = $cx_id;
        }
    }
    if($productCategory == -1) {
        $ajx_single_product_parent->virtuemart_category_id = empty($ajx_single_product_parent->categories[0]) ? '' : $ajx_single_product_parent->categories[0];
    }
}
else {
    $ajx_single_product_parent->virtuemart_category_id = empty($ajx_single_product_parent->categories[0]) ? '' : $ajx_single_product_parent->categories[0];
}
        
 $prCatIDforURL = $ajx_single_product_parent->virtuemart_category_id;     
       
        
        } else {
        $prIDforURL = $row['product_id'];
        $prCatIDforURL = $ajx_single_product->virtuemart_category_id;
        } 
        
          

			  $href = 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$prIDforURL.'&virtuemart_category_id='.$prCatIDforURL;	
		  	$href = JRoute::_ ($href);
		  	
			  $href = str_replace($fixsefURLtoreplacements, $fixsefURLtoreplacements2, $href);
			
			$dbj = JFactory::getDBO();
			
			if ($ajx_single_product->product_parent_id > 0) {
			$pIDforprIMG = $ajx_single_product->product_parent_id;
			}
			else {
			$pIDforprIMG = $row['product_id'];
			}
			
			
		  if ($smallimageURL == '') {
		  $finalIMGstring = $baseJoomlaURL.'modules/mod_genius_vm_ajax_search_vm3/css/no-image.jpg';
		  $finalIMGstring = str_replace('/modules/mod_genius_vm_ajax_search_vm3/ajax', '', $finalIMGstring); 
		  }
		  else {
		  $finalIMGstring = $smallimageURL;
		  }
      
      
            $qGetSKU = 'select product_sku from #__virtuemart_products where virtuemart_product_id = '.$row['product_id'];
            $dbj->setQuery($qGetSKU);
            $SKUsrcstring = $dbj->loadResult();
            

                          $SKUGihlighted = htmlspecialchars_decode($SKUsrcstring);
                          if ($showhighlightprdSKU == 1 && strlen($checkifsrchisempty) > 0) {
                          $SKUGihlighted = str_highlight($SKUGihlighted, $searchArrayStrForHL);
                          }
            
        
        $ratingModel = VmModel::getModel('ratings');
        
        $ajx_single_product->showRating = $ratingModel->showRating($ajx_single_product->virtuemart_product_id);
        if ($ajx_single_product->showRating) {
              $ajx_single_product->vote = $ratingModel->getVoteByProduct($ajx_single_product->virtuemart_product_id);
              $ajx_single_product->rating = $ratingModel->getRatingByProduct($ajx_single_product->virtuemart_product_id);
        }
        
        $maxrating = 5;
        
        if(is_object($ajx_single_product->rating)) {
        
        
        $ratingwidth = ( round($ajx_single_product->rating->rating) * 100 ) / $maxrating;
        
        $starControllerStyle = '';
        if ($ratingwidth == 0) {
        $starControllerStyle = 'display: none;';
        }
        
        $hideStarRatingCount = '';
        if ($ajx_single_product->rating->ratingcount > 0) {
        $starRatingCount = $ajx_single_product->rating->ratingcount;
        } else {
        $hideStarRatingCount = 'hideGeniusCountStarrating';
        $starRatingCount = 0;
        }
        
        }
        else {
        $hideStarRatingCount = 'hideGeniusCountStarrating';
        $starRatingCount = 0;
        $starControllerStyle = 'display: none;';
        }
        
        
	      $masterstringproducts .= '<div onclick="document.location=\''.$href.'\';" id="GeniusAjaxSearchprRow'.$myid.'_'.$n.'" class="srchproductrows" title="'.$pname.'">
	      <a href="'.$href.'" title="'.$pname.'" class="geniusprImage"> <div class="GeniusMasterimgClass">
	      <div class="wraptocenter"><span></span>
	      <img src="'.$finalIMGstring.'" alt="'.$pname.'">  
	      </div>
	      </div>
	      </a>
	      <div class="GeniusTapTwoWrapOuter">
	      <div class="GeniusTapTwoWrap">
	      <div class="prdktisearch">';
	      $id = ' id="prod'.$row['product_id'].'_'.$myid.'" ';
	      $masterstringproducts .= '<a class="product_lnk_ajax_text" style="text-align: left;" href="'.$href.'" '.$id.'>'.$pnameGihlighted.'</a>
	      <div class="GeniusSKUajax">'.JText::_($SKUpreTXT).' '.$SKUGihlighted.'</div>';
	      
	      
	      if ($showRatingsGeniusAjax == 1) {
	      $masterstringproducts .= '<div class="GeniusStarRating">
	      <span class="baseStarsGenius"><span>&#9734;</span><span>&#9734;</span><span>&#9734;</span><span>&#9734;</span><span>&#9734;</span></span>
	      <span class="outerStarsGenius" style="width: '.$ratingwidth.'%;color: '.$ratingStarColor.';'.$starControllerStyle.'"><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span></span>
	      </div>
	      <div class="GeniusStarRatingCount '.$hideStarRatingCount.'">('.$starRatingCount.')</div>';  
	      }
	      
	      
	      if ($price_type_display == 0) {
	      $masterstringproducts .= '</div></div>
	      <div class="GeniusTapThreeWrap"><div class="GeniusPriceTag">'.$currency->createPriceDiv('priceWithoutTax','',$ajx_single_product->prices,true).'</div><a class="GeniusAjaxbuynow" href="'.$href.'">'.JText::_($buynowTXT).'</a></div>
	      </div>';	      
	      }
	      else {
	      $masterstringproducts .= '</div></div>
	      <div class="GeniusTapThreeWrap"><div class="GeniusPriceTag">'.$currency->createPriceDiv('salesPrice','',$ajx_single_product->prices,true).'</div><a class="GeniusAjaxbuynow" href="'.$href.'">'.JText::_($buynowTXT).'</a></div>
	      </div>';
	      }
	      $masterstringproducts .= '</div>';

	      $xb = !$xb;
		  $n++;
	    
} }	    
	    
}                  
                                  

include 'templates/'.$templateofchoice.'.php';


echo '</div>';


echo '<style>
span.geniusHighlight {
    background-color: '.$highlightcolor.';
}
'.$CustomCSS .' ';
include 'templates/'.$templateofchoice.'.css';
echo '</style>';

	    echo '</div></div>';
	    
	    
	    $html = ob_get_clean();
	    echo $html;

	    if (empty($cache_off))
	    @file_put_contents($cachefile, $html);

	    $mainframe->triggerEvent( 'onAfterRender' );


function tableExists($table) {
 $db = JFactory::getDBO();
 $prefix = $db->getPrefix();
 $table = str_replace('#__', '', $table);
 $table = str_replace($prefix, '', $table);
 $q = "SHOW TABLES LIKE '".$db->getprefix().$table."'";
 $db->setQuery($q);
 $r = $db->loadResult();
 if (!empty($r)) return true;
 return false;
}

class op_compatibility2 {
 function url($p1, $p2, $p3)
 {
   if (!empty($GLOBALS['sess'])) return $GLOBALS['sess']->url($p1, $p2, $p3);
   else return JRoute::_($p1);
 }
 function getShopItemid()
 {
   return JRequest::getVar('Itemid');
 }
 function _($val)
 {
   $v2 = str_replace('PHPSHOP_', 'COM_VIRTUEMART_', $val);
   return JText::_($v2);
 }
 function load($str='')
 {
 }
}

}
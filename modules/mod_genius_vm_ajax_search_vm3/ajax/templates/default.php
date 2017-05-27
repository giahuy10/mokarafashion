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


$classforprdrslt = '';
if (empty($masterstringproducts)) {
$classforprdrslt = 'noresultsGeniusprds';
}


$lessthanThreePrds = '';
if ($countprodsreslts < 3) {
$lessthanThreePrds = 'lessthanthreePrds';
}

//Show Products results
echo '<div class="GeniusProductsMasterWrapperprblock '.$lessthanThreePrds.' '.$classforprdrslt.'">';
echo '<div class="GeniusProductsMasterWrapper">
<div class="innerGeniusDivResults">
<div class="GeniusAjaxBlokHeader">'.JText::_($ProductheaderMd).' <span>('.$countprodsreslts.')</span></div>';
if (empty($masterstringproducts)) {
} else {
echo $masterstringproducts;
}
echo '<div class="noPRresultsWhatsoever" style="display:none;">'.JText::_($NoProductResultsAtAllTXT).'</div>
<div class="postLessThanThreetxt" style="display:none;">'.JText::_($lessThanThreePostTXT).'</div>
</div>';
  
  
if (strlen ( $custom_modulepos ) > 0 && $custom_position_include == 1) {
echo '<div class="GeniusCustomModuleDivWrapper">';

if (!class_exists('JModuleHelper')) {
jimport( 'joomla.application.module.helper' );
}
 $zone = $custom_modulepos;
 $moduleOutputAll = '';
          $modules = JModuleHelper::getModules($zone);
          foreach ($modules as $module){
             $moduleOutputAll .= JModuleHelper::renderModule($module);
   }
$moduleOutputAll = str_replace('modules/mod_genius_vm_ajax_search_vm3/ajax/', '', $moduleOutputAll);
echo $moduleOutputAll; 

echo '</div>';
}
echo '</div>
</div>';

//Show Cats results
echo '<div class="GeniusCatsManufsMasterWrapperprblock">';

echo '<div class="GeniusCatsManufsMasterWrapperInner">';
if ($showcatsinfo == 1) {
echo '<div class="GeniusCatsMasterWrapper"><div class="innerGeniusDiv">
<div class="GeniusAjaxBlokHeader">'.JText::_($CategoryheaderMd).' <span>('.$IntCounterCats.')</span></div>
<div class="innerGeniusDivResults">';
if (empty($masterstringcats)) {
echo '<div class="noresultsfoundGeniusblok">'.JText::_($noresultsBlocksTXT).'</div>';
} else {
echo $masterstringcats;
}
echo '</div></div></div>';
}

//Show Manuf results
if ($showmanufinfo == 1) {
echo '<div class="GeniusManufMasterWrapper"><div class="innerGeniusDiv">
<div class="GeniusAjaxBlokHeader">'.JText::_($ManufheaderMd).' <span>('.$IntCounterManuf.')</span></div>
<div class="innerGeniusDivResults">';
if (empty($masterstringmanuf)) {
echo '<div class="noresultsfoundGeniusblok">'.JText::_($noresultsBlocksTXT).'</div>';
} else {
echo $masterstringmanuf;
}
echo '</div></div></div>';
}

echo '</div>';


//Show News results
if ($shownewsinfo == 1) {
echo '<div class="GeniusNewsMasterWrapper"><div class="innerGeniusDiv">
<div class="GeniusAjaxBlokHeader">'.JText::_($NewsheaderMd).' <span>('.$IntCounterNews.')</span></div>
<div class="innerGeniusDivResults">';
if (empty($masterstringNews)) {
echo '<div class="noresultsfoundGeniusblok">'.JText::_($noresultsBlocksTXT).'</div>';
} else {
echo $masterstringNews;
}
echo '</div></div></div>';
}
echo '</div>';     
                      
?>
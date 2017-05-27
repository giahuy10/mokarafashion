<?php
	defined ('_JEXEC') or  die('Direct Access to ' . basename (__FILE__) . ' is not allowed.');
	
// Licensed under the GPL v2&
/**
* @version		mod_genius_vm_ajax_search_vm3.php ,v 1.0.0
* @package		mod_genius_vm_ajax_search_vm3.zip
* @copyright  (C) 2015 Mikkel Olsen / Genius WebDesign, https://www.genius-webdesign.com/
* @license		see docs/LICENSE.txt
*
* Joomla 2.5+ Module
*/

class ModGeniusVMAjaxSearchVm3Helper {

public static function getGeniusVMSearchResultsAjax(){
    
    //Get the app
    $app = JFactory::getApplication();
    
    //Insert stuff to do here



require_once ( 'ajax/genius-functions.php' );

include_once "ajax/indexajax.php";
    

    //close the $app
    $app->close();

}


	/*
	 * @deprecated
	 */

}

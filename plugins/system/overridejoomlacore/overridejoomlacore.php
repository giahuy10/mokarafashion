<?php
/**
 * @package		Simple Mobile Detection
 * @subpackage	plg_cmobile
 * @copyright	Copyright (C) 2013 Conflate. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.joomla-specialist.net
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemOverrideJoomlaCore  extends JPlugin{

	function __construct( &$subject, $params ){
		parent::__construct( $subject, $params );
		
	}
	
	
	public function onAfterRoute() {
          $app = JFactory::getApplication();
          if('com_content' == JRequest::getCMD('option') && 'category' == JRequest::getCMD('view') && 'blog' == JRequest::getCMD('layout') && !$app->isAdmin()) {
              require_once(dirname(__FILE__) .'/override/mod_articles_front_end.php');
          }
     }
}

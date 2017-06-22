<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Inventory
 * @author     Eddy Nguyen <contact@eddynguyen.com>
 * @copyright  2017 Eddy Nguyen
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;


/**
 * Class InventoryRouter
 *
 * @since  3.3
 */


	 function inventoryBuildRoute(&$query)
	{
		$segments = array();
		$view     = null;		

		 if (isset($query['category']))
       {
                $segments[] = $query['category'];
                unset($query['category']);
       }
	  
       if (isset($query['id']))
       {
                $segments[] = $query['id'];
                unset($query['id']);
       };
	    if (isset($query['color']))
       {
                $segments[] = $query['color'];
                unset($query['color']);
       };
	     if (isset($query['neck']))
       {
                $segments[] = $query['neck'];
                unset($query['neck']);
       };
	     if (isset($query['sleeve']))
       {
                $segments[] = $query['sleeve'];
                unset($query['sleeve']);
       };
	     if (isset($query['type']))
       {
                $segments[] = $query['type'];
                unset($query['type']);
       };
	     if (isset($query['price_range']))
       {
                $segments[] = $query['price_range'];
                unset($query['price_range']);
       };
	     if (isset($query['shape']))
       {
                $segments[] = $query['shape'];
                unset($query['shape']);
       };
		 unset($query['view']);
		// unset( $query['Itemid']);
		// unset( $query['option']);
		return $segments;
	}

	 function inventoryParseRoute(&$segments)
	{
		 $vars = array();
       $app = JFactory::getApplication();
       $menu = $app->getMenu();
       $item = $menu->getActive();
       // Count segments
       $count = count($segments);
       // Handle View and Identifier
       switch ($item->query['view'])
       {
               case 'products':
                       
					    $vars['view'] = 'products';
                       $id = explode(':', $segments[$count-1]);
                       $vars['id'] = (int) $id[0];
                       break;
               case 'product':
						$id = explode(':', $segments[$count-1]);
						$db = JFactory::getDbo();
						$query = $db->getQuery(true)
							->select($db->quoteName('id'))
							->from('#__content')
							->where($db->quoteName('catid') . ' = ' . (int) $vars['category'])
							->where($db->quoteName('alias') . ' = ' . $db->quote($id[1]));
						$db->setQuery($query);
						$cid = $db->loadResult();
                       
                       $vars['id'] = $cid;
                       $vars['view'] = 'product';
                       break;
       }
       return $vars;
	}


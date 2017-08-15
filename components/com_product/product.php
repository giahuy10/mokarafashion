<?php
/**
 * @version    CVS: 1.0.2
 * @package    Com_Product
 * @author     Eddy Nguyen <contact@eddynguyen.com>
 * @copyright  2017 Eddy Nguyen
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Product', JPATH_COMPONENT);
JLoader::register('ProductController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Product');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();

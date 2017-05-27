<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Mokara
 * @author     Eddy Nguyen <email@giahuy10.com>
 * @copyright  2017 Mokara
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Mokara', JPATH_COMPONENT);
JLoader::register('MokaraController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Mokara');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();

<?php
/**
 * @version    CVS: 1.0.2
 * @package    Com_Product
 * @author     Eddy Nguyen <contact@eddynguyen.com>
 * @copyright  2017 Eddy Nguyen
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Item controller class.
 *
 * @since  1.6
 */
class ProductControllerItem extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'items';
		parent::__construct();
	}
}

<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Inventory
 * @author     sugar lead <anjakahuy@gmail.com>
 * @copyright  2017 sugar lead
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Coupon controller class.
 *
 * @since  1.6
 */
class InventoryControllerCoupon extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'coupons';
		parent::__construct();
	}
}

<?php
/**
 * @package         Regular Labs Library
 * @version         17.5.14365
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2017 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Library\Condition;

defined('_JEXEC') or die;

/**
 * Class Form2contentProject
 * @package RegularLabs\Library\Condition
 */
class Form2contentProject
	extends Form2content
{
	public function pass()
	{
		if ($this->request->option != 'com_content' && $this->request->view == 'article')
		{
			return $this->_(false);
		}

		$query = $this->db->getQuery(true)
			->select('c.projectid')
			->from('#__f2c_form AS c')
			->where('c.reference_id = ' . (int) $this->request->id);
		$this->db->setQuery($query);
		$type = $this->db->loadResult();

		$types = $this->makeArray($type);

		return $this->passSimple($types);
	}
}
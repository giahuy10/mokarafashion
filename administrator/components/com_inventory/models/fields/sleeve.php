

<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
 
JFormHelper::loadFieldClass('list');
 
class JFormFieldSleeve extends JFormFieldList {
 
	protected $type = 'Sleeve';
 
	
	public function getOptions() {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('a.fieldparams')->from('`#__fields` AS a')->where('a.name = "loai-tay" ');
		$rows = $db->setQuery($query)->loadResult();
		$color = json_decode($rows);
              foreach ($color->options as $option) {
                    $colors[] = $option->name;
                }
                // Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $colors);
                return $options;
	}
}
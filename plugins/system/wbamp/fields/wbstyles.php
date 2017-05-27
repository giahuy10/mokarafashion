<?php
/**
 * wbAMP - Accelerated Mobile Pages for Joomla!
 *
 * @author       Yannick Gaultier
 * @copyright    (c) Yannick Gaultier - Weeblr llc - 2017
 * @package      wbAmp
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version      1.8.0.647
 * @date        2017-05-02
 */

defined('_JEXEC') or die;

/**
 * Display version and edition information
 *
 */
class JFormFieldWbstyles extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'wbstyles';

	public function getInput()
	{
		return;
	}

	public function getLabel()
	{
		$htmlManager = ShlHtml_Manager::getInstance();
		$document = JFactory::getDocument();

		// bootstrap theme
		$htmlManager->addStylesheet('theme.default');
		$document->addScriptDeclaration(
			"
jQuery(document).ready(function(){
  jQuery('.tab-content').addClass('wbl-theme-default');
  weeblrApp.tips
    .setTipsMode('" . (version_compare(JVERSION, '3.6.1', 'ge') ? 'popover' : 'tips') . "')
    .setupTips();
});
"
		);

		// tooltips replacement
		$document->addStyleSheet($htmlManager->getMediaLink('tips', 'css', array()));
		$document->addScript($htmlManager->getMediaLink('tips', 'js', array()));

		// language loading
		$language = JFactory::getLanguage();
		$language->load('plg_system_wbamp', JPATH_ADMINISTRATOR, null, false, true);

		return '';
	}
}


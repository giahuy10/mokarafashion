<?php
/**
 * SEF component for Joomla!
 * 
 * @package   JoomSEF
 * @version   4.6.10
 * @author    ARTIO s.r.o., http://www.artio.net
 * @copyright Copyright (C) 2017 ARTIO s.r.o. 
 * @license   GNU/GPLv3 http://www.artio.net/license/gnu-general-public-license
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

class SEFModelSEF extends SEFModel
{
    function getFeed()
    {
        $sefConfig = SEFConfig::getConfig();
        
        if (!$sefConfig->artioFeedDisplay) {
            return '';
        }
        
        // Load feed from URL
        $response = SEFTools::PostRequest($sefConfig->artioFeedUrl, null, null, 'get');
        if (!$response || ($response->code != 200)) {
            return JText::_('COM_SEF_ERROR_CONNECTING_TO_RSS_FEED');
        }
        $feed = $response->content;
        
        // Parse XML
        $xml = simplexml_load_string($feed);
        if ($xml === false) {
            return JText::_('COM_SEF_NO_ITEMS_TO_DISPLAY');
        }
        if (count($xml->channel->item) == 0) {
            return JText::_('COM_SEF_NO_ITEMS_TO_DISPLAY');
        }
        
        // Build HTML
        $txt = '';
        foreach ($xml->channel->item as $item)
        {
            $title = (string)$item->title;
            $link = (string)$item->link;
            $desc = (string)$item->description;
            //$date = date('j. F Y', strtotime((string)$item->pubDate));
            //$author = (string)$item->author;
            
            $txt .= '<div class="feed-item">';
            $txt .= '<div class="feed-title"><a href="'.$link.'" target="_blank">'.$title.'</a></div>';
            $txt .= '<div class="feed-text">'.$desc.'</div>';
            $txt .= '</div>';
        }
        
        return $txt;
    }
    
    public function checkLanguagePlugins()
    {
        // Check that the language filter plugin is disabled
        if (JPluginHelper::isEnabled('system', 'languagefilter')) {
            JError::raiseWarning(0, JText::sprintf('COM_SEF_WARNING_LANGUAGEFILTER_ENABLED', '<a href="index.php?option=com_plugins&amp;filter_folder=system">'.JText::_('COM_SEF_PLUG_IN_MANAGER').'</a>'));
        }
    }
    
    public function clearCache($option = null)
    {
        $this->cleanCache($option, 0);
        $this->cleanCache($option, 1);
    }
}

?>
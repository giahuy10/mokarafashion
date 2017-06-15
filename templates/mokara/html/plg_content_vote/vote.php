<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.vote
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Layout variables
 * -----------------
 * @var   string   $context  The context of the content being passed to the plugin
 * @var   object   &$row     The article object
 * @var   object   &$params  The article params
 * @var   integer  $page     The 'page' number
 * @var   array    $parts    The context segments
 * @var   string   $path     Path to this file
 */

$uri = clone JUri::getInstance();
$uri->setVar('hitcount', '0');

$options = array();

for ($i = 1; $i < 6; $i++)
{
	$options[] = JHtml::_('select.option', $i, JText::sprintf('PLG_VOTE_VOTE', $i));
}


?>
<div class="stars">

<form method="post" id="form_vote" action="<?php echo htmlspecialchars($uri->toString(), ENT_COMPAT, 'UTF-8'); ?>" class="form-inline hello">
<?php echo JHtml::_('select.genericlist', $options, 'user_rating', null, 'value', 'text', '5', 'content_vote_' . (int) $row->id); ?>
	<span class="content_vote">
		<?php for ($i = 5; $i > 0; $i--){?>
			 <input value="<?php echo $i?>" class="star star-<?php echo $i?>" id="content_vote_<?php echo $row->id?><?php echo $i?>" type="radio" name="user_rating"/>
				<label class="star star-<?php echo $i?>" for="content_vote_<?php echo $row->id?><?php echo $i?>"></label>
		<?php }?>
		
		
		
		
		&#160;<input class="btn btn-mini" type="submit" name="submit_vote" value="<?php echo JText::_('PLG_VOTE_RATE'); ?>" />
		<input type="hidden" name="task" value="article.vote" />
		<input type="hidden" name="hitcount" value="0" />
		<input type="hidden" name="url" value="<?php echo htmlspecialchars($uri->toString(), ENT_COMPAT, 'UTF-8'); ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</span>
</form>
</div>
<script type='text/javascript'>

 $(document).ready(function() { 
   $('input[name=user_rating]').change(function(){
        $('#form_vote').submit();
   });
  });

</script>
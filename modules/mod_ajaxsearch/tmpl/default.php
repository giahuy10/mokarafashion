<?php

defined('_JEXEC') or die;

// Including fallback code for the placeholder attribute in the search field.
JHtml::_('jquery.framework');
JHtml::_('script', 'system/html5fallback.js', false, true);

// Include module's assets
if ($include_css) {
	JHtml::_('stylesheet', 'modules/mod_ajaxsearch/assets/css/style.css');
}
JHtml::_('script', 'modules/mod_ajaxsearch/assets/js/script.js');

if ($width)
{
	$moduleclass_sfx .= ' ' . 'mod_ajaxsearch' . $module->id;
	$css = 'div.mod_ajaxsearch' . $module->id . ' input[type="search"]{ width:auto; }';
	$doc->addStyleDeclaration($css);
	$width = ' size="' . $width . '"';
} else {
	$width = '';
}

$input = $app->input;
if ($input->get('searchword', '', 'string')!='') {
	$text = $input->get('searchword', '', 'string');
}

// js settings
$js = '
	var asoptions = {
		lower_limit: '.$lower_limit.',
		max_results: '.$max_results.'
	};
	var asstrings = {
		show_all: "'.JText::_('MOD_AJAXSEARCH_SHOW_ALL').'"
	};
';
$doc->addScriptDeclaration($js);

$action_url = 'index.php?option=com_search&view=search';
if ($mitemid) {
	$action_url .= '&Itemid='.$mitemid;
}

?>
<div class="ajax-search<?php echo $moduleclass_sfx ?>">
	<form id="mod-ajaxsearch-form" action="<?php echo JRoute::_($action_url);?>" method="post" class="form-inline">
		<div class="btn-toolbar">
			<div class="btn-group pull-left">
				<input type="search" name="searchword" id="mod-ajaxsearch-searchword" placeholder="<?php echo $label; ?>"<?php echo $width; ?> maxlength="<?php echo $maxlength; ?>" class="inputbox" value="<?php echo $text; ?>" autocomplete="off" onblur="if (this.value=='') this.value='<?php echo $text; ?>';" onfocus="if (this.value=='<?php echo $text; ?>') this.value='';" />
			</div>
			<?php if ($button) : ?>
				<div class="btn-group pull-left hidden-phone">
					<button name="Search" onclick="this.form.submit()" class="btn hasTooltip" title="<?php echo JHtml::tooltipText($button_text);?>"><span class="icon-search"></span></button>
				</div>
			<?php endif; ?>
			<div class="clearfix"></div>
		</div>
		<div id="mod-ajaxsearch-results-box" class="results-box"></div>
		<input type="hidden" name="task" value="search" />
		<input type="hidden" name="limit" value="<?php echo $pagination_limit; ?>" />
	</form>
</div>

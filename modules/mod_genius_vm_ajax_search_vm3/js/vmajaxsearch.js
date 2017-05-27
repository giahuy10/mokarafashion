/**
* @package		mod_genius_vm_ajax_search_vm3.zip
* @copyright  (C) 2015 Mikkel Olsen / Genius WebDesign, https://www.genius-webdesign.com/
* @license		see docs/LICENSE.txt
*
* Joomla 2.5+ Module
*/

function search_vm_ajax_live(el, prods, lang, myid, url, scrollbar, scrollsmoothness, initajaxloadenable, initajaxloadbg, initajaxloadwidth) {
	str = ajop_escape(el.value, myid);
	str = str.replace('&', '-|bsq|-');
	
	var checkinputvalmy = jQuery( ".GeniusAjaxInputMaster #vm_ajax_search_search_str2" + myid ).val();
	
 
	
		if (checkinputvalmy.length == 0) {
		jQuery( ".GeniusAjaxModuleWrap a.GeniusCloseLinkModalPop" ).trigger( "click" );
		jQuery("#Genius_vm_ajax_search_BG").hide();
		
	} else {
	
	
if( !jQuery.trim( jQuery("#vm_ajax_search_results2" + myid + ".res_a_s.geniusGroove").html() ).length || jQuery("#vm_ajax_search_results2" + myid + ".res_a_s.geniusGroove").css('display') == 'none') {

			elload = document.getElementById('vm_ajax_search_search_str2' + myid);

			xload = getX(elload);
			yload = getY(elload) + 40;


if (jQuery(".gasAjaxSpinnerCHK")[0]){
} else if (!jQuery.trim( jQuery("#vm_ajax_search_results2" + myid + ".res_a_s.geniusGroove").html() ).length && initajaxloadenable == 0) {
jQuery('#vm_ajax_search_results2' + myid + '.res_a_s.geniusGroove').prepend('<div class="gasAjaxSpinnerCHK" style="position: absolute;width: 100%; height: 100%;z-index: 9999;background: ' + initajaxloadbg + ';"><div style="position: relative; width: 100%; height: 100%;z-index: 9999; text-align: center; padding-top: 15%;" class="hdnload ajaxspinnersearch"><div class="ajaxspinnersearchcontent"></div></div></div>');
jQuery('#vm_ajax_search_results2' + myid + '.res_a_s.geniusGroove').css({ "border": "none!important", "box-shadow": "6px 6px 6px rgba(0, 0, 0, 0.2)", "top": yload + "px", "left": xload + "px", "min-height": "300px", "width": initajaxloadwidth + "px"});

jQuery('#vm_ajax_search_results2' + myid + '.res_a_s.geniusGroove').fadeIn(300);
jQuery('#Genius_vm_ajax_search_BG').fadeIn(300);

}

if (jQuery.trim( jQuery('#vm_ajax_search_results2' + myid + '.res_a_s.geniusGroove').html() ).length) {
jQuery("#vm_ajax_search_results2" + myid + ".res_a_s.geniusGroove").fadeIn(300);
jQuery('#Genius_vm_ajax_search_BG.geniusbgol' + myid).fadeIn(300);
}

}

	
	
	
	if (search_timer[myid] != null) {
		clearInterval(search_timer[myid]);
	}
	query = "&keyword=" + str + "&prods=" + prods + "&lang=" + lang + "&myid=" + myid;

	if (query == op_last_request) {

		var res = document.getElementById("vm_ajax_search_results2" + myid);
		if (res.style.display == 'none') {

			if ((typeof jQuery != 'undefined') && (typeof jQuery.fx != 'undefined'))
				jQuery('#vm_ajax_search_results2' + myid + ', #Genius_vm_ajax_search_BG.geniusbgol' + myid).fadeIn(300, function () { ;
				});
			else
				res.style.display = 'block';

		} else
			res.style.display = 'block';

		return true;
	} else {
		op_last_request = query;

	}
	if (prods == null)
		prods = 5;


	if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} else { // code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange = function () {

		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		
		
		var checkinputvalmyInside = jQuery( ".GeniusAjaxInputMaster #vm_ajax_search_search_str2" + myid ).val();
		
		if (checkinputvalmyInside.length > 0) {
		jQuery('#Genius_vm_ajax_search_BG.geniusbgol' + myid).fadeIn(300);
	}
		 
		 					
		 					 

			var res = document.getElementById("vm_ajax_search_results2" + myid);

			el = document.getElementById('vm_ajax_search_search_str2' + myid);

			x = getX(el);
			y = getY(el);

			res.style.left = x + 'px';
			res.style.top = y + 40 + 'px';
			res.position = 'absolute';

			res.innerHTML = xmlhttp.responseText;

			op_active_el = res;
			op_active_row = document.getElementById(res.id + '_0');
			op_active_row_n = 0;
			setActive(op_active_row, op_active_row_n);

			if (res.style.display == 'none') {
				if ((typeof jQuery != 'undefined') && (typeof jQuery.fx != 'undefined'))
					jQuery('#vm_ajax_search_results2' + myid + ', #Genius_vm_ajax_search_BG.geniusbgol' + myid).fadeIn(300, function () { ;
					});
				else
					res.style.display = 'block';

			} else
				res.style.display = 'block';

jQuery('.searchwrapperajax .hdnload').remove();


var chkwindowswidth = jQuery(window).width();


if (scrollbar == 'none' || chkwindowswidth < 600) { } else {

var scrolltheme = scrollbar;

jQuery("#vm_ajax_search_results2" + myid + " .GeniusProductsMasterWrapper .innerGeniusDivResults").mCustomScrollbar({
    theme:scrolltheme,
    scrollButtons:{ enable: true },
    scrollInertia: scrollsmoothness
});

jQuery("#vm_ajax_search_results2" + myid + " .GeniusCatsManufsMasterWrapperprblock .innerGeniusDivResults").mCustomScrollbar({
    theme:scrolltheme,
    scrollInertia: scrollsmoothness
});

}
}

else {
			if(jQuery('.searchwrapperajax .hdnload').length ==0){ 
			jQuery('#vm_ajax_search_results2' + myid + '.res_a_s.geniusGroove').prepend('<div style="position: absolute;width: 100%; height: 100%;z-index: 9999;"><div style="position: relative; width: 100%; height: 100%;z-index: 9999; text-align: center; padding-top: 15%;" class="hdnload ajaxspinnersearch"><div class="ajaxspinnersearchcontent"></div></div></div>');
			}
			
			}
			
	 
	}

	// "/modules/mod_vm_ajax_search/ajax/index.php"
	xmlhttp.open("POST", url, true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	xmlhttp.send(query);

} 

}

function getRow(id) {
	ida = id.split('_');
	return ida[ida.length - 1];

}

function getModuleId(id) {
	ida = id.split('_');
	if (ida.length > 2)
		return ida[ida.length - 3];
	else
		return 0;
}

function op_hoverme(el) {
	/*
	var d = document.getElementsByName('op_ajax_results');
	for (var i = 0; i<d.length; i++){
	if (d[i].style.display != 'none'){
	if (op_active_row_n != getRow(d[i].id)){
	op_active_el = d[i];
	op_active_row = document.getElementById(d[i].id+'_0');
	op_active_row_n = 0;
	}
	else{
	op_active_row = document.getElementById(d[i].id+'_'+op_active_row_n);
	if (op_active_row != null)
	op_active_row.style.backgroundColor = 'white';
	}
	break;
	}
	}
	if (op_active_el == null) return true;
	 */
	setActive(el);
}

// el is element of the row
function setActive(el, rown) {
	if (rown == null) {
		rown = getRow(el.id);
	}

	if (el == null)
		return;
	if (op_active_row != null && (el != op_active_row)) {
		// restore the original color
		//c = el.getAttribute('savedcolor');
		//if (c != null)
		//el.backgroundColor = c;
		op_active_row.className = op_active_row.className.split(' selectedRow').join('');

		//op_active_row.style.backgroundColor = 'white';
	}
	op_active_row = el;

	if (rown != null) {
		op_active_row_n = rown;
	} else {

		ida = el.id.split('_');
		op_active_row_n = ida[ida.length - 1];
	}

	if ((el.getAttribute('savedcolor') == null) || (el.getAttribute('savedcolor') == ''))
		el.setAttribute('savedcolor', el.style.backgroundColor);

	c = el.getAttribute('savedcolor');

	//el.style.backgroundColor = el.savedcolor;
	//el.focus();
	if (el.className.indexOf('selectedRow') <= 0)
		el.className += ' selectedRow';

	op_active_row = el;

}

function getOffset(el) {
	var _x = 0;
	var _y = 0;
	while (el && !isNaN(el.offsetLeft) && !isNaN(el.offsetTop)) {
		_x += el.offsetLeft - el.scrollLeft;
		_y += el.offsetTop - el.scrollTop;
		el = el.offsetParent;
	}
	return {
		top : _y,
		left : _x
	};
}

function hide_now(myid) {
	// last check
	el = document.getElementById('vm_ajax_search_search_str2' + myid);
	if (search_has_focus != null)
		if (!search_has_focus[myid]) {
			if (typeof jQuery != 'undefined') {
				//jQuery('#vm_ajax_search_results2' + myid).fadeOut('slow', function () { ;
			//	});
			} else{
				//document.getElementById('vm_ajax_search_results2' + myid).style.display = 'none';
			}
		}
}



function op_processCmd(cmd, value, id, row) {
	if (cmd == 'href')
		document.location = value;
	return false;
}




function getY(oElement) {
	var iReturnValue = 0;
	while (oElement != null) {
		iReturnValue += oElement.offsetTop;
		oElement = oElement.offsetParent;
	}
	return iReturnValue;
}

function getX(oElement) {
	var iReturnValue = 0;
	while (oElement != null) {
		iReturnValue += oElement.offsetLeft;
		oElement = oElement.offsetParent;
	}
	return iReturnValue;
}

function ajop_escape(str, myid) {

	//var x1 = document.getElementById('results_re_2'+myid);

	//if (x1 == null || (typeof x1 == 'undefined')) str = '';
	if ((typeof(str) != 'undefined') && (str != null)) {
		x = str.split("&").join("%26");
		x = str.split(" ").join("%20");

		return x;
	} else {

		return "";
	}
	return "";
}

function aj_redirect(id) {

	x = document.getElementById(id);
	if (x != null) {
		if (x.href != null) {
			window.location = x.href;

		} else {}
	}

}


function serc(){
	jQuery('.res_a_s, #Genius_vm_ajax_search_BG').fadeOut('fast');
}
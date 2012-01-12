// name: tofslaForms
// description: tofslaForms JavaScript plug-in, customizes form control elements, uses jQuery Library
// compatibility: works in IE6, IE7, IE8, Mozilla Firefox 3.0+, Opera 10.0+, Google Chrome 4+, Safari 3.1+
// version: 1.1
// copyright: Andrew Krook (2010)
// mailto: jakarta555@gmail.com

/** Feel free to use/modify tofslaForms for commercial and non-commercial purposes, but please, leave the copyright intact. **/
/** If you like the software, consider a donation to tofslaForms. **/


/*
/*~---------------------~*/
//HELP:
/*~---------------------~*/
/*

= = = = = = = = = = = = = = = = = = = = = = =
TO USE THE PLUG-IN: 

1) Place in the head of your HTML:

<link href="tofsla_forms.css" rel="stylesheet" type="text/css" />
<script src="jquery-1.4.4.min.js" type="text/javascript" ></script>
<script src="tofsla_forms.js" type="text/javascript"></script>

2) And put the 3 files above in the folder where your HTML resides.

Of course, you can choose to put the 3 files to other folders but make sure to change paths to them.
= = = = = = = = = = = = = = = = = = = = = = =


	1) You may define which form controls get styled with easy global variables:
		var please_StyleTextareas = true;
		var please_StyleSelects = true;
		etc
		(see below)
		
	2) Run this plugin manually when for example:
		a)  New, unstyled form elements appear on page (via AJAX for instance) and you want to style them too.
		b)  A script (not a user) changes checked state of radios/checkboxes, or selects a new option of a <select>, or changes disabled state of controls. Starting the plugin one more time will update styled controls' look.
		
		To run the plugin manually call TofslaFormsStart()
	
	3) You may prevent form controls from being styled by adding "dontTofsla" class to them.
	4) Controls with "display: none" are not customized.
	5) Styled selects pass "onchange" to their invisible<select>s.
	6) Textareas, input texts and input passwords get "tfFocus" class on focus.
	7) Buttons (both <button type='button'></button> and input buttons) get "tfHover" class on hover and "tfActive" on activation.
	8) Disabled controls get "tfDisabled" class.
	9) Default "please_CalculateSelectsWidth = true" is helpful on sites with numerous selects having different widths from page to page. It will dress up selects maintaining their original widths automatically. But if you run into an issue with <selects> -- try setting the value to "false".
	
*/


//*********** GLOBAL VARIABLES ***********//
//-- feel free to change these global variables' values to false --//
	
	var please_StyleInputTextsAndPasswords = true;
	var please_StyleTextareas = true;
	var please_StyleSelects = false;
	var please_StyleSelectsMultiple = false;
	var please_StyleCheckboxes = true;
	var please_StyleRadios = true;
	var please_StyleButtonButtons = true;
	var please_StyleInputButtons = true;
	
	var please_CalculateSelectsWidth = false; // If set to "true", automatically calculates selects' inner wrapping widths. Decreases CSS work.
	
//*********** GLOBAL VARIABLES ***********//


var formControls = {

	"input:radio": [

		 {"nodeType": "container", // sibling, container, child, empty string
		 	"siblingPosition": "", // before, after, empty string
			"nodeTagName": "span",
			"nodeClass": "tfRadioWrapper",
			"nodeRelativeTo": "control"
		 },

		 {"nodeType": "sibling",
		 	"siblingPosition": "before",
			"nodeTagName": "a",
			"nodeClass": "tfRadio",
			"nodeRelativeTo": "control",
			"additionalExec": "polishRadio()",
			"attachEventListener": "listenRadio()"
		 }

	],


	"input:checkbox": [

		 {"nodeType": "container",
		 	"siblingPosition": "",
			"nodeTagName": "span",
			"nodeClass": "tfCheckboxWrapper",
			"nodeRelativeTo": "control"
		 },

		 {"nodeType": "sibling",
		 	"siblingPosition": "before",
			"nodeTagName": "a",
			"nodeClass": "tfCheckbox",
			"nodeRelativeTo": "control",
			"additionalExec": "polishCheckbox()",
			"attachEventListener": "listenCheckbox()"
		 }

	],

	
	"input:text": [

		 {"nodeType": "container",
		 	"siblingPosition": "",
			"nodeTagName": "div",
			"nodeClass": "tfInputWrapper",
			"nodeRelativeTo": "control"			
		 },

		 {"nodeType": "container",
		 	"siblingPosition": "",
			"nodeTagName": "div",
			"nodeClass": "tfInputInner",
			"nodeRelativeTo": "control",
			"decorativeClassesAttach": "decorateTexts()"
		 }

	],


	"input:password": [
		 {
			"hasAlias": "yes",
			"aliasName": "input:text",
			"decorativeClassesAttach": "decorateTexts()"
		 }
	],


	"textarea": [

		 {"nodeType": "container",
		 	"siblingPosition": "",
			"nodeTagName": "div",
			"nodeClass": "tfTextAreaWrapper",
			"nodeRelativeTo": "control"			
		 },
		 
		 {"nodeType": "sibling",
		 	"siblingPosition": "after",
			"nodeTagName": "div",
			"nodeClass": "tfTextAreaBottom",
			"nodeRelativeTo": "control"			
		 },

		 {"nodeType": "child",
		 	"siblingPosition": "",
			"nodeTagName": "div",
			"nodeClass": "",
			"nodeRelativeTo": "previous"
		 },	

		 {"nodeType": "container",
		 	"siblingPosition": "",
			"nodeTagName": "div",
			"nodeClass": "tfTextAreaInnerWrapper",
			"nodeRelativeTo": "control"			
		 },

		 {"nodeType": "sibling",
		 	"siblingPosition": "before",
			"nodeTagName": "div",
			"nodeClass": "tfTextAreaTop",
			"nodeRelativeTo": "control",
			"decorativeClassesAttach": "decorateTexts()"			
		 }
		 
	],


	"button:button": [

		 {"nodeType": "child",
		 	"siblingPosition": "",
			"nodeTagName": "span",
			"nodeClass": "",
			"nodeRelativeTo": "control"
		 },

		 {"nodeType": "child",
		 	"siblingPosition": "",
			"nodeTagName": "span",
			"nodeClass": "",
			"nodeRelativeTo": "previous",
			"additionalExec": "polishButtonButton()"
		 }

	],

	
	"button:submit": [
		 {
			"hasAlias": "yes",
			"aliasName": "button:button",
			"additionalExec": "polishButtonButton()"
		 }
	],

	
	"button:reset": [
		 {
			"hasAlias": "yes",
			"aliasName": "button:button",
			"additionalExec": "polishButtonButton()"
		 }
	],

	
	"select[multiple]": [
		 {"nodeType": "container",
		 	"siblingPosition": "",
			"nodeTagName": "div",
			"nodeClass": "tfSelectMultipleWrapper",
			"nodeRelativeTo": "control"			
		 },
		 
		 {"nodeType": "sibling",
		 	"siblingPosition": "after",
			"nodeTagName": "div",
			"nodeClass": "tfSelectMultipleBottom",
			"nodeRelativeTo": "control"			
		 },

		 {"nodeType": "child",
		 	"siblingPosition": "",
			"nodeTagName": "div",
			"nodeClass": "",
			"nodeRelativeTo": "previous"
		 },	

		 {"nodeType": "container",
		 	"siblingPosition": "",
			"nodeTagName": "div",
			"nodeClass": "tfSelectMultipleInnerWrapper",
			"nodeRelativeTo": "control"			
		 },

		 {"nodeType": "sibling",
		 	"siblingPosition": "before",
			"nodeTagName": "div",
			"nodeClass": "tfSelectMultipleTop",
			"nodeRelativeTo": "control"			
		 }

	],

	
	"select": [

		 {"nodeType": "container",
		 	"siblingPosition": "",
			"nodeTagName": "div",
			"nodeClass": "tfSelectWrapper",
			"nodeRelativeTo": "control"
		 },

		 {"nodeType": "child",
		 	"siblingPosition": "",
			"nodeTagName": "div",
			"nodeClass": "",
			"nodeRelativeTo": "previous"			
		 },

		 {"nodeType": "child",
		 	"siblingPosition": "",
			"nodeTagName": "span",
			"nodeClass": "",
			"nodeRelativeTo": "previous"			
		 },
		 
		 {"nodeType": "sibling",
		 	"siblingPosition": "after",
			"nodeTagName": "a",
			"nodeClass": "tfSelectOpen",
			"nodeRelativeTo": "previous"			
		 },
		 
		 {"nodeType": "sibling",
		 	"siblingPosition": "before",
			"nodeTagName": "ul",
			"nodeClass": "",
			"nodeRelativeTo": "control",
			"additionalExec": "polishSelect()",
			"attachEventListener": "listenSelect()"
		 }		 
	],


	"input:button": [

		 {"nodeType": "",
		 	"siblingPosition": "",
			"nodeTagName": "",
			"nodeClass": "",
			"nodeRelativeTo": "",
			"additionalExec": "polishInputButton()"
		 }

	],


	"input:submit": [

		{
			"hasAlias": "yes",
			"aliasName": "input:button",
			"additionalExec": "polishInputButton()"
		}


	],	

	
	"input:reset": [
		{
			"hasAlias": "yes",
			"aliasName": "input:button",
			"additionalExec": "polishInputButton()"
		}
	]

};


////Executes the function when the DOM is ready to be used.
//$(function(){
//	// Document is ready
//	TofslaFormsStart();
//});


function TofslaFormsStart()
{

	if(please_StyleRadios == true)
		StyleControls('input:radio');

	if(please_StyleCheckboxes == true)
		StyleControls('input:checkbox');

	if(please_StyleInputTextsAndPasswords == true)
	{
		StyleControls('input:text');
		StyleControls('input:password');
	}

	if(please_StyleTextareas == true)
		StyleControls('textarea');

	if(please_StyleButtonButtons == true)
	{
		StyleControls('button:button');
		StyleControls('button:reset');
		StyleControls('button:submit');		
	}

	//IE6, IE7, and currently Opera do not permit styling multiple selects properly
	if( !$.browser.opera && IEversion() != 6 && IEversion() != 7)
	{
		if(please_StyleSelectsMultiple == true)
			StyleControls("select[multiple]");
	}

	if(please_StyleSelects == true)
		StyleControls("select");

	if(please_StyleInputButtons == true)
	{
		StyleControls('input:button');
		StyleControls('input:reset');
		StyleControls('input:submit');
	}
	
	UpdateDisabledState();
	UpdateCheckedStateAndChoice();
	listenResets();
	decorateButtons();
}


var g_previouslyAddedNode;
var g_currentControl;

function StyleControls(controlToFind)
{

	var controlName;
	
	if(formControls[controlToFind][0].hasAlias == "yes")
	{
		controlName = formControls[controlToFind][0].aliasName;	
	}
	else
		controlName = controlToFind;

	
	var iterateTill;
	
	$(controlToFind).each(function(index, control ){
	
		iterateTill = formControls[controlName].length;
		
		if(controlToFind == 'select')
		{
			if(control.getAttribute('multiple'))
				iterateTill = 0;
		}
		
		for(var i = 0; i < iterateTill; i++)
		{
			
			if( IsStylingAllowed($(control)) )
			{

				g_currentControl = $(control);
				
				var nodeTagName = formControls[controlName][i].nodeTagName;
				var nodeClass = formControls[controlName][i].nodeClass;
				var nodeHTML;
				
				if(nodeClass == "")
					nodeHTML = '<' +  nodeTagName + '></' + nodeTagName +'>';				
				else
					nodeHTML = '<' +  nodeTagName + ' class="' + nodeClass + '"></' + nodeTagName +'>';
				
				
				var nodeToManipulate;
				
				if( formControls[controlName][i].nodeRelativeTo == 'control' )
					nodeToManipulate = $(control);
				else
				{
					nodeToManipulate = $(g_previouslyAddedNode);
				}
				
				
				if( formControls[controlName][i].nodeType == 'container')
				{
					nodeToManipulate.wrap( nodeHTML );
					g_previouslyAddedNode = nodeToManipulate.parent();
				}
				
				if( formControls[controlName][i].nodeType == 'sibling')
				{
					if(formControls[controlName][i].siblingPosition == 'before')
					{
						nodeToManipulate.before(nodeHTML);
						g_previouslyAddedNode = nodeToManipulate.prev();
					}

					if(formControls[controlName][i].siblingPosition == 'after')
					{
						nodeToManipulate.after(nodeHTML);
						g_previouslyAddedNode = nodeToManipulate.next();
					}
				}
				
				if( formControls[controlName][i].nodeType == 'child')
				{
					nodeToManipulate.prepend( nodeHTML );
					g_previouslyAddedNode = nodeToManipulate.children()[0];
				}
				

				if( i == 0 )
				{
					if(controlName.indexOf('button') == -1)
					{
						$(g_previouslyAddedNode).addClass("tofslaControl");
					}
					else
						$(control).addClass("tofslaControl");
				}
				

				var originalControl;
				
				if(controlToFind != controlName)
					originalControl = formControls[controlToFind][0];
				else
					originalControl = formControls[controlName][i];					
				
				if(originalControl.additionalExec)
				{
					var additionalExec = new Function(originalControl.additionalExec);
					additionalExec();
				}
				
					
				if(originalControl.attachEventListener)
				{
					var attachEventListener = new Function(originalControl.attachEventListener);
					attachEventListener();
				}
				

				if(originalControl.decorativeClassesAttach)
				{
					var decorativeClassesAttach = new Function(originalControl.decorativeClassesAttach);
					decorativeClassesAttach();
				}
				
				
			}//end if

		}//end for
	
		$(control).addClass("tofslaApplied");
	
	});//end each
	
}


function IsStylingAllowed(control)
{

	if( (control.hasClass('tofslaApplied') == false) && (control.css('display') != 'none') && (control.hasClass('dontTofsla') == false) )
	return true;
	
	return false;
}


var g_SelectIndex = 0;
/********************************/
		/* POLISHES */
/********************************/
function polishSelect()
{
	var optionsHTML = '';
	
	RemCurrentSelectWidth();
	g_currentControl.addClass('tfHidden');
	$(g_currentControl[0].parentNode).css('zIndex', 100-g_SelectIndex);
	g_SelectIndex++;
	
	
	$('option', g_currentControl).each(function(index, node){
		optionsHTML	= optionsHTML + '<li><a href="#" index="'+ index +'">' + $(node).html() + '</a></li>';
	});

	var ul = $('ul', g_currentControl.parent() )[0];
	$(ul).prepend ( $(optionsHTML) );
	$(ul).css('display', 'none');
	
	if(please_CalculateSelectsWidth == true)
		setSelectsCalculatedWidth();
		
}


function polishButtonButton()
{
	if(g_currentControl.text() != "")
	{
		var buttonText = g_currentControl.text();
		$(g_currentControl[0].childNodes[1]).replaceWith('');
		g_currentControl[0].childNodes[0].childNodes[0].innerHTML = buttonText;
	}
	
	g_currentControl.addClass("addDecorateButton");
	
	if(g_currentControl[0].type == "reset")
	{
		g_currentControl.addClass('addResetListener')
	}
}


function polishInputButton()
{

	var currentControl = g_currentControl[0];

	var leftSide = buttonHTML = '<button id="'+ currentControl.id +'" name="'+ currentControl.name +'" type="'+ currentControl.type +'" class="';
	var rightSide = '><span><span>'+currentControl.getAttribute('value') +'</span></span></button>';
	var disabledCode = ' disabled="disabled"';

	
	if(!currentControl.disabled)
		disabledCode = '';


	if(currentControl.type != 'reset')
		classCode = currentControl.className + ' addDecorateButton' + ' tofslaApplied' + '"';
	else
		classCode = currentControl.className + ' addDecorateButton' + ' tofslaApplied' + ' addResetListener' + '"';


	buttonHTML = leftSide + classCode + disabledCode + rightSide;

	g_currentControl.replaceWith(buttonHTML);

}


function polishRadio()
{
	var currentControl = g_currentControl[0];
	var previouslyAddedNode = g_previouslyAddedNode[0];
	
	previouslyAddedNode.setAttribute('rel', currentControl.name)
}


function polishCheckbox()
{
	var currentControl = g_currentControl[0];
}


function RemCurrentSelectWidth()
{
	var currentControl = g_currentControl[0];
	RemCurrentSelectWidth.width = currentControl.offsetWidth;
	
	if(RemCurrentSelectWidth.width == 0) //equal to 0 when the the form it belongs to is not visible
	{
		RemCurrentSelectWidth.width = parseInt(currentControl.css('width') );
	
		if(RemCurrentSelectWidth.width == 0)
		{
			RemCurrentSelectWidth.width = 100; //let it be 100 px wide by default
		}
	}
	
}


function setSelectsCalculatedWidth()
{
	var currentControl = g_currentControl[0];
	var mainWrapper = currentControl.parentNode;

	var selectSpan = mainWrapper.childNodes[0].childNodes[0]; // <span>
	var SelectAwidth = parseInt( $(mainWrapper.childNodes[0].childNodes[1]).css('width') ); // <a>
	var SelectSpanWidth = RemCurrentSelectWidth.width - SelectAwidth - getHorizontalBorderPadding(selectSpan);


	$(mainWrapper).css('width', RemCurrentSelectWidth.width + 'px');
	$(selectSpan).css('width', SelectSpanWidth + 'px');
	

	var ul = $('ul', mainWrapper);
	ul.css('width', RemCurrentSelectWidth.width - getHorizontalBorderPadding(ul) + 'px');
	
}


function getHorizontalBorderPadding(node)
{

	node = $(node);
			
	var HorizontalBorderPadding;
	var bordL, bordR, padL, padR;
	bordL = 0; bordR = 0; padL = 0; padR = 0;
	
	var pattern = /[0-9]+/;
	
	if(IEversion() > -1)//this is an IE
	{
		if(pattern.test(parseInt(node.css('border-left-width')) ) )
		{
			bordL = parseInt(node.css('border-left-width'));
		}
		if(pattern.test(parseInt(node.css('border-right-width')) ) )
		{
			bordR = parseInt(node.css('border-right-width'));
		}
		if(pattern.test(parseInt(node.css('padding-left')) ) )
		{
			padL = parseInt(node.css('padding-left'));
		}
		if(pattern.test(parseInt(node.css('padding-right')) ) )
		{
			padR = parseInt(node.css('padding-right'));
		}
		
		HorizontalBorderPadding = bordL + bordR + padL + padR;
	}
	else
	{
		HorizontalBorderPadding = parseInt(node.css('border-left-width')) + parseInt(node.css('border-right-width')) + parseInt(node.css('padding-left')) + parseInt(node.css('padding-right'));
	}
		
		return HorizontalBorderPadding;

}


function UpdateDisabledState()
{

	$('.tofslaControl').each(function(index, node ){
		
		if(node.tagName.toLowerCase() != "button")
		{
			if($('.tofslaApplied', node)[0].disabled)
				$(node).addClass('tfDisabled');
			else
				$(node.parentNode).removeClass('tfDisabled');
		}
		else
		{
			if(node.disabled)
				$(node).addClass('tfDisabled');
			else
				$(node).removeClass('tfDisabled');
		}


	});
}


function UpdateCheckedStateAndChoice()
{
	if(please_StyleSelects == true) resetSelects();
	if(please_StyleCheckboxes == true) resetCheckboxes();
	if(please_StyleRadios == true)	resetRadios();
}


function resetCheckboxes()
{
	$('.tfCheckboxWrapper').each(function(index, node){

		var a = node.childNodes[0];
		var input = node.childNodes[1];

		$(a).removeClass('tfChecked');

		if(input.checked)
			$(a).addClass('tfChecked');

	});	
}


function resetRadios()
{
	$('.tfRadioWrapper').each(function(index, node){

		var a = node.childNodes[0];
		var input = node.childNodes[1];
		
		$(a).removeClass('tfChecked');
		$(input).removeClass('tfChecked');
				
		if(input.checked)
			$(a).addClass('tfChecked');
	});	
}


function resetSelects()
{
	var seLect, selIndex, ul, selectLink;


	$('.tfSelectWrapper').each(function(index, node){

		seLect = node.childNodes[2];

		if( !seLect.getAttribute('multiple') )
		{
			selIndex = (seLect.selectedIndex < 0) ? 0 : seLect.selectedIndex;

			ul = node.childNodes[1];
			
			selectLink = $('a[index="' + selIndex + '"]', ul)[0];

			handleSelectsClick(node, selectLink, seLect, ul);
		}

	});

}


function handleSelectsClick(wrapper, thisLink, seLect, ul)
{

	if(!thisLink) // no select options
	{
		return;
	}
			
		
	$('span', wrapper)[0].innerHTML = thisLink.innerHTML;
	
	$('a.selected', wrapper).each( function(i, linky){
		$(linky).removeClass('selected');		
	});

	$(thisLink).addClass('selected');
	
	$(ul).hide();

		
	if (seLect.selectedIndex != thisLink.getAttribute('index') )
	{
	
		seLect.selectedIndex = thisLink.getAttribute('index');

		if(IEversion() > -1)
		{
			var evt = document.createEventObject();
			seLect.fireEvent('onchange', evt)
		}
		else
		{
			var evt = document.createEvent('HTMLEvents');
			evt.initEvent('change', 'true', 'false');
			seLect.dispatchEvent(evt);
		}

	}

}


function listenCheckbox()
{
	var a = g_previouslyAddedNode;
	var checkbox = g_currentControl;
	
	a.bind("mousedown", function(event) {
		this.nextSibling.click();
	});


	checkbox.bind('click', function(event) {
		changeVisualCheck(this) 
	});

	var changeVisualCheck = function(check){

		if(!check.disabled)
		{
			if (check.checked){

				$(check.previousSibling).addClass('tfChecked');
			}
			else {
				$(check.previousSibling).removeClass('tfChecked');
			}
			
		}

		
	};

}


function listenRadio()
{
	var radio = g_currentControl;
	var a = g_previouslyAddedNode;

	a.bind("mousedown", function(event) {
		this.nextSibling.click();
	});
	

	radio.bind('click', function(event) {
		changeVisualRadio(this) 
	});

	var changeVisualRadio = function(rd){

		if(!rd.disabled)
		{
			/* uncheck all others with the same name */
			$('a[rel="' + rd.getAttribute('name') +'"]').each(function(index, node){
				
					$(node).removeClass('tfChecked');
				
			});
			
			$(rd.previousSibling).addClass('tfChecked');

		}

	};

	
}


function listenResets()
{
	$('button:reset').each(function(index, node){

		if( $(node).hasClass('tofslaApplied') && $(node).hasClass('addResetListener') )
		{
			$(node).removeClass('addResetListener');
			
			$(node).bind("click", function(event) {
				
				var action = function(){ UpdateCheckedStateAndChoice(); };
				window.setTimeout(action, 100);
				
			});
			
		}
	});
	
}


listenSelect.isDocumentListeningEnabled = false;
function listenSelect()
{
	var seLect = g_currentControl[0];
	var ul = g_previouslyAddedNode[0];
	var wrapper = g_currentControl[0].parentNode;

	/* Hide the ul and add click handler to the a */
	$('a', ul).each(function(i, linky){

		$(linky).bind('mousedown', function(event) {
			handleSelectsClick (wrapper, this, seLect, ul);
		});
		
		
		if(IEversion() > -1) // IE
		{
			$(linky).bind('mouseover', function(event) {
				$(linky).addClass('hover');
			});
			
			$(linky).bind('mouseout', function(event) {
				$(linky).removeClass('hover');
			});
		}
		
		$(linky).click(function() {
			return false;
		});
		
	});
	
	/* Set the defalut */
	var Link = $( 'a[index="' + seLect.selectedIndex + '"]', ul)[0];
	
	handleSelectsClick (wrapper, Link, seLect, ul);
	
	/* Apply the click handler to the Open */
	$( $('a.tfSelectOpen', wrapper)[0] ).bind('mousedown', function(event) {
		if(!seLect.disabled)
		{
			openOrCloseSelect(wrapper, ul);
		}
	});
	
	
	/* Apply one more click handler to the Open */
	$($('div span', wrapper)[0]).bind('mousedown', function(event) {

		if(!seLect.disabled)
			openOrCloseSelect(wrapper, ul);

	});
	
	
	/* Close Or Open Select */
	var openOrCloseSelect = function(selectWrapper, uL){
		
		if($(uL).css('display') == 'block')
			$(uL).css('display', 'none');
		else
		{
			$(uL).css('display', 'block'); 
			hideAllOpenSelects(selectWrapper);
		}

	};


	/* Hide all open selects */
	var hideAllOpenSelects = function(nodeToLeaveOpen){
		
		$('.tfSelectWrapper').each(function(index, node){
			
			var ulInQuestion = $('ul', node)[0];
			
			if ( ( $(ulInQuestion).css('display') == 'block') && (node != nodeToLeaveOpen) )
			{
				$(ulInQuestion).hide();
			}
		
		});

	};


	/* Check for an external click */
	var checkExternalClick = function(target) {
		
		var parents = $(target).parents();
		var isChildOfStyledSelect = false;

		parents.each(function(index, node){
			if( $(node).hasClass('tfSelectWrapper') )
				isChildOfStyledSelect = true;
		});
		
		if(isChildOfStyledSelect == false)
			hideAllOpenSelects(document);

	};


	if(!listenSelect.isDocumentListeningEnabled)
	{
		/* Apply document listener */
		$(document).bind("mousedown", function(event) {
			checkExternalClick(event.target);	
		});
		
		listenSelect.isDocumentListeningEnabled = true;
		
	}

}


function decorateTexts()
{
	var nodeToDecorate = $(g_currentControl[0]);

	nodeToDecorate.bind("focus", function(event) {
		$(nodeToDecorate.parents('.tofslaControl')[0]).addClass('tfFocus');
	});

	nodeToDecorate.bind("blur", function(event) {
		$(nodeToDecorate.parents('.tofslaControl')[0]).removeClass('tfFocus');
	});
}


function decorateButtons()
{
	
	$('button').each(function(index, node){
	
		if( $(node).hasClass('tofslaApplied') && $(node).hasClass('addDecorateButton') )
		{
			$(node).bind("mouseenter", function(event) {
				$(this).addClass('tfHover');		
			});
		
			$(node).bind("mouseleave", function(event) {
				$(this).removeClass('tfHover');		
			});
		
			$(node).bind("mousedown", function(event) {
				$(this).addClass('tfActive');
			});
		
			$(node).bind("mouseup", function(event) {
				$(this).removeClass('tfActive');		
			});
			
			$(node).removeClass('addDecorateButton');
		}
	
	});
}


function IEversion()
{
	var version = navigator.appVersion;
	if(version.indexOf("MSIE") != -1)
	{
		var startCut = version.indexOf("MSIE") + 5;
		var endCut = startCut + 1;
		return version.substring(startCut, endCut); 
	}
	else
		return -1;
}

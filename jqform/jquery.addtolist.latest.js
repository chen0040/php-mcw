/*
 * jQuery Add To List Plugin
 * version: 1.0 (2008/05/20)
 * @requires jQuery v1.2.2 or later
 *
 * Examples at: http://pelicansareevil.com/jquery/addtolist/
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * Revision: $Id$
 */
(function($) {

/**
 * addToList() provides the hook in functionality to dynamically add new items to a select list.
 * This functionality also provides a ajax hook that will post the new item data. The response of this ajax hook will generate the value/label of the new item
 * 
 * addToList accepts a single argument which is an options object.
 * The following attributes are supported in the options: 
 * 
 *  triggerValue:	The value of a <option> item that will trigger the form-open event
 *  
 *  submitURL:		The url 
 *  
 *  form: 			the jquery selector element that will link to the popup form
 *  
 *  dataType: 		The ajax dataType
 *  
 *  dataHandler:	function callback that will hook into the ajax reponse. This callback function will
 *  				generate a object that contains a value & label properties. These properties will help create the new 
 *  				<option> tag
 *  
 *  insertPosition:	Defines where the new item (if available) will be inserted into the existing list of options. Values can be 'first'
 *  				, 'last', 'sort' or numerical fixed position
 * 
 * @name addToList
 * @type jQuery
 * @param options  object literal containing options which control the addtolist functionaltiy
 * @cat Plugins/Form
 * @return jQuery
 */
$.fn.addToList = function(options) {
	var that = $(this);
	options = $.extend({
		triggerValue: -1,
		submitURL: false, // string url value, false disables ajax
		form: '',
		dataType: 'json',
		dataHandler: false,
		insertPosition: 'last' // available options: first, last, sort and numerical index position
	}, options || {});
	
	// dataHandler MUST be a function
	if(typeof options.dataHandler != 'function') return this; 
	
	// hide the form if its visisble
	$(options.form+':visible').hide();
	
	// add a 'trigger' class to any trigger options
	$('option[@value="'+options.triggerValue+'"]', this).addClass('trigger');
	
	// capture the escape key
	$(document).keydown(function(e) {
		// hook into the keystrokes, if escape is pressed hide the form
		if(e.which == 27 && $(options.form+':visible').size() > 0) {
			that.trigger('form-close');
		}
	});
	
	//prepare the form
	$(options.form+'>form').submit(function() {
		// submit the the form, ajax style
		$(this).ajaxSubmit({
			success: processAjax,
			dataType: options.dataType
		});
		// cancel the default form submit action
		return false;
	});
	var current;
	var tmp;
	
	return this.each(function() {
		var width = $(this).width();
		var height = $(this).height();
		var pos = $(this).offset();
		
		var me = $(this);
		
		me.bind('form-open', function() {
			frm  = $(options.form);
			if($(options.form+':visible').size() == 0) {
				// copy the form to the body, position then show it
				frm.appendTo('body').addClass('addtolist-form').css({
					width: width,
					top: pos.top + height,
					left: pos.left
				}).show();
			}
			// (re)focus the first input element
			frm.find('input:text:first').get(0).focus();
			me.attr('form-open', true);
		});
		
		// called when the form has been submitted or cancelled.
		me.bind('form-close', function() {
			if(me.attr('form-open') == 'true') {
				$(options.form+':visible').hide();
				me.attr('form-open', false)
			}
		});
		// when the popup form has been canceled (ie not submitted)
		me.bind('form-cancel', function() {
			if(me.attr('form-open') == 'true') {
				me.trigger('form-close');
				me.get(0).selectedIndex = 0;
			}
		});
		

		
		$(this).change(function() {
			// if the changed value is our trigger value display the form
			if($(this).val() == options.triggerValue) {
				// display the form and position id directly under our select object
				me.trigger('form-open');
				current = me;
			} else {
				// if the form is open close it
				me.trigger('form-close');
				$(options.form+':visible').hide();
			}
		})
		
	});
	
	/**
	 * Process the ajax success callback
	 * 
	 * @param {Object} result contains the ajax data in the specified format (see dataType)
	 */
	function processAjax(result)
	{
		var newRecord = options.dataHandler(result);
		if(newRecord) {
			that.trigger('form-success', [newRecord]);
			var option = '<option value="'+newRecord.value+'" selected="selected">'+newRecord.label+'</option>';
			// figure out where to insert this new option
			if(options.insertPosition == 'first') {
				current.prepend(option);
			} else if(options.insertPosition == 'last') {
				current.append(option);
			} else {
				// fixed position insert
				if(options.insertPosition == 'sort') {
					// figure out the sort position;
					var pos = 0;
					// loop through each option item
					$('option', tmp).each(function() {
						val = $(this).text();
						if(newRecord.label < val) {
							pos++;
						}
					});
					options.insertPosition = pos;
				}
				$('option:eq('+options.insertPosition+')', current).before(option);
			}
			that.trigger('form-success-inserted', [newRecord]);
		} else {
			that.trigger('form-failure')
		}
		$(options.form+':visible').hide();
	}
};


})(jQuery);
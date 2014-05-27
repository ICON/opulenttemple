var rsmg_img_area_select;
var rsmg_scroll_item;
var rsmg_calculate_thumbnail;

jQuery.noConflict();
jQuery(document).ready(function($){
	rsmg_init_toolbar($);
	rsmg_init_suckerfish($);
	rsmg_init_autocomplete($);
	rsmg_get_items($, false);
	rsmg_init_load_more($);
	rsmg_init_details_buttons($);
	rsmg_init_upload($);
	
	if ($('#rsmg_dialog_no_items').length > 0)
	{
		$('#dialog:ui-dialog').dialog('destroy');
		$('#rsmg_dialog_no_items').dialog({
			height: 400,
			width: 400,
			modal: true,
			draggable: false,
			resizable: false,
			buttons: [
				{
					text: rsmg_get_lang('RSMG_CLOSE'),
					click: function() {
						$(this).dialog('close');
					}
				}
			]
		});
	}
});

// toolbars
function rsmg_init_toolbar($)
{
	// hide edit toolbar
	$('#rsmg_toolbar_edit').addClass('rsmg_hidden');
	
	// a little workaround for the Options button...
	// J! 2.5
	if ($('#toolbar-popup-options').length > 0)
		$('#toolbar-popup-options a span').text($('#toolbar-popup-options a').text());
	// J! 1.5
	if ($('#toolbar-popup-Popup').length > 0)
	{
		li = $('<li>');
		$('#toolbar-popup-Popup a span').text($('#toolbar-popup-Popup a').text());
		$('#toolbar-popup-Popup a').appendTo(li);
		$('#rsmg_toolbar_default ul').append(li);
	}
	
	$("#rsmg_select_all").click(function(e) {
		e.preventDefault();
		$(".rsmg_item").addClass("ui-selected");
		if (e.shiftKey)
			$('#rsmg_select_all').attr('rel', 1);
		else
			$('#rsmg_select_all').attr('rel', 0);
		
		rsmg_show_selected($);
	});	
	
	$("#rsmg_filter").click(function(e) {
		e.preventDefault();
		if ($('#rsmg_filter_text').val().trim().length > 0)
		{
			var text = $('#rsmg_filter_text').val();
			if ($('#rsmg_column ul li a.rsmg_tick').attr('rel') != 'published')
				$('#rsmg_filter_text').val('');
			
			var current_column	 = $('#rsmg_column .rsmg_tick').attr('rel');
			var current_operator = $('#rsmg_operator .rsmg_tick').attr('rel');
			
			var already_columns   = document.getElementsByName('rsmg_columns[]');
			var already_operators = document.getElementsByName('rsmg_operators[]');
			var already_values	  = document.getElementsByName('rsmg_values[]');
			for (var i=0; i<already_columns.length; i++)
			{
				if ($(already_columns[i]).val() == current_column
					&& $(already_operators[i]).val() == current_operator
					&& $(already_values[i]).val() == text)
					return;
			}
			
			var li 			= $('<li>');
			var col 		= $('<span>').html($('#rsmg_column > a').html());
			var operator 	= $('<span>').html($('#rsmg_operator > a').html());
			var value		= $('<strong>').text(text);
			var close 		= $('<a>', {'href': 'javascript: void(0)', 'class': 'rsmg_close'}).click(function(e) {
				e.preventDefault();
				$(this).parent('li').hide('highlight', 500, function() {
					$(this).remove();
					rsmg_get_items($, true);
					
					if ($('#rsmg_filters li').length == 1 && $('#rsmg_clear_filters').length > 0)
						$('#rsmg_clear_filters').remove();
				});				
			});
			col.children('.sf-sub-indicator').remove();
			operator.children('.sf-sub-indicator').remove();
			
			var input_col 		= $('<input>', {'type': 'hidden', 'name': 'rsmg_columns[]', 'value': current_column});
			var input_operator  = $('<input>', {'type': 'hidden', 'name': 'rsmg_operators[]', 'value': current_operator});
			var input_value 	= $('<input>', {'type': 'hidden', 'name': 'rsmg_values[]', 'value': text});

			// do not show "value" for filters that use the published column
			if (current_column == 'published')
			{
				value.text('');
				close.css({
					'margin-left': 0,
					'padding-left': 0,
					'border-left': 0
				});
			}
			
			$('#rsmg_filters').append(li.append(col, operator, value, close, input_col, input_operator, input_value));
			$('#rsmg_clear_filters').remove();
			
			if ($('#rsmg_filters').children().length > 2)
			{
				var clear = $('<li>', {'id': 'rsmg_clear_filters'}).html(rsmg_get_lang('RSMG_CLEAR_ALL_FILTERS')).click(function (e){
					e.preventDefault();
					$('#rsmg_filters').children().hide('highlight', 500, function() { $(this).remove(); rsmg_get_items($, true); });
				});
				$('#rsmg_filters').append(clear);
			}
		}
		
		rsmg_get_items($, true);
	});
	
	// use this to trigger the filtering when enter is pressed and the form is submitted
	$("#rsmg_filter_form").submit(function(e) {
		e.preventDefault();
		$('#rsmg_filter').trigger('click');
	});
	
	// if there are any filters from the session, initialize the clicks
	$('.rsmg_close').click(function(e) {
		e.preventDefault();
		$(this).parent('li').hide('highlight', 500, function() {
			$(this).remove();
			rsmg_get_items($, true);
			if ($('#rsmg_filters li').length == 1 && $('#rsmg_clear_filters').length > 0)
				$('#rsmg_clear_filters').remove();
		});
	});
	
	$('#rsmg_clear_filters').click(function (e){
		e.preventDefault();
		$('#rsmg_filters').children().hide('highlight', 500, function() { $(this).remove(); rsmg_get_items($, true); });
	});
}

// make sortable
function rsmg_init_sortable($)
{
	$("#rsmg_items").removeAttr('style');
	
	// disable if not ordering - no point in ordering something that's order by eg. title
	is_disabled = $('#rsmg_order ul li a.rsmg_tick').attr('rel') == 'ordering' ? false : true;
	
	$("#rsmg_items").sortable({
		'cursor': 'auto',
		'disabled': is_disabled,
		// save ordering
		update: function(e, ui) {
			var cids 		= [];
			var orderings 	= [];
			
			$('input[name="cid[]"]').each(function (index, el) {
				rsmg_sort_array[index].id = $(el).val();
				
				cids.push(rsmg_sort_array[index].id);
				orderings.push(rsmg_sort_array[index].ordering);
			});
			
			if (cids.length > 0)
			{
				// send request to save the ordering
				// because it has changed in the DOM
				$.ajax({
					type: 'POST',
					url: "index.php?option=com_rsmediagallery&controller=items&task=saveorder&format=raw",
					data: {
						'cid[]': cids,
						'ordering[]': orderings
					}
				});
			}
		}
	});
	
	if (is_disabled)
		$("#rsmg_items").addClass('ui-sortable-disabled');
}

// make selectable
function rsmg_init_selectable($)
{
	$("#rsmg_items").bind('mousedown', function (e) {
		if (e.target)
		{
			target = $(e.target);
			// make ctrl-click multiselect possible
			if (e.ctrlKey == true && target != $("#rsmg_items"))
			{
				e.metaKey = true;
			}
			
			// handle bug when edit button is clicked with scrollbar on top
			if (target.hasClass('rsmg_action_button') && $(window).scrollTop() == 0)
			{
				target.trigger('click');
			}
		}
	}).selectable({
		start: function () {
			$('#rsmg_select_all').attr('rel', 0);
		},
		stop: function() {
			rsmg_show_selected($);
		}
	});
}

// make rumble effect available
function rsmg_init_rumble($)
{
	$('.rsmg_item').jrumble();
}

// create suckerfish dropdowns
function rsmg_init_suckerfish($)
{
	$(".rsmg_filter_toolbar > ul").children('li').each(function (i, el) {
		maxwidth = 0;
		
		$(el).children('ul').each(function (j, ul) {
			$(ul).show();
			$(ul).children('li').each (function (k, li) {
				width = $(li).outerWidth();
				
				if (width > maxwidth)
					maxwidth = width;
			});
			$(ul).hide();
		});
		if (maxwidth > 0)
			$(el).css('width', maxwidth + 'px');
	});
	
	$(".rsmg_filter_toolbar ul").superfish();
	
	// filter column
	$('#rsmg_column ul li a').click(function (e){
		e.preventDefault();
		
		var arrow = $('<span>', {'class': 'sf-sub-indicator'}).html(' &#187;');
		
		$(".rsmg_filter_toolbar ul").hideSuperfishUl();
		$('#rsmg_column > a').html($(this).html());
		$('#rsmg_column > a').append(arrow);
		$('#rsmg_column ul li a').removeClass('rsmg_tick');
		$(this).addClass('rsmg_tick');
		
		if ($(this).attr('rel') == 'published')
		{
			$('#rsmg_operator ul li').each(function (index, el){
				// skip "is" and "is not"
				if (index > 1)
				{
					// this means that either "contains" or "does not contain" is ticked
					if ($(el).children('.rsmg_tick').length > 0)
					{
						// if this is "contains" show "is"
						if (index == 2)
							$('#rsmg_operator ul li:nth-child(1) a').trigger('click');
						// if this is "does not contain" show "is not"
						else
							$('#rsmg_operator ul li:nth-child(2) a').trigger('click');
					}
					
					// hide these items - they are not available for the published filter
					$(el).hide();
				}
			});
			// filtering is no longer needed for "published"
			$('#rsmg_filter_text').val('1');
			$('#rsmg_filter_text').hide();
		}
		else
		{
			$('#rsmg_operator ul li').each(function (index, el){
				if (index > 1) $(el).show();
			});
			if ($('#rsmg_filter_text').val() == '1')
				$('#rsmg_filter_text').val('');
			$('#rsmg_filter_text').show();
		}
	});
	
	// operator
	$('#rsmg_operator ul li a').click(function (e){
		e.preventDefault();
		
		var arrow = $('<span>', {'class': 'sf-sub-indicator'}).html(' &#187;');
		
		$(".rsmg_filter_toolbar ul").hideSuperfishUl();
		$('#rsmg_operator > a').html($(this).html());
		$('#rsmg_operator > a').append(arrow);
		$('#rsmg_operator ul li a').removeClass('rsmg_tick');
		$(this).addClass('rsmg_tick');
	});
	
	// ordering
	$('#rsmg_order ul li a').click(function (e){
		e.preventDefault();
		
		var arrow = $('<span>', {'class': 'sf-sub-indicator'}).html(' &#187;');
		
		$(".rsmg_filter_toolbar ul").hideSuperfishUl();
		$('#rsmg_order > a').html($(this).html());
		$('#rsmg_order > a').append(arrow);
		$('#rsmg_order ul li a').removeClass('rsmg_tick');
		$(this).addClass('rsmg_tick');
	});
	
	// direction
	$('#rsmg_direction ul li a').click(function (e){
		e.preventDefault();
		
		var arrow = $('<span>', {'class': 'sf-sub-indicator'}).html(' &#187;');
		
		$(".rsmg_filter_toolbar ul").hideSuperfishUl();
		$('#rsmg_direction > a').html($(this).html());
		$('#rsmg_direction > a').append(arrow);
		$('#rsmg_direction ul li a').removeClass('rsmg_tick');
		$(this).addClass('rsmg_tick');
	});
	
	// limit
	$('#rsmg_limit ul li a').click(function (e){
		e.preventDefault();
		
		var arrow = $('<span>', {'class': 'sf-sub-indicator'}).html(' &#187;');
		
		$(".rsmg_filter_toolbar ul").hideSuperfishUl();
		$('#rsmg_limit > a').html($(this).html());
		$('#rsmg_limit > a').append(arrow);
		$('#rsmg_limit ul li a').removeClass('rsmg_tick');
		$(this).addClass('rsmg_tick');
	});
}

// turn on autocomplete
function rsmg_init_autocomplete($)
{
	$("#rsmg_filter_text").autocomplete({
		source: function (request, response) {
			// min 2 chars
			if (request.term.length < 2)
				return response([]);
			
			var operator = $("#rsmg_operator .rsmg_tick").attr('rel');
			if (operator == 'is')
				operator = 'contains';
			if (operator == 'is_not')
				operator = 'contains_not';
			
			$.ajax({
				type: 'POST',
				url: "index.php?option=com_rsmediagallery&controller=items&task=getsuggestions&format=raw",
				dataType: "json",
				data: {
					'filter_columns': $("#rsmg_column .rsmg_tick").attr('rel'),
					'filter_operators': operator,
					'filter_values': request.term,
					'dont_remember': 1
				},
				success: function(data) {
					response(data);
				}
			});
		}
	});
}

// create tooltips
function rsmg_init_tooltips($)
{
	$("[title]").mbTooltip({
		opacity : .85,
		wait:200,
		cssClass:"default",
		timePerWord:70,
		hasArrow:true,
		hasShadow:true,
		imgPath:"components/com_rsmediagallery/assets/images/",
		ancor:"mouse",
		shadowColor:"black",
		mb_fade:200
	});
}

// create picture toolbar
function rsmg_init_pic_toolbar($)
{
	// click on delete
	$('.rsmg_delete').unbind('click');
	$('.rsmg_delete').click(function() {
		// do not trigger if clicked twice or details already showing
		if ($('#rsmg_item_detail').css('display') == 'block')
		{
			return;
		}
		
		var self    = $(this);
		var parent  = self.parents('li.rsmg_item');
		var cid 	= parent.children('input[name="cid[]"]').val();
		
		rsmg_del_items($, parent, cid, true);
	});
	
	// click on edit
	$('.rsmg_edit').unbind('click');
	$('.rsmg_edit').click(function(e) {
		// prevent default click
		e.preventDefault();
		
		$('#rsmg_items').css('opacity','1');
		$('#rsmg_items').removeClass('rsmg_full_width');
		// IE fix for opacity...
		if ($.browser.msie)
			$('#rsmg_items').css('opacity','100%');
		$('#rsmg_items').css('cursor','default');
		
		// get the parent container
		var parent = $(this).parents('.rsmg_item');
		
		// hide all items
		rsmg_hide_items($);
		
		// keep this item visible
		parent.css('display', 'block');
		
		// do not trigger if clicked twice or details already showing		
		if (parent.find('.rsmg_thumb_container_loading').length > 0 || $('#rsmg_item_detail').css('display') == 'block')
			return;
		
		// add loading icon on thumb
		parent.find('.rsmg_thumb_container').append($('<div>', {'class': 'rsmg_thumb_container_loading'}));
		
		// hide filter toolbar
		rsmg_hide_filter_toolbar($);
		
		// hide load more
		$('#rsmg_load_more').hide('fade', 500);
		
		// show edit toolbar
		rsmg_show_edit_toolbar($);
		
		// get top offset
		var item_top = parent.offset().top;
		rsmg_scroll_item = function() {
			var edit_top = $('#rsmg_item_detail').offset().top;
			if (parseInt(edit_top) <= parseInt(item_top)) {
				if ($(window).scrollTop() > item_top + 20)
				{
					parent.css('margin-top', $(window).scrollTop() - item_top + 20);
				}
				else
					parent.css('margin-top', 0);
			}
		};
		$(window).scroll(rsmg_scroll_item);
		
		$.ajax({
			type: 'POST',
			url: "index.php?option=com_rsmediagallery&controller=items&task=getitem&format=raw",
			data: {
				'cid': parent.children('input[name="cid[]"]').val()
			},
			beforeSend: function() {
				$('#rsmg_detail_title').val('');
				$('#rsmg_detail_description').val('');
				$('#rsmg_detail_url').val('');
				$('#rsmg_detail_tags').val('');
				$('#rsmg_detail_hits').val('');
				$('#rsmg_save_result').html('').removeClass('rsmg_error');
			},
			success: function(data) {
				if (typeof data == 'object' && data.id)
				{
					// get src to original image
					var original_src = parent.find('img').attr('original-src');
					
					// edit thumb - use original image - for cropping
					// must unbind first because we only need only one trigger
					$('#rsmg_box_thumb img').unbind('load');
					$('#rsmg_box_thumb img').attr('src', original_src);
					
					var rsmg_image_loaded = function() {
						// skip for IE
						if ($.browser.msie)
							$('#rsmg_box_thumb img').unbind('load');
						
						if (!data.params.selection)
							data.params.selection = {x1: 0, x2: 380, y1: 0, y2: $('#rsmg_box_thumb img').height()};
						
						$('#rsmg_detail_title').val(data.title);
						$('#rsmg_detail_description').val(data.description);
						$('#rsmg_detail_url').val(data.url);
						$('#rsmg_detail_tags').val(data.tags);
						$('#rsmg_detail_published').attr('checked', data.published == 1);
						$('#rsmg_detail_free_aspect').attr('checked', data.free_aspect == 1);
						$('#rsmg_detail_hits').val(data.hits);
						$('#rsmg_created_date').html(data.created);
						$('#rsmg_modified_date').html(data.modified);
						
						// show picture title
						rsmg_show_message($, data.title, 'rsmg_editing', true);
						
						// listing thumb - use original image - to preview the crop correctly
						parent.find('img').attr('src', original_src);
						
						$('#rsmg_item_detail').show('blind',  {direction: "left"} , 500, function() {
							// when loading is complete,
							// show image
							$('#rsmg_box_thumb img').show();
							
							// try to adjust the height, this happens with Chrome which doesn't ceil the height, instead it floors it
							var new_height = 380 * data.params.info[1] / data.params.info[0];
							if (!isNaN(new_height) && data.params.selection.y1 == 0 && new_height < data.params.selection.y2)
								data.params.selection.y2 = Math.floor(new_height);
							
							// we need to store this here so that we can use it in other places as well
							rsmg_calculate_thumbnail = function(img, selection) {
								if (!selection.width || !selection.height)
									return;
									
								var preview_image = parent.find('img');
								var thumb_container = parent.find('.rsmg_thumb_container');
								
								thumb_container.removeAttr('style');
								preview_image.removeAttr('style');
								
								var free_aspect = document.getElementById('rsmg_detail_free_aspect').checked;
								if (free_aspect) {
									if (selection.width > selection.height && selection.width / selection.height > 4/3) {
										// landscape - must calculate height & force full width
										our_width  = 280;
										our_height = Math.round(our_width * selection.height / selection.width);
										
										var filler_height = Math.round((210 - our_height) / 2);
										thumb_container.css({
											marginTop: filler_height,
											marginBottom: filler_height,
											height: 210 - filler_height * 2
										});
										
										full_width 	= Math.round(280 * 380 / selection.width);
										full_height = Math.round(full_width * img.height / 380);
										
										preview_image.css({
											width: full_width,
											marginLeft: -Math.round(selection.x1 * full_width / 380),
											marginTop: -Math.round(selection.y1 * full_height / img.height)
										});
									} else {
										// portrait - must calculate width & force full height
										our_height = 210;
										our_width  = Math.round(our_height * selection.width / selection.height);
										
										var filler_width = Math.round((280 - our_width) / 2);
										thumb_container.css({
											marginLeft: filler_width,
											marginRight: filler_width,
											width: 280 - filler_width*2
										});
										
										full_height = Math.round(210 * img.height / selection.height);
										full_width  = Math.round(full_height * img.width / img.height);
										
										preview_image.css({
											height: full_height,
											marginLeft: -Math.round(selection.x1 * full_width / 380),
											marginTop: -Math.round(selection.y1 * full_height / img.height)
										});
									}
								} else {
									var scaleX = 280 / selection.width;
									var scaleY = 210 / selection.height;
									
									preview_image.css({
										width: Math.round(scaleX * img.width),
										height: Math.round(scaleY * img.height),
										marginLeft: -Math.round(scaleX * selection.x1),
										marginTop: -Math.round(scaleY * selection.y1)
									});
								}
								
								jQuery('#rsmg_admin_w').val(thumb_container.width());
								jQuery('#rsmg_admin_h').val(thumb_container.height());
							};
							
							// init cropping - get an instance while we're at it
							rsmg_img_area_select = $('#rsmg_box_thumb img').imgAreaSelect({
								instance: true,
								aspectRatio: $('#rsmg_detail_free_aspect').attr('checked') ? false : '4:3',
								disable: false,
								hide: false,
								handles: true,
								persistent: false,
								fadeSpeed: 200,
								onSelectEnd: function(img, selection){
									if (!selection.width || !selection.height)
										return;
									
									rsmg_save_thumbnail_positions($, selection);
								},
								onSelectChange: rsmg_calculate_thumbnail,
								onInit: function (img, selection) {
									// no more loading
									parent.find('.rsmg_thumb_container_loading').remove();
									
									if (!selection.width || !selection.height)
										return;
									
									rsmg_calculate_thumbnail(img, selection);
									rsmg_save_thumbnail_positions($, selection);
								},
								// set initial values
								x1: data.params.selection.x1,
								y1: data.params.selection.y1,
								x2: data.params.selection.x2,
								y2: data.params.selection.y2
							}); // cropping has been initialized
						}); // edit/details screen has been shown
						
					}
					
					// fix for ie7 & 8
					if ($.browser.msie && $.browser.version < 9) {
						// image has been loaded IE7 fix
						$('<img>').load(rsmg_image_loaded).attr('src', original_src);
					}
					else {
						// image has been loaded
						$('<img>', {'src': original_src}).load(rsmg_image_loaded);
					}
				}
			}
		});
	});
	
	// click to publish / unpublish
	$('div.rsmg_publish').unbind('click');
	$('div.rsmg_publish').click(function(e) {
		e.preventDefault();
		
		// do not trigger if clicked twice or details already showing
		if ($('#rsmg_item_detail').css('display') == 'block')
		{
			return;
		}
		
		var self = $(this);
		var parent  = self.parents('li.rsmg_item');
		var cid 	= parent.children('input[name="cid[]"]').val();
		
		// prevent multiple clicks while ajax is loading
		if (self.hasClass('rsmg_publish_loading'))
			return false;
		
		if (self.hasClass('rsmg_published'))
		{
			rsmg_unpublish_items($, cid, function(){
					self.children('span').html(rsmg_get_lang('RSMG_UNPUBLISHED'));
					self.addClass('rsmg_unpublished');
					self.removeClass('rsmg_publish_loading');
				},
				function (){
					self.removeClass('rsmg_published');
					self.addClass('rsmg_publish_loading');
				},
				true
			);
		}
		else
		{
			rsmg_publish_items($, cid, function(){
					self.children('span').html(rsmg_get_lang('RSMG_PUBLISHED'));
					self.addClass('rsmg_published');
					self.removeClass('rsmg_publish_loading');
				},
				function (){
					self.removeClass('rsmg_unpublished');
					self.addClass('rsmg_publish_loading');
				},
				true
			);
		}
	});	
	
	$('.rsmg_preview').fancyZoom({
		scaleImg: true,
		closeOnClick: true
	});
}

function rsmg_hide_items($)
{
	$('.rsmg_item').css('display', 'none');
	$("#rsmg_items").selectable({ disabled: true });
	$("#rsmg_items").sortable({ disabled: true });
}

function rsmg_show_items($)
{
	$("#rsmg_items").addClass('rsmg_full_width');
	$('.rsmg_item').removeAttr('style');
	$('.rsmg_item').css('display', 'block');
	$("#rsmg_items").selectable({ disabled: false });
	//$("#rsmg_items").sortable({ disabled: false });
	rsmg_init_sortable($);
}

function rsmg_show_filter_toolbar($)
{
	$('.rsmg_filter_toolbar').show();
	$('#rsmg_filter').show();
	$('#rsmg_select_all').show();
}

function rsmg_hide_filter_toolbar($)
{
	$('.rsmg_filter_toolbar').hide();
	$('#rsmg_filter').hide();
	$('#rsmg_select_all').hide();
}

function rsmg_show_edit_toolbar($)
{
	$('#rsmg_toolbar_default').hide('fade', 500);
	$('#rsmg_toolbar_edit').show('fade', 500);
}

function rsmg_show_default_toolbar($)
{
	$('#rsmg_toolbar_edit').hide('fade', 500);
	$('#rsmg_toolbar_default').show('fade', 500);
}

function rsmg_get_items_filter($, more)
{
	// rsmg_columns[]
	// rsmg_operators[]
	// rsmg_values[]
	
	var columns = [];
	$('input[name="rsmg_columns[]"]').each(function (index, el){
		columns.push($(el).val());
	});
	if (columns.length == 0)
		columns = '';
	var operators = [];
	$('input[name="rsmg_operators[]"]').each(function (index, el){
		operators.push($(el).val());
	});
	if (operators.length == 0)
		operators = '';
	var values = [];
	$('input[name="rsmg_values[]"]').each(function (index, el){
		values.push($(el).val());
	});
	if (values.length == 0)
		values = '';
	
	var data = {
		'limit': $('#rsmg_limit ul li a.rsmg_tick').attr('rel'),
		'limitstart': $('ul#rsmg_items li.rsmg_item').length,
		'filter_columns[]': columns,
		'filter_operators[]': operators,
		'filter_values[]': values,
		'order': $('#rsmg_order ul li a.rsmg_tick').attr('rel'),
		'direction': $('#rsmg_direction ul li a.rsmg_tick').attr('rel')
	};
	
	if (more)
		for (var key in more)
			data[key] = more[key];
	
	return data;
}

var rsmg_sort_array = [];

function rsmg_get_items($, clear, more)
{
	// parent container
	var parent = $('#rsmg_items');
	
	// clear contents
	if (clear == true)
	{
		rsmg_sort_array = [];
		parent.empty();
	}
	
	$.ajax({
		type: 'POST',
		url: "index.php?option=com_rsmediagallery&controller=items&task=getitems&format=raw",
		data: rsmg_get_items_filter($, more),
		beforeSend: function() {
			// li container
			var li = $('<li>', {'class': 'rsmg_item', 'id': 'rsmg_loader_container'});
			
			// ajax loader
			var loader = $('<div>', {'id': 'rsmg_loader'});
			
			// add loader image
			li.append(loader);
			
			// append the loader as the last item in the list
			parent.append(li);
			
			// hide load more
			$('#rsmg_load_more').hide('fade', 500);
		},
		success: function(data){
			$('#rsmg_loader_container').remove();
			
			if (typeof data == 'object' && data.items && data.total)
			{
				$(data.items).each(function (index, item) {
					rsmg_sort_array.push({'id': item.id, 'ordering': item.ordering});
					
					// id container
					var id = $('<input>', {'type': 'hidden', 'name': 'cid[]', 'value': item.id});
					
					// li container
					var li = $('<li>', {'class': 'rsmg_item'});
					if ($('#rsmg_select_all').attr('rel') == 1)
						li.addClass('ui-selected');
					
					// thumbnail
					var div_thumb  = $('<div>', {'class': 'rsmg_thumb_container'});
					
					// center thumbnail
					var admin_w = item.params.admin_thumb.w;
					var admin_h = item.params.admin_thumb.h;
					rsmg_center_admin_thumb(admin_w, admin_h, div_thumb);
					
					var img_thumb  = $('<img>', {
						'src': rsmg_get_root() + '/components/com_rsmediagallery/assets/gallery/' + item.filename, 
						'alt': item.title,
						'original-src': rsmg_get_root() + '/components/com_rsmediagallery/assets/gallery/original/' + item.filename,
						'thumb-src': rsmg_get_root() + '/components/com_rsmediagallery/assets/gallery/' + item.filename,
						'original-width': item.params.info[0],
						'original-height': item.params.info[1]
					});
					img_thumb.data('rsmg_data', {'admin_w': admin_w, 'admin_h': admin_h});
					div_thumb.append(img_thumb);
					
					// clear
					var span_clear = $('<span>', {'class': 'rsmg_clear'});
					
					// published/unpublished
					if (item.published == 1) {
						var div_publish 	  = $('<div>', {'class': 'rsmg_action_button rsmg_publish rsmg_published', 'title': rsmg_get_lang('RSMG_UNPUBLISH_DESC')});
						var span_publish_text = $('<span>').html(rsmg_get_lang('RSMG_PUBLISHED'));
					} else {
						var div_publish 	  = $('<div>', {'class': 'rsmg_action_button rsmg_publish rsmg_unpublished', 'title': rsmg_get_lang('RSMG_PUBLISH_DESC')});
						var span_publish_text = $('<span>').html(rsmg_get_lang('RSMG_UNPUBLISHED'));
					}
					div_publish.append(span_publish_text);
					
					// toolbox container
					var dl = $('<dl>');
					
					// toolbox - edit, delete
					var dt_edit	   = $('<dt>');
					var a_edit 	   = $('<a>', {'class': 'rsmg_action_button rsmg_edit', 'href': '#', 'title': rsmg_get_lang('RSMG_EDIT_DESC')});
					var dt_preview = $('<dt>');
					var a_preview  = $('<a>', {'class': 'rsmg_action_button rsmg_preview', 'href': '#', 'title': rsmg_get_lang('RSMG_PREVIEW_DESC')});
					var dt_delete  = $('<dt>');
					var a_delete   = $('<a>', {'class': 'rsmg_action_button rsmg_delete', 'href': '#', 'title': rsmg_get_lang('RSMG_DELETE_DESC')});
					dt_edit.append(a_edit);
					dt_preview.append(a_preview);
					dt_delete.append(a_delete);
					
					dl.append(dt_preview, dt_edit, dt_delete);
					
					li.append(id, div_thumb, span_clear, div_publish, dl);
					
					parent.append(li);
				});
				
				rsmg_init_items($);
				
				// show load more
				if (data.total > $('ul#rsmg_items li.rsmg_item').length)
				{
					var left = data.total - $('ul#rsmg_items li.rsmg_item').length;
					$('#rsmg_load_more').html(rsmg_get_lang('RSMG_LOAD_MORE').replace('%d', left));
					$('#rsmg_load_more').attr('rel', left);
					$('#rsmg_load_more').show('fade', 500);
				}
				else
				{
					var left = 0;
					$('#rsmg_load_more').html(rsmg_get_lang('RSMG_LOAD_MORE').replace('%d', left));
					$('#rsmg_load_more').attr('rel', left);
				}
				
				if (clear)
					rsmg_show_selected($);
			}
		}
	});
}

function rsmg_init_items($)
{
	rsmg_init_sortable($);
	rsmg_init_selectable($);
	rsmg_init_rumble($);
	rsmg_init_pic_toolbar($);
	rsmg_init_tooltips($);
}

function rsmg_init_load_more($)
{
	$('#rsmg_load_more').click(function (e){
		e.preventDefault();
		e.shiftKey ? rsmg_get_items($, false, {'limitall': 1, 'limit': $('#rsmg_load_more').attr('rel')}) : rsmg_get_items($);
	});
	
	$(document).keydown(function (e) {
		if (e.shiftKey)
		{
			$('#rsmg_load_more').html(rsmg_get_lang('RSMG_LOAD_ALL').replace('%d', $('#rsmg_load_more').attr('rel')));
			$('#rsmg_select_all').html(rsmg_get_lang('RSMG_SELECT_ALL_PAGES').replace('%d', parseInt($('#rsmg_load_more').attr('rel')) + parseInt($('ul#rsmg_items li.rsmg_item').length)));
		}
	});
	
	$(document).keyup(function (e) {
		$('#rsmg_load_more').html(rsmg_get_lang('RSMG_LOAD_MORE').replace('%d', $('#rsmg_load_more').attr('rel')));
		$('#rsmg_select_all').html(rsmg_get_lang('RSMG_SELECT_ALL'));
	});
}

// init upload field
function rsmg_init_upload($)
{
	$('#rsmg_add_files').fileupload({
		autoUpload: true,
		acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
		dataType: 'json',
		url: 'index.php?option=com_rsmediagallery&controller=items&task=uploaditem&format=raw',
		sequentialUploads: true,
		submit: function (e, data) {
			data.formData = {
				'tags': $('#rsmg_add_tags_text').val().trim(),
				'published': document.getElementById('rsmg_add_published').checked ? 1 : 0
			}
			return true;
		},
		start: function (e) {
			// while uploading, don't allow closing the window
			$('#rsmg_dialog_upload').dialog('option', 'buttons', [
				{
					text: rsmg_get_lang('RSMG_PLEASE_WAIT'),
					click: function() {
						return false;
					}
				}
			]);
			
			$('.ui-dialog .ui-button-text').append($('<img>', {'src': 'components/com_rsmediagallery/assets/images/loader-small-trans.gif'}));
		},
		stop: function (e) {
			$('#rsmg_dialog_upload').dialog('option', 'buttons', [
				{
					text: rsmg_get_lang('RSMG_CLOSE'),
					click: function() {
						$(this).dialog('close');
						rsmg_get_items($, true);
					}
				}
			]);
		},
		add: function (e, data) {
			// must check for valid extensions
			var options = $(this).data('fileupload').options;
			var valid	= true;
			
			if (!options.acceptFileTypes)
				return valid;
			
            $.each(data.files, function (index, file) {
				if (!options.acceptFileTypes.test(file.type) && !options.acceptFileTypes.test(file.name))
					valid = false;
            });
			
			if (valid && options.autoUpload == true)
				data.submit();
        },
		done: function (e, data) {
			if (data.result && typeof data.result == 'object')
			{
				// add the result to the list
				$('#rsmg_add_results').prepend($('<li>', {'class': data.result.error == 1 ? 'rsmg_error' : 'rsmg_success'}).html(data.result.message));
			}
		}
	})
}

// init details toolbar
function rsmg_init_details_buttons($)
{
	$('.rsmg_button_cancel').click(function(e) {
		e.preventDefault();
		rsmg_close_details($, true);
	}).button({
        icons: {'primary': "ui-icon-close"}	
    });
	
	$(".rsmg_button_save").click(function (e) {
		e.preventDefault();
		rsmg_save_details($);
	}).button({
        icons: {'primary': "ui-icon-disk"}	
    });
	
	$( ".rsmg_button_apply" ).click(function (e) {
		e.preventDefault();
		rsmg_save_details($, true);
	}).button({
        icons: {'primary': "ui-icon-check"}	
    });	
	
	// use this to trigger the save when enter is pressed and the form is submitted
	$("#rsmg_save_form").submit(function(e) {
		e.preventDefault();
		$('.rsmg_button_save').trigger('click');
	});
}

function rsmg_del_items($, parent, cid, single)
{
	selectall = $('#rsmg_select_all').attr('rel') == 1 && !single ? 1 : 0;
	
	parent.trigger('startRumble');
	$( "#dialog:ui-dialog" ).dialog("destroy");
	$( "#rsmg_dialog_confirm_delete" ).dialog({
		beforeClose: function() {
			parent.trigger('stopRumble');
		},
		resizable: false,			
		modal: true,
		draggable: false,
		title: rsmg_get_lang('RSMG_ARE_YOU_SURE'),
		buttons: [
			{
				text: rsmg_get_lang('RSMG_DELETE'),
				click: function() {
					$(this).dialog('close');
					parent.hide('highlight', 500, function() {
						$(this).remove();
					});
					
					window.setTimeout(function() {
					temp_cid = cid;
					if (typeof temp_cid != 'array' && typeof temp_cid != 'object')
						temp_cid = [cid];
					
					for (t=0; t<temp_cid.length; t++)
					{
						for (a=0; a<rsmg_sort_array.length; a++)
						if (rsmg_sort_array[a].id == temp_cid[t])
						{
							rsmg_sort_array.splice(a, 1);
							break;
						}
					}
					
					selectall = $('#rsmg_select_all').attr('rel') == 1 ? 1 : 0;
					
					$.ajax({
						type: 'POST',
						url: "index.php?option=com_rsmediagallery&controller=items&task=delitems&format=raw",
						data: rsmg_get_items_filter($, {'cid[]': cid, 'selectall': selectall}),
						success: function(data){
							if (typeof data == 'object' && data.items)
							{								
								rsmg_show_message($, rsmg_get_lang(temp_cid.length > 1 ? 'RSMG_ITEMS_DELETED' : 'RSMG_ITEM_DELETED'), 'rsmg_sucess');
								
								// show load more
								if (data.total > $('ul#rsmg_items li.rsmg_item').length)
								{
									var left = data.total - $('ul#rsmg_items li.rsmg_item').length;
									$('#rsmg_load_more').html(rsmg_get_lang('RSMG_LOAD_MORE').replace('%d', left));
									$('#rsmg_load_more').attr('rel', left);
									$('#rsmg_load_more').show('fade', 500);
								}
								else
								{
									var left = 0;
									$('#rsmg_load_more').html(rsmg_get_lang('RSMG_LOAD_MORE').replace('%d', left));
									$('#rsmg_load_more').attr('rel', left);
								}
							}
						}
					});
					}, 600);
				}
			},
			{
				text: rsmg_get_lang('RSMG_CANCEL'),
				click: function() {
					$(this).dialog('close');
				}
			}
		]
	});
}

function rsmg_publish_items($, cid, successCallback, beforeCallback, single)
{
	if (typeof successCallback != 'function')
		successCallback = function() {}
	if (typeof beforeCallback != 'function')
		beforeCallback = function() {}
	
	selectall = $('#rsmg_select_all').attr('rel') == 1 && !single ? 1 : 0;
	
	$.ajax({
		type: 'POST',
		url: "index.php?option=com_rsmediagallery&controller=items&task=publishitems&format=raw",
		data: {'cid[]': cid, 'selectall': selectall},
		success: successCallback,
		beforeSend: beforeCallback
	});
}

function rsmg_unpublish_items($, cid, successCallback, beforeCallback, single)
{
	if (typeof successCallback != 'function')
		successCallback = function() {}
	if (typeof beforeCallback != 'function')
		beforeCallback = function() {}
	
	selectall = $('#rsmg_select_all').attr('rel') == 1 && !single ? 1 : 0;
	
	$.ajax({
		type: 'POST',
		url: "index.php?option=com_rsmediagallery&controller=items&task=unpublishitems&format=raw",
		data: {'cid[]': cid, 'selectall': selectall},
		success: successCallback,
		beforeSend: beforeCallback
	});
}

function rsmg_close_details($, hide_title)
{
	$(window).unbind('scroll', rsmg_scroll_item);
	$('.rsmg_thumb_container').removeAttr('style');
	$('.rsmg_thumb_container img').each(function(index, el) {
		var rsmg_data = $(el).data('rsmg_data');
		rsmg_center_admin_thumb(rsmg_data.admin_w, rsmg_data.admin_h, $(el).parent());
	});
	// hide filter options
	rsmg_show_filter_toolbar($);
	// show default tooblar instead of edit
	rsmg_show_default_toolbar($);
	// show load more
	if (typeof $('#rsmg_load_more').attr('rel') != 'undefined' && parseInt($('#rsmg_load_more').attr('rel')) > 0)
		$('#rsmg_load_more').show('fade', 500);
	// hide picture title
	if (hide_title)
		$('#rsmg_info').hide('fade', 500, function() {
			rsmg_show_selected($);
		});
	// disable cropping
	$('#rsmg_box_thumb img').hide();
	$('#rsmg_box_thumb img').imgAreaSelect({remove:true});
	$('.rsmg_thumb_container').children('img').each(function (index, img){
		$(img).attr('src', $(img).attr('thumb-src'));
		$(img).removeAttr('style');
	});
	// blind details box & show items
	$('#rsmg_item_detail').hide('blind',  {direction: "left"} , 500, function(){
		rsmg_show_items($);
	});
}

function rsmg_save_details($, apply)
{
	var cid = 0;
	var parent = null;
	
	$('li.rsmg_item').each(function(index, el){
		if ($(el).css('display') == 'block')
		{
			cid 	= $(el).children('input[name="cid[]"]').val();
			parent 	= $(el);
		}
	});
	
	$.ajax({
		type: 'POST',
		url: "index.php?option=com_rsmediagallery&controller=items&task=saveitem&format=raw",
		data: {
			'cid': 			cid,
			'title': 		$('#rsmg_detail_title').val(),
			'tags': 		$('#rsmg_detail_tags').val(),
			'description': 	$('#rsmg_detail_description').val(),
			'url': 			$('#rsmg_detail_url').val(),
			'free_aspect':	$('#rsmg_detail_free_aspect').attr('checked') ? 1 : 0,
			'hits': 		$('#rsmg_detail_hits').val(),
			'published': 	$('#rsmg_detail_published').attr('checked') ? 1 : 0,
			'x1': 			$('#rsmg_x1').val(),
			'y1': 			$('#rsmg_y1').val(),
			'x2': 			$('#rsmg_x2').val(),
			'y2': 			$('#rsmg_y2').val(),
			'w':  			$('#rsmg_w').val(),
			'h':  			$('#rsmg_h').val(),
			'admin_w':		$('#rsmg_admin_w').val(),
			'admin_h':  	$('#rsmg_admin_h').val()
		},
		beforeSend: function() {
			// hide buttons - need to save the request without interruptions
			$('.rsmg_button_apply').hide();
			$('.rsmg_button_save').hide();
			$('.rsmg_button_cancel').hide();
			// show loading image
			$('#rsmg_detail_loader').show();
		},
		success: function(data) {
			// show buttons
			$('.rsmg_button_apply').show();
			$('.rsmg_button_save').show();
			$('.rsmg_button_cancel').show();
			// hide loading image
			$('#rsmg_detail_loader').hide();
					
			if (typeof data == 'object')
			{
				if (data.success == 1)
				{
					var skipcache 	= Math.floor(Math.random()*1000001);
					var item		= data.item;
					var img			= parent.find('img');
					
					img.attr('title', item.title);
					img.attr('thumb-src', rsmg_get_root() + '/components/com_rsmediagallery/assets/gallery/' + item.filename + '?skipcache=' + skipcache);
					// store data in image
					img.data('rsmg_data', {'admin_w': $('#rsmg_admin_w').val(), 'admin_h': $('#rsmg_admin_h').val()});
					
					// show published
					if (item.published == 1)
					{
						parent.find('div.rsmg_publish').removeClass('rsmg_publish_loading').removeClass('rsmg_unpublished').addClass('rsmg_published').children('span').html(rsmg_get_lang('RSMG_PUBLISHED'));
					}
					// show unpublished
					else
					{
						parent.find('div.rsmg_publish').removeClass('rsmg_publish_loading').removeClass('rsmg_published').addClass('rsmg_unpublished').children('span').html(rsmg_get_lang('RSMG_UNPUBLISHED'));
					}
					
					// close details
					if (!apply)
						rsmg_close_details($, false);
						
					rsmg_show_message($, rsmg_get_lang('RSMG_ITEM_SAVED'), 'rsmg_sucess');
				}
				else
				{
					// show the error message
					$('#rsmg_save_result').addClass('rsmg_error').html(data.message);
				}
			}
		}
	});
}

function rsmg_submit($, task)
{
	switch (task)
	{
		case 'save':
			rsmg_save_details($);
			return;
		break;
		
		case 'apply':
			rsmg_save_details($, true);
			return;
		break;
		
		case 'cancel':
			rsmg_close_details($, true);
			return;
		break;
		
		case 'upload':
			$('#dialog:ui-dialog').dialog('destroy');
			$('#rsmg_dialog_upload').dialog({
				height: 330,
				width: 430,
				modal: true,
				draggable: false,
				resizable: false,
				buttons: [
					{
						text: rsmg_get_lang('RSMG_CLOSE'),
						click: function() {
							$(this).dialog('close');
						}
					}
				]
			});
			
			return;
		break;
	}
	
	var children = $('ul#rsmg_items li.rsmg_item.ui-selected').children('input[name="cid[]"]');
	if (children.length == 0)
	{
		$('#dialog:ui-dialog').dialog('destroy');
		$('#rsmg_dialog_message').dialog({
			height: 400,
			width: 400,
			modal: true,
			draggable: false,
			resizable: false,
			buttons: [
				{
					text: rsmg_get_lang('RSMG_CLOSE'),
					click: function() {
						$(this).dialog('close');
					}
				}
			]
		});
	}
	else
	{
		var cid = new Array();
		children.each(function(index, el){
			cid.push($(el).val());
		});
		
		switch (task)
		{
			case 'remove':
				// remove items from list
				var parent = children.parents('li.rsmg_item');
				rsmg_del_items($, parent, cid);			
			break;
			
			case 'publish':
				if (children.parents('li.rsmg_item').children('div.rsmg_publish').hasClass('rsmg_publish_loading'))
					return false;
				
				rsmg_publish_items($, cid, function() {
						// callback - long chain here to publish items
						children.parents('li.rsmg_item').children('div.rsmg_publish').removeClass('rsmg_publish_loading').addClass('rsmg_published').children('span').html(rsmg_get_lang('RSMG_PUBLISHED'));
					},
					function() {
						children.parents('li.rsmg_item').children('div.rsmg_publish').removeClass('rsmg_published').removeClass('rsmg_unpublished').addClass('rsmg_publish_loading');
					}
				);
			break;
			
			case 'unpublish':
				if (children.parents('li.rsmg_item').children('div.rsmg_publish').hasClass('rsmg_publish_loading'))
					return false;
					
				rsmg_unpublish_items($, cid, function() {
						// callback - long chain here to unpublish items
						children.parents('li.rsmg_item').children('div.rsmg_publish').removeClass('rsmg_publish_loading').addClass('rsmg_unpublished').children('span').html(rsmg_get_lang('RSMG_UNPUBLISHED'));
					},
					function() {
						children.parents('li.rsmg_item').children('div.rsmg_publish').removeClass('rsmg_published').removeClass('rsmg_unpublished').addClass('rsmg_publish_loading');
					}
				);
			break;
			
			case 'tag':
				// tag button
				$('#dialog:ui-dialog').dialog('destroy');
				$('#rsmg_dialog_tag').dialog({
					width: 400,
					modal: true,
					draggable: false,
					resizable: false,
					buttons: [
						{
							text: rsmg_get_lang('RSMG_SAVE_CHANGES'),
							click: function() {
								if ($('#rsmg_tags_text').val().trim().length == 0)
								{
									$('#rsmg_tags_text').val('');
									$('#rsmg_tags_text_error').show('fade', {}, 600);
									setTimeout(function() { $('#rsmg_tags_text_error').hide('fade', {}, 600) }, 3000);
									return;
								}
								
								$('#rsmg_tags_text_error').hide();
								$(this).dialog('close');
								selectall = $('#rsmg_select_all').attr('rel') == 1 ? 1 : 0;
								$.ajax({
									type: 'POST',
									url: "index.php?option=com_rsmediagallery&controller=items&task="+($('#rsmg_action0:checked').length > 0 ? 'tagitems' : 'untagitems')+"&format=raw",
									data: {'cid[]': cid, 'tags': $('#rsmg_tags_text').val(), 'selectall': selectall},
									success: function (data) {
										$('#rsmg_tags_text').val('');
										var message = $('#rsmg_action0:checked').length > 0 ? rsmg_get_lang('RSMG_TAGS_HAVE_BEEN_ADDED') : rsmg_get_lang('RSMG_TAGS_HAVE_BEEN_REMOVED');
										rsmg_show_message($, message, 'rsmg_sucess');
									}
								});
							}
						},
						{
							text: rsmg_get_lang('RSMG_CANCEL'),
							click: function() {
								$('#rsmg_tags_text_error').hide();
								$(this).dialog('close');
							}
						}
					]
				});
			break;
		}
	}
}

function rsmg_show_selected($)
{
	// get selected items
	var selected = $('#rsmg_items').children('.ui-selected').length;
	// get total items
	var all 	 = parseInt($('#rsmg_load_more').attr('rel')) + $('#rsmg_items').children('li').length;
	// if all items are selected
	if ($('#rsmg_select_all').attr('rel') && $('#rsmg_select_all').attr('rel') == 1)
		selected = all;
	message = rsmg_get_lang('RSMG_SELECTED_ITEMS').replace('%d', selected).replace('%d', all);
	rsmg_show_message($, message, 'rsmg_selected', true);
}

function rsmg_show_message($, message, classname, persist)
{
	$('#rsmg_info').empty();
	$('#rsmg_info').show();
	var p = $('<p>', {'class': classname}).html(message).hide();
	$('#rsmg_info').append(p.show('fade',  {}, 600));
	if (!persist)
	{
		setTimeout(function() {
			p.hide('fade', function() { $(this).remove(); }, 600);
		}, 3000);
	}
}

function rsmg_change_aspect_ratio()
{
	var free_aspect 	 = document.getElementById('rsmg_detail_free_aspect').checked;
	var ratio 			 = free_aspect ? false : '4:3';
	var selection 		 = rsmg_img_area_select.getSelection();
	var selection_width  = selection.x2 - selection.x1;
	var selection_height = selection.y2 - selection.y1;
	var img 			 = jQuery('#rsmg_box_thumb img');
	var width 			 = img.width();
	var height			 = img.height();
	
	if (!free_aspect) {		
		if (selection_width / selection_height != 4/3) {
			computed_width  = selection_width;
			computed_height = Math.round(computed_width * 3 / 4);
			
			if (computed_height <= height) {
				if (selection.y1 + computed_height <= height) {
					selection.y2 = selection.y1 + computed_height;
				} else {
					while (selection.y1 > -1 && selection.y1 + computed_height > height)
						selection.y1--;
					selection.y2 = selection.y1 + computed_height;
				}
			} else {
				computed_height = selection_height;
				computed_width	= Math.round(computed_height * 4 /3);
				
				if (computed_width <= width) {
					if (selection.x1 + computed_width <= width) {
						selection.x2 = selection.x1 + computed_width;
					} else {
						while (selection.x1 > -1 && selection.x1 + computed_width > width)
							selection.x1--;
						selection.x2 = selection.x1 + computed_width;
					}
				} else {
					computed_width  = 4;
					computed_height = 3;
					
					selection.x1 = 0;
					selection.x2 = 4;
					selection.y1 = 0;
					selection.y2 = 3;
				}
			}
		}
	} else {
		computed_width  = selection_width;
		computed_height = selection_height;
	}
	
	rsmg_img_area_select.setOptions({aspectRatio: ratio});
	rsmg_img_area_select.setSelection(selection.x1, selection.y1, selection.x2, selection.y2);
	
	// update the object with the new properties
	rsmg_img_area_select.update();
	
	// need to trigger this manually...
	var new_selection = {x1: selection.x1, y1: selection.y1, x2: selection.x2, y2: selection.y2, width: computed_width, height: computed_height};
	var new_img		  = {width: width, height: height};
	rsmg_calculate_thumbnail(new_img, new_selection);
	rsmg_save_thumbnail_positions(jQuery, new_selection);
}

function rsmg_save_thumbnail_positions($, selection)
{
	$('#rsmg_x1').val(selection.x1);
	$('#rsmg_y1').val(selection.y1);
	$('#rsmg_x2').val(selection.x2);
	$('#rsmg_y2').val(selection.y2);
	$('#rsmg_w').val(selection.width);
	$('#rsmg_h').val(selection.height);
}

function rsmg_center_admin_thumb(admin_w, admin_h, div_thumb)
{
	admin_w = parseInt(admin_w);
	admin_h = parseInt(admin_h);
	
	if (admin_w == 280 && admin_h == 210) {
		return;
	}
	
	if (admin_w > admin_h && admin_w / admin_h > 4/3) {
		// landscape
		var filler_height = Math.round((210 - admin_h) / 2);
		div_thumb.css({
			width: 280,
			height: admin_h,
			marginTop: filler_height,
			marginBottom: filler_height
		});
	} else {
		// portrait
		var filler_width = Math.round((280 - admin_w) / 2);
		div_thumb.css({
			height: 210,
			width: admin_w,
			marginLeft: filler_width
		});
	}
}
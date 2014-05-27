jQuery.noConflict();
jQuery(document).ready(function($){
	$('.rsmg_param_tags').autoSuggest('index.php', {
		minChars: 2,
		startText: '',
		emptyText: rsmg_get_lang('RSMG_PARAM_TAGS_NO_RESULTS'),
		limitText: '',
		queryParam: 'filter_values',
		extraParams: '&option=com_rsmediagallery&controller=items&task=getsuggestions&format=raw&filter_columns=tags&filter_operators=contains&dont_remember=1',
		neverSubmit: true,
		retrieveComplete: function(data) {
			if (data)
			{
				newdata = [];
				for (i=0; i<data.length; i++)
					newdata.push({'value': data[i]});
				return newdata;
			}
			return data;
		},
		selectionAdded: function (self) {
			$('#menu-pane .panel:first-child .jpane-slider').css('height', $('#menu-pane .panel table.paramlist').height());
		}
	});
	
	if (typeof Joomla != 'undefined' && typeof Joomla.submitbutton != 'undefined') {
		original = Joomla.submitbutton;
		Joomla.submitbutton = function(task, type) {
			if (task == 'item.apply' || task == 'item.save' || task == 'item.save2new' || task == 'item.save2copy')
			{
				// IE fix, as usual
				var tags_input;
				if (document.getElementsByName('jform[params][tags]').length == 2)
					tags_input = document.getElementsByName('jform[params][tags]')[1];
				else
					tags_input = document.getElementsByName('jform[params][tags]')[0];
				if (tags_input.value.length == 0)
					return alert(rsmg_get_lang('RSMG_PLEASE_ADD_TAG'));
			}
			original(task, type);
		}
	} else {
		original = submitbutton;
		submitbutton = function(task) {
			if (task == 'save' || task == 'apply')
			{
				// IE fix, as usual
				var tags_input;
				if (document.getElementsByName('params[tags]').length == 2)
					tags_input = document.getElementsByName('params[tags]')[1];
				else
					tags_input = document.getElementsByName('params[tags]')[0];
				if (tags_input.value.length == 0)
					return alert(rsmg_get_lang('RSMG_PLEASE_ADD_TAG'));
			}
			original(task);
		}
	}
});
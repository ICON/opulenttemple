jQuery(document).ready(function(){
	jQuery("#adminForm").validationEngine('attach', {promptPosition : "topRight:12,-44"});
});

Joomla.submitform = function(pressbutton, form)
{
	if (typeof form == 'undefined'){
		form = jQuery('#adminForm');
	} else if(!(form instanceof jQuery)){
		form = jQuery(form);
	}

	//Unlock the page
	holdForm = false;
	var valid = false;

	if (typeof(pressbutton) == 'undefined')
	{
		form.submit();
		return;
	}	
	var parts = pressbutton.split('.');

	form.find("#task").val(pressbutton);
	switch(parts[parts.length-1])
	{
		case 'save':
		case 'save2copy':
		case 'save2new':
		case 'apply':
		
			
			valid = checkFullForm(form);
			
			
			//Call the validator
			break;

		default:
			valid = true;
			form.validationEngine('detach');
			break;
	}


	
	
	if(valid){
		if(typeof jQuery.fn.block != 'undefined'){
			form.block({ 
				message: Joomla.JText._("PLG_JDOM_BLOCK_UI_DEFAULT_MESSAGE")
			});		
		}
	
		form.submit();
	} else {
		//Lock the page
		holdForm = false;
	}
}

function checkFullForm(form){

	if (typeof form == 'undefined'){
		form = jQuery('#adminForm');
	} else if(!(form instanceof jQuery)){
		form = jQuery(form);
	}
	
	form.find('.fields_errors').remove();
	var validate = form.validationEngine('validate');
	
	if(validate == true){	
		return true;
	}
	
	var tabs = form.find('a[data-toggle="tab"]');
	
	tabs.each(function(index){
		href = jQuery(this).attr('href');
		errors = jQuery(href).find('.formError:not(.greenPopup)');
		numErrors = errors.length;
		
		if(numErrors > 0){
			var error_msg = Joomla.JText._("JSHOP_FORM_WITH_ERRORS");
			var prompt = '<div class="parentFormadminForm formError" style="opacity: 0.87; position: absolute; top: -40px; left: -13px; margin-top: 0px;"><div style="width: 165px;" class="formErrorContent">'+ error_msg +'<br></div><div class="formErrorArrow"><div class="line10"><!-- --></div><div class="line9"><!-- --></div><div class="line8"><!-- --></div><div class="line7"><!-- --></div><div class="line6"><!-- --></div><div class="line5"><!-- --></div><div class="line4"><!-- --></div><div class="line3"><!-- --></div><div class="line2"><!-- --></div><div class="line1"><!-- --></div></div></div>';
			jQuery(this).append('<span class="fields_errors"><span class="num">'+ numErrors +'</span>'+ prompt +'</span>');
		}
	});
	
	tabs.on('click', function(){
		jQuery(this).find('.fields_errors').remove();	
	});
	
	return false;
}

Joomla.submitformAjax = function(task, form)
{
	if (typeof form === 'undefined'){
		form = jQuery('#adminForm');
	} else if(!(form instanceof jQuery)){
		form = jQuery(form);
	}
	
	var taskName = '';
	if (typeof(task) !== 'undefined' && task !== '')
	{		
		form.task.value = task;
		var parts = task.split('.');
		taskName = parts[parts.length-1];	
		form.find('.cktoolbar span.' + taskName).addClass('spinner');
	}
	else
		//Not ajax when controller task is empty (ex: filters, search, ...)
		return Joomla.submitform();

	//Ajax query only when a task is performed
	jQuery.post("index.php?return=json", jQuery(form).serialize(), function(response)
	{
		response = jQuery.parseJSON(response);
		if (response.transaction.result)
		{
			switch(taskName)
			{
				case 'save':
				case 'save2copy':
				case 'save2new':
				case 'apply':
				case 'delete':
				case 'publish':
				case 'unpublish':
				case 'trash':
				case 'archive':
					//Reload parent page only if needed
					parent.holdForm = false;
					parent.location.reload(false);
	
					//Close modal
					parent.SqueezeBox.close();
							
					break;
				
				case 'cancel':
					//Close modal
					parent.SqueezeBox.close();				
					break;
				
				default:
					//Keep modal opened
					break;
			}
			
		}
		else
		{
			var msg = response.transaction.rawExceptions;
			if (msg.trim() == '')
				msg = 'Unknown error';
	
			alert(msg);
		}
	});		


};


function checkFormStep(index,formID){
	var scrollExecuted = false,validations = [],
		form = jQuery('#'+ formID +'Step'+index),
	formElements = form.find('input, select, textarea');

	if(formElements.length == 0){
		return true;
	}
	formElements.each(function(ind){
		var validationResult = !jQuery(this).validationEngine('validate');
		validations.push(validationResult);
		if(!scrollExecuted && !validationResult){
			scrollToElement(this);
		}
	});
	
	if(!validations.AllValuesSame()){
		return false;
	} else if(validations.AllValuesSame()) {
		if(validations[0]){
			return true;
		}
		return false;
	}

	return false;
}

Array.prototype.AllValuesSame = function(){
    if(this.length > 0) {
        for(var i = 1; i < this.length; i++)
        {
            if(this[i] !== this[0])
                return false;
        }
    } 
    return true;
}

function scrollToElement( target, topoffset ) {
	if (typeof target == 'undefined'){
		return;
	} else if(!(target instanceof jQuery)){
		target = jQuery(target);
	}

	if(typeof jQuery( target ) !== 'undefined'){
		if(typeof topoffset == 'undefined'){
			topoffset = 100;
		}
		
		var speed = 1300;
		var destination = target.offset().top - topoffset;
		
		jQuery( 'html:not(:animated),body:not(:animated)' ).animate( { scrollTop: destination}, speed, function() {
		/*	window.location.hash = target; */
		});

	}
    return false;
}

jQuery.fn.serializeObject = function()
{
    var a,o = {};
	a = this.find('input,textarea,select').serializeArray();
    jQuery.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

function submitStep(fId,data,tk){
	var dataToSubmit = {
			"frm": fId,
			"frmData": data
			};
		dataToSubmit[tk] = 1;
	
	jQuery('#system-message').jdomAjax({
		namespace:"jforms.submission.ajax.savestep", 
		vars:dataToSubmit,
		success: function(object, data, textStatus, jqXHR)
		{
			var thisp = this;
			
			// fill the object with the returned html
			$(object).html('').html(data);
			$(object).ready(function()
			{
				if (typeof(thisp.ready) != 'undefined')
					thisp.ready(object, data, textStatus, jqXHR);	
			});
		},		
		loading: function(object)
		{
	
		},
		error: function(object, jqXHR, textStatus, errorThrown)
		{
		
		}
	});
}
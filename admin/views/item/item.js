function categoryChange(id, multi = false) {
	if(multi == true) {
		var category = '';
	}
	
	jQuery("#" + id + " option:selected").each(function() {
		if(multi == true) {
			category += jQuery(this).val() + ',';
		} else {
			category = jQuery(this).val();
		}
	});
	
	// Check if all categories option is selected in the options list.
	if(category.indexOf('-1') == '-1') {
		SetFields(category, multi);
	} else {
		SetFields('-1', multi);
	}
}

function SetFields(category, multi = false) {
	if(multi == true) {
		if(category == '-1') {
			jQuery('div.field.categorydepends.control-group').each(function(index, element) {
				jQuery(element).css('display', 'block');
				Required(element);
			});
		} else {
			jQuery('div.field.categorydepends.control-group').filter(function(i, e) {
				var InCats = jQuery(this).attr('in-cats-array');
				
				if(category.indexOf(InCats) == '-1') {
					if(InCats == '-1') {
						jQuery(this).css('display', 'block');
						Required(e);
					} else {
						jQuery(this).css('display', 'none');
						Required(e, false);
					}
				} else {
					jQuery(this).css('display', 'block');
					Required(e);
				}
			});
		}
	} else {
		jQuery('div.field.categorydepends.control-group').each(function(index, element) {
			var InCats = jQuery(element).attr('in-cats-array');
			
			if(InCats == '-1') {
				jQuery(element).css('display', 'block');
				Required(element);
			} else {
				if(jQuery.inArray(category, [InCats]) == '-1') {
					jQuery(element).css('display', 'none');
					Required(element, false);
				} else {
					jQuery(element).css('display', 'block');
					Required(element);
				}
			}
		});
	}
}

// That makes the field required or not.
function Required(e, setrequired = true) {
	var cls = jQuery(e).attr('class');
	
	if(setrequired == false) {
		jQuery("." + cls.replace(/ /g, '.') + " .controls .required").removeAttr('required').removeClass('required').addClass('req').attr('req', '1');
	} else {
		jQuery("." + cls.replace(/ /g, '.') + " .controls .req").attr('required', 'required').addClass('required').removeClass('req').removeAttr('req');
	}
}
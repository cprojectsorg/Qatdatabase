function categoryChange(id, multi = false) {
	var Element = jQuery('#' + id);
	var MaxCats = Element.attr('max-cats');
	
	if(multi == true) {
		var category = '';
	}
	
	// Check how many categories selected.
	if(multi == true && MaxCats !== 0) {
		// If exceeded the maximum limit unselect the last selected category/categories.
		if(jQuery('#' + id + ' option:selected').length > MaxCats) {
			Value = Element.val().slice(0, MaxCats);
			Element.val(Value);
		}
	}
	
	jQuery('#' + id + ' option:selected').each(function() {
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
						if(InCats.indexOf(',') == '-1') {
							jQuery(this).css('display', 'none');
							Required(e, false);
						} else {
							count = 0;
							jQuery(InCats.split(',')).each(function(index, value) {
								if(category.indexOf(value) == '-1') {
									// Continue 'each' loop.
									return true;
								} else {
									count++;
									
									// Break 'each' loop.
									return false;
								}
							});
							
							if(count == 0) {
								jQuery(this).css('display', 'none');
								Required(e, false);
							} else {
								jQuery(this).css('display', 'block');
								Required(e);
							}
						}
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
				if(jQuery.inArray(category, InCats.split(',')) == '-1') {
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

function ImageInput(id, labelid, max) {
	var FilesNumber = document.getElementById(id).files.length;
	
	if(FilesNumber > 1) {
		var Selected = Joomla.JText._('COM_QATDDATABASE_ITEM_FIELD_IMAGES_SELECTED', 'Images selected');
	} else {
		var Selected = Joomla.JText._('COM_QATDDATABASE_ITEM_FIELD_IMAGE_SELECTED', 'Image selected');
	}
	
	if(FilesNumber !== 0) {
		if(FilesNumber >= max) {
			document.getElementById(labelid).innerText=(max + ' / ' + max + ' ' + Selected);
		} else {
			document.getElementById(labelid).innerText=(FilesNumber + ' / ' + max + ' ' + Selected);
		}
	} else {
		document.getElementById(labelid).innerText=(Joomla.JText._('COM_QATDATABASE_ITEM_ADD_IMAGES', 'Add images'));
	}
}
function toggle_not_found(klass){
  if(jQuery(klass).length == 0){
    jQuery('.fr-not-found').show()
  }else{
    jQuery('.fr-not-found').hide()
  }           
}

jQuery(document).ready(function(){
  'use strict';


  if(jQuery('#fr-embed-code').length > 0){
    var textBox = document.getElementById("fr-embed-code");
    textBox.onfocus = function() {
        textBox.select();

        // Work around Chrome's little problem
        textBox.onmouseup = function() {
            // Prevent further mouseup intervention
            textBox.onmouseup = null;
            return false;
        };
    };
  }

	jQuery('#fr-tabs div.tab-div').hide();
	jQuery('#fr-tabs div:first').show();
	jQuery('#fr-tabs ul li:first').addClass('active');
	 
	jQuery('#fr-tabs ul li a').click(function(){
		jQuery('#fr-tabs ul li').removeClass('active');
		jQuery(this).parent().addClass('active');
		var currentTab = jQuery(this).attr('href');
		jQuery('#fr-tabs div.tab-div').hide();
		jQuery(currentTab).show();
		return false;
	});

});


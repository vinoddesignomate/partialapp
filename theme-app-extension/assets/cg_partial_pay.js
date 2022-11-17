var jQuery = $.noConflict(true);

jQuery( document ).ready(function() {  // ERROR!  jQuery library hasn't been run yet, so we can't use jQuery to select the document!

    jQuery('#add-btn').on('click', function(event){
     alert("clicked")
    }) 
  });



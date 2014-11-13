(function($) {
  "use strict";
  jQuery(document).ready(function($){
    $('div.col-sm-3 > ul').addClass('list-unstyled');
    $('input[type="submit"]').addClass('btn btn-default');
    
    $(body).on('onload',function(){
      alert('hi annad');
    });
    
    //$('#logo,#header-social').height($('#logo_image').height());
    
  
  });
  
  $(window).load(function() {
      alert("window load occurred!");
  });
  
  alert('annad');
  
})(jQuery);
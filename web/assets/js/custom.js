(function($) {
  "use strict";
  jQuery(document).ready(function($){
    $('div.col-sm-3 > ul').addClass('list-unstyled');
    $('input[type="submit"]').addClass('btn btn-default');
  });
  
  $(window).load(function() {
      $('#logo,#header-social').height($('#logo_image').height());
  });
  
  
  
})(jQuery);
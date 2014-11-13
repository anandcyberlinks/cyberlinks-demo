(function($) {
  "use strict";
  jQuery(document).ready(function($){
    $('div.col-sm-3 > ul').addClass('list-unstyled');
    $('input[type="submit"]').addClass('btn btn-default');
    
    //var height = $('#logo_image').height();
    $('#logo,#header-social').height($('#logo_image').height());
    //alert('hi anand');
  
  });
})(jQuery);
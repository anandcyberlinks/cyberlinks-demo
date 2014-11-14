(function($) {
  "use strict";
  jQuery(document).ready(function($){
    $('div.col-sm-3 > ul').addClass('list-unstyled');
    $('input[type="submit"]').addClass('btn btn-default');
  });
  
  $(window).load(function() {
      var hi = $('#logo_image').height() < 80 ? 80 : $('#logo_image').height();
      $('#logo,#header-social').height(hi);
  });
})(jQuery);
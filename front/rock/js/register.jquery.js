$(document).ready(function(){
  $('#contact').validate({
    rules:{
      'name':{
        'required':true,
        'minlength':3
      },
      'email':{
        'required':true,
        'email':true
        
      },
      'coment':{
        'required':true,
      },
        
    },

    
    
    

  });
  
  $('#joinus').validate({
    rules:{
      'name':{
        'required':true,
        'minlength':3
      },
      'mobile':{
        'required':true,
        'minlength':10,
        'number':true
        
      },
      'email':{
        'required':true,
        'email':true,
      },
        
    },

    
    
    

  });
});

function SubmitIfValid(){
    if(!$("#contact").valid()) 
    { return false; }else{ 
	var postdata = $("#contact").serialize();
	var posturl = $("#contact").attr('action');
	$("#msg").html('Please wait.....');
	$.post( posturl, postdata, function( data ) {
			$( "#msg" ).html( data );
                        $('#contact')[0].reset();
			
	});
	
    return false;
    
    
    }
}
function SubmitIfValid1(){
    if(!$("#joinus").valid()) 
    { return false; }else{ 
	var postdata = $("#joinus").serialize();
	var posturl = $("#joinus").attr('action');
	$("#msg1").html('Please wait.....');
	$.post( posturl, postdata, function( data ) {
			$( "#msg1" ).html( data );
			setTimeout(function(){ window.location='index.html'; }, 1500);
			
	});
	
    return false;
    
    
    }
}


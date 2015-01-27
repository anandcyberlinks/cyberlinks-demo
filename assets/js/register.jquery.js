$(document).ready(function(){
    
    /*if($('#age_group_from').val()!="")
    {
        $.validator.addMethod("greaterThan", function(value, element) {
        var startdatevalue = $('#age_group_from').val();
        return parseInt(startdatevalue) < parseInt(value);
        }, "Must be greater than to Age Group from.");
    }*/


    $.validator.addMethod("greaterThan", function (value, element, param) {
    var $element = $(element)
        , $min;

    if (typeof(param) === "string") {
        $min = $(param);
    } else {
        $min = $("#" + $element.data("min"));
    }

    if (this.settings.onfocusout) {
        $min.off(".validate-greaterThan").on("blur.validate-greaterThan", function () {
            $element.valid();
        });
    }
    return parseInt(value) >= parseInt($min.val());
}, "Must be greater than to Age Group from.");

$.validator.addClassRules({
    greaterThan: {
        greaterThan: true
    }
});


  $('#registerId').validate({
    rules:{
      'uname':{
        'required':true,
        'minlength':5
      },
      'url':{
        'required':true,
      },
      'title':{
        'required':true,
      },
      'number':{
        'required':true,
        'number' :true,
      },
      'first_name':{
        'required':true,
        'minlength':4
      },
      'email':{
        'required':true,
        'email':true,
        'remote':'checkemail'
      },
        'package_name':{
        'required':true,
        'minlength':4,
        'remote':'checkpackage'
      },
      
      'form_name':{
        'required':true,
        'remote':'checkform'
      },
      'name':{
        'required':true,
        'minlength':4,
        'maxlength':30
      },
      'description':{
        'required':true,
        'minlength':4,
      },
      
      'start_date':{
        'required':true,
      },
      
      'password':{
        'required':true,
        'minlength':6
      },
      'cpassword':{
        'required':true,
        'equalTo':'#password'
      },
      'gender':{
        'required':true
      },
      'username':{
        'required':true,
        'remote':'checkusername'
      },
      'language':{
        'required':true,
      },
      'role_id':{
        'required':true
      },
      'days':{
        'required':true,
        'number' :true
      },
      'price':{
        'required':true,
        'number' :true
      },
      'avatar':{
        'required':true,
        'accept':'jpg|jpeg|png|ini'
      },
      'package_tittle':{
        'required':true,
        'minlength':6 
      },
      'agree':{
        'required':true 
      },
      'age_group_from':{
        'number' :true
        //'le': '#age_group_to'
      },
      'age_group_to':{
        'number' :true
        // 'greaterThan': true
      },
    },

  messages: {
    'fisrt_name':{
      required: "First Name is required.",
      minlength: "First Name should be 4 char long.",
    },
    'email':{
      'required':'Email should not be blank',
      'email':'Please enter valid email Id',
      'remote':'Email already taken'
    },
    
    'form_name':{
      'remote':'Form Name already taken'
    },
      'package_name':{
      'required':'Package should not be blank',
      'remote':'Already Exist Please try Diffrent'
    },
    'days':{
      'number':'Please enter valid duration in Days'
    },
    'price':{
      'number':'Please enter valid price in Rupees',
    },
    'password':{
      'required':'Password should not be blank',
      'minlength':"Passowrd should be 6 char long."
    },
    'cpassword':{
      'required':'Re-Password should not be blank',
      'equalTo':'Password not matched'
    },
    'username':{
        'required':'Username is required',
        'remote':'Username already taken'
    },
    'avatar':{
      'required':'Please upload Avatar',
      'accept':"Please upload file with 2 extn."
    },
    'age_group_from':{
      'number':'Please enter valid Age.'
    },
    'age_group_to':{
      'number':'Please enter valid Age.'
    },
  }

  });
  
  
    $('#SubscriptionForm').validate({
    rules:{
      'title':{
        'required':true,
        'minlength':4
      },     
    },

  messages: {
    'title':{
      required: "Title is required.",
      minlength: "Title should be 4 char long."
    }
  }

  });
  
  $('#Punchang').validate({
    rules:{
      'date':{
        'required':true,
        'remote':'checkemail'
      },
      'month':{
        'required':true
      },
      'pakshya':{
        'required':true
      },
      'tithi':{
        'required':true
      }
    },
    messages: {
    'date':{
      required: "Date is required.",
      remote: "Data already added for this date"
    }
  }
  });

   $('#frmstream').validate({
    rules:{
      'youtube':{
        'required':true
      },
    },

  messages: {
    'title':{
      required: "Title is required.",
      minlength: "Title should be 4 char long."
    }
  }

  });
});

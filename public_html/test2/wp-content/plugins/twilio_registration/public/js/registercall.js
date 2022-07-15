jQuery(document).ready(function(){
    jQuery("#submitbtn").click(function () {
        if(jQuery('#div1').length){
            alert("Div1 exists");
        }
        var code = jQuery('#ccode').val();
        var phone = jQuery('#phone_number').val();
        var username = jQuery('#username').val();
        var firstname = jQuery('#firstname').val();
        var lastname = jQuery('#lastname').val();
         var email = jQuery('#dsp-email').val();
         var confirm_email = jQuery('#confirm_email').val();
        if(jQuery('#dsp-password').length){
            var password = jQuery('#dsp-password').val();
            var repassword = jQuery('#re-password').val();
            var arr = [phone,username,firstname,lastname,email,confirm_email,password,repassword];
        }
        else{
            var arr = [phone,username,firstname,lastname,email,confirm_email];
        }


         var error = emptyValidate(arr);
         if (error != '') {
             jQuery('#result').html("<div class='alert alert-danger' tabindex='-1'>"+error+"</div>");
             document.body.scrollTop = 0;
             document.documentElement.scrollTop = 0;

         }else{
            if (isNaN(phone)) {
                jQuery('#result').html("<div class='alert alert-danger' tabindex='-1'>Enter valid Phone number</div>");
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            }else{
                var data = {
                    'action': 'validate_username_email_exist_action',
                    'username' : username,
                    'email' : email,
                }
                jQuery.post(my_ajax_object.ajax_url, data, function (response) {
                    if (response){
                        validatePhone();
                    }else{
                        jQuery('#result').html("<div class='alert alert-danger' tabindex='-1'>"+response+"</div>");
                        document.body.scrollTop = 0;
                        document.documentElement.scrollTop = 0;
                    }
                });
            }

        }
        function validatePhone(){
            var phone_number = code + phone;
            var data1 = {
                'action': 'validate_phone_action',
                'phone_number': phone_number,
            };
            jQuery.post(my_ajax_object.ajax_url, data1, function (response) {
                if (response) {
                    sendMsg();
                } else{
                    jQuery('#result').html("<div class='alert alert-danger'>Phone number already used</div>");
                    document.body.scrollTop = 0;
                    document.documentElement.scrollTop = 0;
                }
            });
        }
        function sendMsg(){
            var phone_number = code + '' + phone;
            var data = {
                'action': 'sendmsg_action',
                'phone_number': phone_number,
            };
            jQuery.post(my_ajax_object.ajax_url, data, function (response) {
                if (response) {
                    jQuery('#verificationModal').modal('show');
                } else {
                    jQuery('#result').html("<div class='alert alert-danger'>Error occured</div>");
                    document.body.scrollTop = 0;
                    document.documentElement.scrollTop = 0;
                }
            });
        }
        function emptyValidate(field){
            var err ='';
            for (var i = 0; i <= field.length - 1; i++) {

                if (field[i] == '') {
                    err = "Field should not be empty";
                    break;
                }
            }
            return err;
        }
    });

});








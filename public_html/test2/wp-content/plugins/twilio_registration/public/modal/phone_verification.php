<script>
    function submitContactForm(){

        var phone_number = jQuery('#ccode').val()+''+jQuery('#phone_number').val();
        var code = jQuery('#code').val();

        var data = {
            'action'        : 'verification_action',
            'phone_number'  :  phone_number,
            'code'          :  code
        };

        jQuery.post(my_ajax_object.ajax_url, data, function(response) {
            if (response == 1) {
                jQuery('#verificationModal').modal('hide');
                jQuery("#formsubmit").click();
            }else{
                document.getElementById("error").innerHTML = response;
                jQuery('#code').val('');
                jQuery('#verificationModal').modal('show');
            }
        });

    }
</script>
<div class="modal fade" id="verificationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Verification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="verificationForm" action="" method="post">
                    <div class="form-group">
                        <label for="code" class="col-form-label">Enter Code:</label>
                        <textarea class="form-control" id="code" name="code"></textarea>
                        <div id="error"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">

                <input type="button" class="btn btn-secondary" name="verify" id="verify" onclick="submitContactForm()" value="Verify">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>


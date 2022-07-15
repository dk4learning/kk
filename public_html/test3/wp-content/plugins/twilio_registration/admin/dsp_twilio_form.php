<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$success = '';
$fail = '';
    if (isset($_POST['save'])) {
        $phone_number = $_POST['phone_number'];
        $auth_token = $_POST['auth_token'];
        $sid_key = $_POST['sid_key'];
        $twi = update_option('twilio_phone',$phone_number);
        $auth = update_option('auth_token',$auth_token);
        $sid = update_option('sid_key',$sid_key);
        $success = "Saved Succesfully";
    }
    ?>
    <div class="wrap"><h2>Twilio Detail Form</h2></div>
    <div class="box box-primary">
        <form role="form" action="" method="post">
            <?php if (isset($success) &&!empty($success)) { ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php } if (isset($fail) && !empty($fail)) { ?>
                <div class="alert alert-danger"><?php echo $fail; ?></div>
            <?php } ?>
            <div class="box-body">
                <div class="form-group">
                    <label for="phone_number">PHONE NUMBER</label>
                    <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="Enter phone number" value="<?php echo get_option('twilio_phone'); ?>" required />
                </div>
                <div class="form-group">
                    <label for="sid_key">SID Key</label>
                    <input type="text" class="form-control" name="sid_key" id="sid_key" placeholder="Enter sid key" value="<?php echo get_option('sid_key'); ?>" required />
                </div>
                <div class="form-group">
                    <label for="auth_token">AUTH Token</label>
                    <input type="text" class="form-control" name="auth_token" id="auth_token" placeholder="Enter auth token" value="<?php echo get_option('auth_token'); ?>" required />
                </div>
                <div class="box-footer">
                    <input type="submit" class="btn btn-primary" id="save" name="save" value="Save" />
                </div>
        </form>
    </div>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
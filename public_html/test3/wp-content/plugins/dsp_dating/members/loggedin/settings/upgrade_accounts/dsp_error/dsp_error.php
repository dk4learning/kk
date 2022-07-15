<div class="box-border">
    <div class="box-pedding">
        <?php
        if (isset($_REQUEST['RESPMSG'])) {
            echo "<br>" . $_REQUEST['RESPMSG'];
        }
        if (isset($_REQUEST['errormessage'])) {
            echo "<br>" . $_REQUEST['errormessage'];
        }
        echo "<br>" . __('Sorry Your transaction could not completed successfully', 'wpdating');
        ?>
    </div>
</div>

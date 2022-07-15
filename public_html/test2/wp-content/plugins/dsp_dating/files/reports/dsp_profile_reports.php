<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$dsp_gender_list = $wpdb->prefix . DSP_GENDER_LIST_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

$count_total_males = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_user_profiles WHERE gender='M'");
$count_total_females = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_user_profiles WHERE  gender='F'");
$count_total_couples = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_user_profiles WHERE  gender='C'");
$gender_table_row = $wpdb->get_results("select * from $dsp_gender_list where enum NOT IN ('M','F','C')");
// var_dump($gender_table_row);
foreach ($gender_table_row as $gender) {
    $count_total_custom     = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_user_profiles WHERE  gender='$gender->enum'");
    if ($count_total_custom>0) {
        $custom_gender          .= '*'.$count_total_custom;
        $custom_gender_name     .= '*'.$gender->gender;
    }
    
}

?>
<div id="general" class="postbox">
    <h3 class="hndle"><span><?php echo __('Profiles', 'wpdating') ?></span></h3>
    <div style="margin:20px">
        <table border="0" style="padding: 40px;width: 480px;" >
            <tr>
                <td >
                    <table>
                        <tr>
                            <td>
                                <img src="<?php echo WPDATE_URL; ?>/files/dsp_piechart.php?data=<?php echo $count_total_males ?>*<?php echo $count_total_females ?>*<?php echo $count_total_couples.$custom_gender ?>&label=Male*Female*couples<?php echo $custom_gender_name?>" /> 
                                <div style="padding-left:70px; padding-top:13px; color:#819B45; font-size:18px; font-weight:bolder;">Profile Breakdown</div>

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>


        </table>
    </div>
</div>
<br />
<table width="490" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
        <td width="490" height="61" valign="top">&nbsp;</td>
    </tr>
</table>
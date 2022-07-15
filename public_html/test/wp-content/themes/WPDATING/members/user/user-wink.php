<?php
/* -------------------------------------------------
  BlankPress - Default Page Template
  -------------------------------------------------- */

include('../../header.php');
include('../../functions.php');

?>


<?php


$delwinks_msg_id= get('del_wink_id');
$Actiondel= get('Action');
if(($delwinks_msg_id!="") && ($Actiondel=="Del"))
{
$wpdb->query("DELETE FROM $dsp_member_winks_table where wink_mesage_id  = '$delwinks_msg_id'");
}
$request_Action=get('Act');
if(($request_Action=="R")) {
	 $wpdb->query("UPDATE $dsp_member_winks_table SET wink_read='Y' where receiver_id='$user_id'");
}
if($check_couples_mode->setting_status == 'Y'){
$total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_member_winks_table winks, $dsp_user_profiles profile WHERE winks.sender_id = profile.user_id AND winks.receiver_id = '$user_id'");
} else {
$total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_member_winks_table winks, $dsp_user_profiles profile WHERE winks.sender_id = profile.user_id AND winks.receiver_id = '$user_id' AND profile.gender!='C'");
//$total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_member_winks_table where receiver_id ='$user_id'");
}
?>
<?php
if($total_results1>0) { ?>
<div class="box-border">
<div class="box-pedding">
<div class="dsp_vertical_scrollbar">
<div class="heading-submenu">My Winks</div>
<div class="box-page">
<form name="delwinktextfrm" action="" method="post">
<?php
if($check_couples_mode->setting_status == 'Y'){
$my_winks = $wpdb->get_results("SELECT * FROM $dsp_member_winks_table winks, $dsp_user_profiles profile WHERE winks.sender_id = profile.user_id
AND winks.receiver_id = '$user_id' ORDER BY winks.send_date");
} else {
$my_winks = $wpdb->get_results("SELECT * FROM $dsp_member_winks_table winks, $dsp_user_profiles profile WHERE winks.sender_id = profile.user_id
AND winks.receiver_id = '$user_id' AND profile.gender!='C' ORDER BY winks.send_date");
}
foreach($my_winks as $winks)
{
$wink_msg_id = $winks->wink_mesage_id;
$wink_sender_id = $winks->sender_id;
$wink_id = $winks->wink_id;
$exist_wink_message = $wpdb->get_row("SELECT * FROM $dsp_flirt_table WHERE Flirt_ID = '$wink_id'");
$sender_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$wink_sender_id'");
$message_sent_date=date("d/m/Y h:i",strtotime($winks->send_date));
$favt_mem=array();
$private_mem=$wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$wink_sender_id'");
		foreach ($private_mem as $private) {
	   $favt_mem[]=$private->favourite_user_id;
		}
?>
<ul class="details-row">
<li class="image-box">
<?  if($check_couples_mode->setting_status == 'Y'){
			if($winks->gender=='C')
			{
		   ?>
           <? if($winks->make_private =='Y'){  ?>
		    <? if(!in_array($current_user->ID, $favt_mem)) { ?>
                <a href="<?php echo $root_link.get_username($wink_sender_id)."/my_profile/"; ?>" >
                <img src="<?php echo $imagepath?>plugins/dsp_dating/images/private-photo-pic.jpg" style="width:60px; height:60px;" class="img2" />
                </a>
                <? }
				else {?>
				<a href="<?php echo $root_link.get_username($wink_sender_id)."/my_profile/"; ?>" >
				<img src="<?php echo display_members_photo($wink_sender_id,$imagepath); ?>"  style="width:60px; height:60px;" class="img2"/></a>
                <?php }
				 } else { ?>

		   <a href="<?php echo $root_link.get_username($wink_sender_id)."/my_profile/"; ?>">
		   <img src="<?php echo display_members_photo($wink_sender_id,$imagepath); ?>" style="width:60px; height:60px;" class="img2" />
            </a>
			<? }  ?>


			<? } else { ?>

           <? if($winks->make_private =='Y'){  ?>

            <? if(!in_array($current_user->ID, $favt_mem)) { ?>
                <a href="<?php echo $root_link.get_username($wink_sender_id)."/"; ?>" >
                <img src="<?php echo $imagepath?>plugins/dsp_dating/images/private-photo-pic.jpg" style="width:60px; height:60px;" class="img2" />
                </a>
                <? }
				else {?>
				<a href="<?php echo $root_link.get_username($wink_sender_id)."/"; ?>" >
				<img src="<?php echo display_members_photo($wink_sender_id,$imagepath); ?>" style="width:60px; height:60px;" class="img2"/></a>
                <?php }
				 } else { ?>

           <a href="<?php echo $root_link.get_username($wink_sender_id)."/"; ?>">
           <img src="<?php echo display_members_photo($wink_sender_id,$imagepath); ?>" style="width:60px; height:60px;" class="img2" />
            </a>
			<? }  ?>

		 <?    }
		   } else { ?>
           <? if($winks->make_private =='Y'){  ?>

            <? if(!in_array($current_user->ID, $favt_mem)) { ?>
                <a href="<?php echo $root_link.get_username($wink_sender_id)."/"; ?>" >
                <img src="<?php echo $imagepath?>plugins/dsp_dating/images/private-photo-pic.jpg" style="width:60px; height:60px;" class="img2" />
                </a>
                <? }
				else {?>
				<a href="<?php echo $root_link.get_username($wink_sender_id)."/"; ?>" >
				<img src="<?php echo display_members_photo($wink_sender_id,$imagepath); ?>"   style="width:60px; height:60px;" class="img2"/></a>
                <?php }
				 } else { ?>

           <a href="<?php echo $root_link.get_username($wink_sender_id)."/"; ?>">
           <img src="<?php echo display_members_photo($wink_sender_id,$imagepath); ?>" style="width:60px; height:60px;" class="img2" />
            </a>
			<? }  ?>
		<? } ?>
</li>
<li class="mid"> <p class="dsp_page_link title"><strong>
		<?  if($check_couples_mode->setting_status == 'Y'){
			if($winks->gender=='C')
			{
		   ?>
		   <a href="<?php echo $root_link.get_username($wink_sender_id)."/my_profile/"; ?>"><?=$sender_name->display_name?></a>
			<? } else { ?>
			   <a href="<?php echo $root_link.get_username($wink_sender_id)."/"; ?>"><?=$sender_name->display_name?></a>
		 <?    }
		   } else { ?>
		<a href="<?php echo $root_link.get_username($wink_sender_id)."/"; ?>"><?=$sender_name->display_name?></a>
		<? } ?>

		</strong></p>
<p class="description"><?=$exist_wink_message->flirt_Text?></p>
<p class="date"><?=$message_sent_date?></p>
</li>
	 <li class="last"><a href="<? echo $root_link."home/view_winks/Action/Del/del_wink_id/".$wink_msg_id;?>" class="dsp_span_pointer"><?=language_code('DSP_DELETE_LINK');?>
	<!--<img src="<?php //echo $pluginpath?>user_photos/b_drop.png" width="16" height="16" alt="Edit" border="0">--></a></li>
</ul>
<? unset($favt_mem); } // foreach($my_winks as $winks) ?>
</form>
</div>
</div>

</div>
</div>
<? } else { ?>
<div class="box-border">
<div class="box-pedding">
<div class="dsp_vertical_scrollbar">
<div style=" text-align:center;" class="box-page">
<strong><?=language_code('DSP_NO_WINK_MSG')?></strong>
</div>
</div>
</div>
</div>

<? } ?>

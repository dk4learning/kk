
<div role="banner" class="ui-header ui-bar-a" data-role="header">
     <?php include_once("page_menu.php");?> 
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo __('Search', 'wpdating'); ?></h1>
     <?php include_once("page_home.php");?> 

</div>


<div class="ui-content" data-role="content">
    <div class="content-primary">	 

        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all menu-list">

           
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                <a  onclick="callSearch('basic_search')">
                <img src="images/icons/search.png" />
                    <?php echo __('Search', 'wpdating'); ?>
                     </a>
                </li>
           
           
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                    <a onclick="callSearch('advance_search')">
                     <img src="images/icons/advsearch.png" />
                    <?php echo __('Advanced', 'wpdating'); ?>	
                     </a>
                </li>
           
            <?php if ($check_zipcode_mode->setting_status == 'Y') { ?>
               
                    <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                        <a onclick="callSearch('zipcode_search')">
                           <img src="images/icons/zip.png" />
                       <?php echo __('Zip Code', 'wpdating'); ?>
                        </a>
                    </li>
               
            <?php } ?>
           
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                     <a onclick="callSearch('save_searches')">
                        <img src="images/icons/save.png" />
                    <?php echo __('Save Searches', 'wpdating'); ?>
                    </a>
                </li>
            



        </ul>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up   ?>
</div>	
<?php include_once("dspLeftMenu.php"); ?>
<?php 
/*
=================================================
Lavish Search Form
Includes the Search Form On Front End
=================================================
*/

if (is_front_page()) {
    do_action('wpdating_search_form');
}
else {
    //do nothing
}
?>

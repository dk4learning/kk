<?php
/**
 * The Inset Top Sidebar
 * @package lavish
 * @since 1.0.0
 */

if ( ! is_active_sidebar( 'insettop' ) ) {
	return;
}
?>
<div class="lavish_date_widget_insettop">
    <div class="container">
        <div class="row">
           	<div class="col-md-12">
           		<?php dynamic_sidebar( 'insettop' ); ?>
        	</div>
        </div>
    </div>
</div>
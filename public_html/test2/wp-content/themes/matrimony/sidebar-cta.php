<?php
/**
 * The Call to Action Sidebar
 * @package lavish
 * @since 1.0.0
 */

if ( ! is_active_sidebar( 'cta' ) ) {
	return;
}
?>
<div class="lavish_date_widget_cta" >
	<div class="container">
        <div class="row">
           	<div class="dsp-md-12">
           		<?php dynamic_sidebar( 'cta' ); ?>
        	</div>
        </div>
    </div>
</div>
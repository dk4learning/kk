<?php
/**
 * The Inset Bottom Sidebar
 * @package lavish
 * @since 1.0.0
 */

if ( ! is_active_sidebar( 'insetbottom' ) ) {
	return;
}
?>

<div class="lavish_date_widget_inset_bottom">
    <div class="container">
        <div class="row">
           	<div class="col-md-12">
           		<?php dynamic_sidebar( 'insetbottom' ); ?>
        	</div>
        </div>
    </div>
</div>
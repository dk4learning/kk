<?php
/**
 * The Inset Top Sidebar
 * @package lavish
 * @since 1.0.0
 */

if ( ! is_active_sidebar( 'insetfull' ) ) {
	return;
}
?>

<div class="lavish_date_widget_insetfull">
	<?php dynamic_sidebar( 'insetfull' ); ?>
</div>
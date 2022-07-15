<?php
/*
=================================================
This is a Register Page For Dsp Dating Plugin.
=================================================
*/
?>
<div class="dsp-row">
	<div class="dsp-md-8 lavish_register_box">
		<h2 class="special_heading"><strong><?php echo __('Register', 'lavish-date'); ?></strong></h2>
		<hr/>
		<?php echo do_shortcode('[dsp_register]'); ?>
	</div>

	<div class="dsp-md-4">
		<?php dynamic_sidebar( 'reg_widget' ); ?>
	</div>
</div>

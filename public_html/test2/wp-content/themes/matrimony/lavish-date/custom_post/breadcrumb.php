<?php
add_action('style_breadcrumb', 'lavish_date_breadcrumb_fnc');

function lavish_date_breadcrumb_fnc() {
	?>
	<div class="style_breadcrumbs">
		<div class="container">
			<?php	
			if(function_exists('bcn_display')) {
                bcn_display();
            }
        ?>
        </div>
	</div>
<?php } ?>
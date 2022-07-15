<?php
/*-------------------------------------------------
	BlankPress - Default Sidebar Template
 --------------------------------------------------*/
?>

<?php 
function flat_responsive_topgroup() {
 $count = 0;
 if ( is_active_sidebar( 'about_us_widget' ) )
  $count++;
 if ( is_active_sidebar( 'custom_text_widget_1' ) )
  $count++;
 if ( is_active_sidebar( 'custom_text_widget_2' ) )
  $count++;  
 if ( is_active_sidebar( 'custom_text_widget_3' ) )
  $count++;
 $class = '';

	 switch ( $count ) {
	  case '1':
	   $class = 'dsp-md-12';
	   break;
	  case '2':
	   $class = 'dsp-md-6';
	   break;
	  case '3':
	   $class = 'dsp-md-4';
	   break;
	  case '4':
	   $class = 'dsp-md-3';
	   break;
	 }
	if ( $class )
  echo 'class="' . $class . '"';
}
if (   ! is_active_sidebar( 'about_us_widget'  )
 && ! is_active_sidebar( 'custom_text_widget_1' )
 && ! is_active_sidebar( 'custom_text_widget_2'  )
 && ! is_active_sidebar( 'custom_text_widget_3'  )  
 )

  return;
 // If we get this far, we have widgets. Let do this.
?>

<div class="dsp-row">
	<div class="container">
	<?php if (is_active_sidebar( 'about_us_widget')) { ?>
		<div <?php echo flat_responsive_topgroup(); ?>>
	    	<aside class="sidebar footer-sidebar">
		  	  <?php dynamic_sidebar( 'about_us_widget' ); ?>
	   		</aside>
		</div>
	<?php } ?>

	<?php if (is_active_sidebar( 'custom_text_widget_1')) { ?>
		<div <?php echo flat_responsive_topgroup(); ?>>
		    <aside class="sidebar footer-sidebar">
		    	<?php dynamic_sidebar( 'custom_text_widget_1' ); ?>
		    </aside>
		</div>
	<?php } ?>

	<?php if (is_active_sidebar( 'custom_text_widget_2')) { ?>
		<div <?php echo flat_responsive_topgroup(); ?>>
		    <aside class="sidebar footer-sidebar">
			    <?php dynamic_sidebar( 'custom_text_widget_2' ); ?>
		    </aside>
		</div>
	<?php } ?>

	<?php if (is_active_sidebar( 'custom_text_widget_3')) { ?>
		<div <?php echo flat_responsive_topgroup(); ?>>
	   		<aside class="sidebar footer-sidebar">
	    		<?php dynamic_sidebar( 'custom_text_widget_3' ); ?>
	    	</aside>
		</div>
	<?php } ?>
</div>
</div>


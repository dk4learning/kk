<?php

/**
 * Social bar group
 * @package lavish_date
 * @since 1.0.0
 */
?>

<?php if( get_theme_mod( 'hide_social' ) == '') { ?>
		<?php $options = get_theme_mods();						
             
			echo '<div id="social-icons"><ul>';										
			if (!empty($options['twitter_uid'])) echo '<li><a title="Twitter" href="' . $options['twitter_uid'] . '" target="_blank"><i class="fa fa-twitter"></i></a></li>';
			if (!empty($options['facebook_uid'])) echo '<li><a title="Facebook" href="' . $options['facebook_uid'] . '" target="_blank"><i class="fa fa-facebook"></i></a></li>';
			if (!empty($options['google_uid'])) echo '<li><a title="Google+" href="' . $options['google_uid'] . '" target="_blank"><i class="fa fa-google-plus"></i></a></li>';			
			if (!empty($options['pinterest_uid'])) echo '<li><a title="Pinterest" href="' . $options['pinterest_uid'] . '" target="_blank"><i class="fa fa-pinterest"></i></a></li>';
			if (!empty($options['flickr_uid'])) echo '<li><a title="Flickr" href="' . $options['flickr_uid'] . '" target="_blank"><i class="fa fa-flickr"></i></a></li>';
			if (!empty($options['youtube_uid'])) echo '<li><a title="Youtube" href="' . $options['youtube_uid'] . '" target="_blank"><i class="fa fa-youtube"></i></a></li>';
			if (!empty($options['instagram_uid'])) echo '<li><a title="Instagram" href="' . $options['instagram_uid'] . '" target="_blank"><i class="fa fa-instagram"></i></a></li>';		
		echo '</ul></div>';		
}
?>
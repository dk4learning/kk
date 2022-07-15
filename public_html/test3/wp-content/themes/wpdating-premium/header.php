<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WPDating_Premium
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
</head>
	
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'wpdating-premium' ); ?></a>

	<header class="site-header" id="masthead">

        <div class="content-wrap d-flex space-bet align-center">

            <div class="left-content">
			<div class="logo-wrap">
					<?php			
						$logo = cs_get_option('default-logo');
						$high_logo = cs_get_option( 'retina-logo' );
						if( $logo || $high_logo ) :
							$logo_img = wp_get_attachment_image_src( $logo, 'full' );
							$high_res = wp_get_attachment_image_src( $high_logo, 'full' );
							?>

							<a class="navbar-brand" href="<?php echo esc_url( home_url('/') ); ?>">
								<img src="<?php echo esc_url( $logo_img[0] ); ?>" alt="<?php bloginfo('name'); ?>">
							</a>

							<?php if($high_res[0]): ?>
								<a class="navbar-brand high-res" href="<?php echo esc_url( home_url('/') ); ?>">
									<img src="<?php echo esc_url( $high_res[0] ); ?>" alt="<?php esc_attr( bloginfo('name' ) ); ?>">
								</a>
							<?php endif; ?>
							<?php
							else:
								?>
								<h1 id="logo">
									<a class="navbar-brand" href="<?php echo esc_url( home_url('/') ) ?>">
										<?php esc_html( bloginfo('name') ); ?>
									</a>
								</h1>
								<?php
							endif;
						$wpdating_premium_description = get_bloginfo( 'description', 'display' );
						if ( $wpdating_premium_description || is_customize_preview() ) :
							?>
							<p class="site-description"><?php echo $wpdating_premium_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
						<?php endif; ?>
				    </div>
                <div class="ham-icon">
                    <span></span>
                </div>
            </div>

            <div class="right-content d-flex align-center">
				<nav id="site-navigation" class="main-navigation main-menu no-dot d-flex align-center">
					<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'wpdating-premium' ); ?></button>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
						)
					);
					?>
				</nav>
            	<div class="login-wrap">
					<?php echo do_shortcode('[wpee_header_login]');?>
				</div>
                <div class="lang-wrap"></div>
            </div>

        </div>

    </header>


	
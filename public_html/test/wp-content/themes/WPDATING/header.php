<?php

/* -------------------------------------------------

  BlankPress - Default Header Template

  -------------------------------------------------- */

$option = get_option( 'BP_options', false );

$frontpage_display = get_theme_mod( 'frontpage_display' );

$userData = $_POST;

$error = null;

if ( ! empty( $userData ) && array_key_exists( 'user_login', $userData ) && array_key_exists( 'user_password', $userData ) ) {

	$username = $userData['user_login'];

	$password = $userData['user_password'];

	if ( $username != '' && $password != '' ) {

		$user = wp_signon( $userData, false );

		if ( is_wp_error( $user ) ) {
			$error = 'Username/Password is incorrect';
		} else {
			wp_redirect( site_url( 'members' ) );
		}

	}

}


?>

<!doctype html>

<!--[if lt IE 7]>
<html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->

<!--[if IE 7]>
<html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 ie7"> <![endif]-->

<!--[if IE 8]>
<html <?php language_attributes(); ?> class="no-js lt-ie9 ie8"> <![endif]-->

<!--[if gt IE 8]><!-->
<html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->

<head>

	<?php theme_meta(); ?>


    <title><?php wp_title( '|', true, 'right' ); ?></title>


    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">


	<?php wp_head(); ?>

</head>


<body <?php dynamic_body_ids(); ?> <?php body_class( 'fixed-header' ); ?>>


<div class="main-wrapper">

	<?php if ( get_header_image() != null ): ?>

        <style>

            #dsp-header {

                background: url('<?php echo get_header_image(); ?>');

                /*background-size: contain;*/

                background-repeat: no-repeat;

            }

        </style>

	<?php endif; ?>

    <header id="dsp-header">


        <div id="header-container" class="container">


            <div class="dsp-row">

				<?php $slider_display = get_theme_mod( 'slider_display' );


				if ( $slider_display ) {

					echo '<div class="dsp-top-header dsp-top-header-checked">';

				} else {

					echo '<div class="dsp-top-header">';

				}

				?>


                <div class="dsp-logo-placeholder dsp-lg-3 dsp-md-4 dsp-sm-8 dsp-xs-7">

					<?php get_template_part( 'inc/logo-group' ); ?>

                </div>


				<?php if ( ! is_user_logged_in() ) { ?>

                    <div class="dsp-user dsp-lg-2 dsp-md-3 dsp-sm-4 dsp-xs-5">

                        <a href="<?php echo $root_link . '/members/register' ?>"
                           class="dsp-register pull-right"><?php echo language_code( 'DSP_REGISTER' ) ?></a>

                        <a href="javascript:void(0)"
                           class="dsp-login pull-right"><?php echo language_code( 'DSP_LOGIN' ) ?></a>

                        <div class="dsp-login-form<?php if ( $error === null )
							echo ' hide' ?>">

                            <div class="dsp-login-form-title">

                                User Login

                            </div>

                            <div class="dsp-login-form-container">

                                <form name="login-form" method="post">

									<?php if ( $error !== null ): ?>

                                        <div class="alert alert-danger">

											<?php echo $error; ?>

                                        </div>

									<?php endif; ?>

                                    <div class="dsp-login-input-container">

                                        <div class="dsp-form-group user">

                                            <div class="dsp-row">

                                                <div class="dsp-md-12">

                                                    <div class="input-group">

                                                        <span class="input-group-addon"><span
                                                                    class="glyphicon glyphicon-user"></span></span>

                                                        <input type="text" name="user_login" class="dsp-form-control" required
                                                               placeholder="<?php echo language_code( 'DSP_USERNAME' ) ?>">

                                                    </div>

                                                </div>

                                            </div>


                                        </div>

                                        <div class="dsp-form-group password">

                                            <div class="dsp-row">

                                                <div class="dsp-md-12">

                                                    <div class="input-group">

                                                        <span class="input-group-addon"><span
                                                                    class="glyphicon glyphicon-lock"></span></span>

                                                        <input type="password" name="user_password" required
                                                               class="dsp-form-control"
                                                               placeholder="<?php echo language_code( 'DSP_PASSWORD' ) ?>">

                                                    </div>

                                                </div>

                                            </div>

                                        </div>


                                    </div>

                                    <button type="submit"
                                            class="btn btn-primary"><?php echo language_code( 'DSP_SUBMIT_BUTTON' ) ?></button>
                                    <div class="dsp-text-center fpw-link-cont">
                                        <a class="fpw-link"
                                           href="<?php get_bloginfo( 'wpurl' ); ?>/members/lost_password"
                                           rel="nofollow"><?php echo language_code( 'DSP_FORGOT_PASSWORD' ) ?></a>
                                    </div>
                                    <div class="wpdating-theme-fb">
	                                    <?php
	                                    do_action( 'wpdating_facebook_login' );
	                                    ?>
                                    </div>
                                </form>

                            </div>

                        </div>

                    </div>

				<?php } else { ?>

                    <div class="dsp-user dsp-lg-2 dsp-md-3 dsp-sm-3 dsp-xs-5">

                        <a href="#" class="dsp-register pull-right">

							<?php

							$current_user = wp_get_current_user();

							echo $current_user->user_login;

							?>

                            <i class="fa fa-caret-down"></i>

                        </a>

                        <div class="dsp-user-setting">

                            <ul>

                                <li>

                                    <a href="<?php bloginfo( 'url' ) ?>/members/setting/notification"><i
                                                class="fa fa-gear"></i><?php echo language_code( 'DSP_MENU_SETTINGS' ) ?>
                                    </a></li>


                                <li><a href="<?php

									$version = (float) get_bloginfo( 'version' );

									if ( $version >= 2.7 ) {

										?><?php

										echo wp_logout_url( $_SERVER['REQUEST_URI'] );

									} else {

										bloginfo( 'wpurl' );

										?>/wp-login.php?action=logout&redirect_to=<?php echo $_SERVER['REQUEST_URI']; ?><?php } ?>"><i
                                                class="fa fa-power-off"></i><?php echo language_code( 'DSP_LOGOUT' ) ?>
                                    </a></li>

                            </ul>

                        </div>

                    </div>


				<?php } ?>


                <div class="dsp-primary-nav dsp-lg-7 dsp-md-5 dsp-sm-12 dsp-menu-col">

                    <a href="#" class="toggleMenu">Menu</a>


                    <div class="dsp-menu">
                    <?php $walker = new Menu_With_Description; ?>
						<?php

						if ( has_nav_menu( 'primary' ) ) {

							wp_nav_menu( array(

								'theme_location' => 'primary',

								'menu' => '',

								'container' => false,

                                'menu_class' => 'vs',
                                
                                'walker' => $walker,

								'depth' => 0

							) );

						} else {

							?>

                            <ul>

								<?php

								wp_list_pages( array(

									'depth' => 0,

									'date_format' => get_option( 'date_format' ),

									'title_li' => '',

									'echo' => 1,

									'sort_column' => 'menu_order, post_title',

								) );

								?>

                            </ul>

						<?php } ?>


                    </div>

                </div>


            </div>

        </div>

</div>


<?php if ( $frontpage_display ){ ?>





<?php $slider_display = get_theme_mod( 'slider_display' );


if ( $slider_display ) {

	?>

	<?php if ( is_front_page() ): ?>

        <div class="sldier-container">

            <div class="container full-container">

                <div class="dsp-row">

					<?php //echo do_shortcode("[camera slideshow='banner-home']");?>

					<?php //echo do_shortcode('[WPDating_slider id=2134]'); ?>

					<?php echo do_shortcode( get_theme_mod( 'slider_shortcode' ) ); ?>

                </div>

            </div>

        </div>

	<?php endif; ?>

	<?php

}

?>



<?php if ( is_front_page() ) { ?>

    <div class="dsp-revolution-wrapper">

		<?php $search_display = get_theme_mod( 'search_display' );

		if ( $search_display ) {

			do_action( 'wpdating_search_form' );

		}

		?>

    </div>
<?php } ?>

</header>

<section class="dsp-full-width ">

    <div class="container">

        <div class="dsp-row">


			<?php } else {

			?>


            </header>


            <section class="dsp-full-width">

                <div class="container">

                    <div class="dsp-row">

						<?php } ?>




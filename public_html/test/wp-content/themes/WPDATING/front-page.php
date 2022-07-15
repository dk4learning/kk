<?php

/*-------------------------------------------------

	BlankPress - Default Front Page Template

	Template Name: frontpage Full

 --------------------------------------------------*/

get_header();
$frontpage_display = get_theme_mod( 'frontpage_display' );
if ( ! $frontpage_display ){
	?>
    <div class="container-fluid container-home-editor">

        <div class="container">

            <div class="row">

                <h3><?php while ( have_posts() ): the_post();

						the_title(); endwhile; ?></h3>

            </div>

            <div class="row">

                <div class="container-home-editor-content">

					<?php while ( have_posts() ): the_post();

						the_content();

					endwhile;

					?>

                </div>

            </div>

        </div>

    </div>

<?php }

else {

?>


<div class="dsp-row">


    <!--<div class="dsp-heading-block text-center dsp-md-12">

		<h2><span>Steps</span> To Find Your Match</h2>

	</div>-->


	<?php

	if ( is_active_sidebar( 'sidebar-4' ) ) : ?>


        <div class="space-tp"><?php dynamic_sidebar( 'sidebar-4' ); ?></div>


	<?php endif;

	?>


</div>

</div>

</section>


<section class="dsp-full-width space-tp member-block">

    <div class="container">

		<?php $display_member_block = get_theme_mod( 'member_display' );

		if ( $display_member_block ) {

			?>

            <div class="dsp-row">

                <div class="dsp-heading-block text-center dsp-md-12">

                    <h2><span><?php echo language_code( 'DSP_NEW_MEMBERS' ); ?></span></h2>

                </div>

            </div>

            <div class="dsp-row">

                <div class="clearfix ">


					<?php

					$new_members = dsp_getNewMembers();

					foreach ( $new_members as $mem ) {

						$mem_id = $mem->user_id;

						$gender = $mem->gender;

						//$img_url = display_members_photo($mem_id ,$imagepath);

						$age = $mem->age;

					}


					?>

                    <div class="dsp-new-member-slider jcarousel-wrapper jcarousel-wrapper-frontpage">

                        <div class="jcarousel">

                            <ul class="slides">

								<?php

								$imagepath = get_option( 'siteurl' ) . '/wp-content/';  // image Path

								$new_members = dsp_getNewMembers();

								$i = 0;

								foreach ( $new_members as $mem ) {


									$user_id = $mem->user_id;

									$username = get_userdata( $user_id );

									$name = $username->display_name;

									$gender = $mem->gender;

									$age = $mem->age;

									?>

                                    <li>

                                        <div class="dsp-md-2 dsp-home-member dsp-sm-3">

                                            <!-- <div class="dsp-new-member-photo circle-image"> -->

                                            <div class="member-image">

                                                <a href="<?php echo ROOT_LINK . get_username( $mem->user_id ); ?>">

                                                    <img src="<?php echo display_members_photo( $user_id, $imagepath ); ?>"/>

                                                </a>

                                            </div>

                                            <a href="<?php echo ROOT_LINK . get_username( $mem->user_id ); ?>"
                                               class="db-member-title"><?php echo substr( $name, 0, 7 ); ?></a>

                                            <span class="blue-txt"><?php echo dsp_get_age( $age ) ?> <?php echo language_code( 'DSP_YEARS_OLD' )?></span>

                                        </div>

                                    </li>


								<?php } ?>


                            </ul>

                        </div>


                    </div>

                </div>


            </div>

			<?php

		}


		?>


        <div class="dsp-row">

            <div class="clearfix">

				<?php $display_member_online = get_theme_mod( 'member_online' );

				if ( $display_member_online ) {

					?>

                    <div class="dsp-md-4 dsp-sm-4">

                        <div class="dsp-home-widget">

                            <div class="dsp-widget-title">

                                <h2><span><?php echo language_code( 'DSP_ONLINE_MEMBER_TEXT' ); ?></span></h2>

                            </div>

                            <ul class="dsp-widget-member dsp-widget-online-member">

								<?php

								$online_members = dsp_get_online_users();
								$imagepath      = get_option( 'siteurl' ) . '/wp-content/';  // image Path

								foreach ( $online_members as $mem ) {

									$user_id = $mem->user_id;

									$username = get_userdata( $user_id );

									$name = $username->display_name;

									$gender = $mem->gender;

									$age = $mem->age;


									?>

                                    <li>


                                        <div class="circle-image pull-left margin-right">

                                            <a href="<?php echo ROOT_LINK . get_username( $mem->user_id ); ?>"
                                               class="dsp-widget-image ">

                                                <img src="<?php echo display_members_photo( $user_id, $imagepath ); ?>"
                                                     height="42" width="42"/>

                                            </a>

                                        </div>


                                        <div class="dsp-widget-info">

                                            <a href="<?php echo ROOT_LINK . get_username( $mem->user_id ); ?>"><?php echo substr( $name, 0, 7 ); ?></a>

                                            <span class="txt-lt-blue"><?php echo dsp_get_age( $age ) ?> <?php echo language_code( 'DSP_YEARS_OLD' )?></span>

                                        </div>

                                    </li>

								<?php } ?>

                            </ul>

                        </div>

                    </div>

					<?php

				}


				?>



				<?php $happy_stories = get_theme_mod( 'happy_stories' );

				if ( $happy_stories ) {

					?>


                    <div class="dsp-md-4 dsp-sm-4">

                        <div class="dsp-home-widget">


                            <div class="dsp-widget-title">

                                <h2><span><?php echo language_code( "DSP_HAPPY_STORIES" ); ?></span></h2>

								<?php if ( is_user_logged_in() ) { ?>

                                    <a href="<?php echo get_bloginfo( 'url' ); ?>/<?php echo 'members/stories'; ?>"
                                       class="link-more pull-right"><?php echo language_code( 'DSP_LIST_ALL' ) ?></a>

								<?php } else { ?>

                                    <a href="<?php echo get_bloginfo( 'url' ); ?>/<?php echo 'members/stories' ?>"
                                       class="link-more pull-right"><?php echo language_code( 'DSP_LIST_ALL' ) ?></a>

								<?php } ?>

                            </div>

                            <ul class="dsp-widget-stories">

								<?php

								$dsp_stories = $wpdb->prefix . DSP_STORIES_TABLE;

								$story_result = $wpdb->get_results( "select * from $dsp_stories order by date_added desc" );

								if ( count( $story_result ) > 0 ) {

									?>

									<?php foreach ( $story_result as $story_row ) { ?>

                                        <li>

                                            <a href="#" class="dsp-widget-image ">

                                                <img src="<?php echo get_bloginfo( 'url' ) . '/wp-content/uploads/dsp_media/story_images/thumb_' . $story_row->story_image; ?>"
                                                     alt="">

                                            </a>

                                            <div class="dsp-widget-info">

                                                <a href="<?php echo get_bloginfo( 'url' ); ?>/<?php echo 'members/stories'; ?>"><?php echo $story_row->story_title; ?></a>

                                                <p>

													<?php $story_content = ltrim( str_replace( '\\', '', $story_row->story_content ) ); ?>

													<?php echo substr( $story_content, 0, 49 ); ?>

                                                </p>

                                            </div>

                                        </li>

									<?php } ?>


								<?php } ?>

                            </ul>


                        </div>

                    </div>

					<?php

				}


				?>



				<?php $latest_blog = get_theme_mod( 'latest_blog' );

				if ( $latest_blog ) {

					?>

                    <div class="dsp-md-4 dsp-sm-4">

                        <div class="dsp-home-widget">


                            <div class="dsp-widget-title">

                                <h2><span><?php echo language_code( 'DSP_LATEST_BLOG' ); ?></span></h2>

                            </div>

                            <ul>

								<?php

								$args = array( 'numberposts' => '5' );

								$recent_posts = wp_get_recent_posts( $args );

								foreach ( $recent_posts as $recent ) {

									echo '<li><span class="dsp-meta">' . mysql2date( 'j M Y', $recent["post_date"] ) . '</span><a href="' . get_permalink( $recent["ID"] ) . '" title="Blog ' . __(esc_attr( $recent["post_title"] )) . '" >' . __($recent["post_title"]) . '</a> </li> ';

								}

								?>

                            </ul>

                        </div>

                    </div>

					<?php

				}


				?>

            </div>

			<?php } ?>





			<?php get_footer(); ?>




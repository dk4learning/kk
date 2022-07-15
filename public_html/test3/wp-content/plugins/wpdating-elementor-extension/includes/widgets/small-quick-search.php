<?php
namespace Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Wpdating_Elementor_Extension_Helper_Functions;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wpdating_Elementor_Extension_Small_Quick_Search extends Widget_Base {

	public function get_name() {
		return 'wpee-small-quick-search';
	}

	public function get_title() {
		return __( 'Quick Search', 'wpdating' );
	}

	public function get_icon() {
		return 'eicon-search';
	}

	public function get_categories() {
		return [ 'wpdating' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Quick Search', 'wpdating' ),
			]
		);

	}

	protected function render() {
		global $wpee_settings;
		$wpee_settings = $this->get_settings();
        $this->add_render_attribute( 'wpee-small-quick-search', 'class', 'wpee-small-quick-search' ); ?>
        <div <?php echo $this->get_render_attribute_string( 'wpee-small-quick-search' ); ?>>
        	<div class="wpee-small-quick-search-wrap">			    
	    		<?php 
				global $wpdb;
				//For min and max age
				$check_min_age = wpee_get_setting('min_age');
				$min_age_value = $check_min_age->setting_value;
				$check_max_age = wpee_get_setting('max_age');
				$max_age_value = $check_max_age->setting_value;
				$check_near_me = wpee_get_setting('near_me');
				// Member page
                $member_page_url = Wpdating_Elementor_Extension_Helper_Functions::get_members_page_url();

				//search options values
				$selected_seeking  = '';
				$selected_gender   = ''; 
				if( is_user_logged_in() ) {
					$current_user_profile = wpee_get_user_profile_by( array( 'user_id' => get_current_user_id() ) );
					if( $current_user_profile ) {
						$selected_gender  = $current_user_profile->gender;
						$selected_seeking = $current_user_profile->seeking; 
					}
				}
				?>
				<div class="profile-quick-search-inner wpee-block-content">
					<?php 
				   ?>
				   	<form class="dspdp-form-horizontal dsp-form-container" name="frmsearch" method="post" action="<?php echo esc_url($member_page_url); ?>">
				   		<?php wp_nonce_field( 'wpee_basic_search', 'wpee-basic-search' );?>

	                    <div class="form-inline">
			                <?php
								$gender_label = __( 'I am', 'wpdating' );
			                    $gender_list = wpee_get_gender_list( $selected_gender, $gender_label );
			                    if(!empty($gender_list)) : ?>
			                    <div class="form-group">
		                            <select class="form-control" name="gender">
		                                <?php echo $gender_list; ?>
		                            </select>
			                    </div>
			                <?php endif; ?>

			                <?php 
			                   $seeking_label = __( 'Seeking a', 'wpdating' );
			                   $gender_list = wpee_get_gender_list( $selected_seeking, $seeking_label );
			                   if(!empty($gender_list)):
			                ?>
			                    <div class="form-group">                 
		                            <select name="seeking" class="form-control">
		                                <?php echo $gender_list; ?>
		                            </select>
			                    </div>
			                <?php endif; ?>
			                <div class="form-group">
		                        <select name="age_from" class="form-control"> 
	                        		<option value=""><?php echo esc_html__('Age From','wpdating'); ?></option>
		                            <?php
									$min_selected = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : '';
		                            for ($fromyear = $min_age_value; $fromyear <= $max_age_value; $fromyear++) {
		                                if ($fromyear == $min_selected) {
		                                    ?>
		                                    <option value="<?php echo esc_attr($fromyear); ?>" selected="selected"><?php echo esc_html($fromyear); ?></option>
		                                <?php } else { ?>
		                                    <option value="<?php echo esc_attr($fromyear); ?>"><?php echo esc_html($fromyear); ?></option>
		                                    <?php
		                                }
		                            }
		                            ?>
		                        </select>
		                    </div>
			                <div class="form-group">
		                        <select name="age_to" class="form-control">
		                        	<option value=""><?php echo esc_html__('Age To','wpdating'); ?></option>
		                            <?php
									$max_selected = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : '';
		                            for ($fromyear = $min_age_value; $fromyear <= $max_age_value; $fromyear++) {
		                                if ($fromyear == $max_selected) {
		                                    ?>
		                                    <option value="<?php echo esc_attr($fromyear); ?>" selected="selected"><?php echo esc_html($fromyear); ?></option>
		                                <?php } else { ?>
		                                    <option value="<?php echo esc_attr($fromyear); ?>"><?php echo esc_html($fromyear); ?></option>
		                                    <?php
		                                }
		                            }
		                            ?>
		                        </select>
		                    </div>
			                <div class="form-group submit-wrap">
			                	<i class="fa fa-search"></i>
						    	<input type="submit" name="submit" class="dsp_submit_button dspdp-btn dsp-block dspdp-btn-default" value="<?php echo esc_html__('Submit', 'wpdating'); ?>"/>
						    </div>
		                </div>
					</form>
				</div>
		    </div>
		</div>
		<?php
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpdating_Elementor_Extension_Small_Quick_Search() );

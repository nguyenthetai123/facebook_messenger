<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'WPE_PLUGIN_OPTIONS' ) ) {
	/**
	 * WPE_PLUGIN_OPTIONS Class
	 *
	 * @since	1.0
	 */
	class WPE_PLUGIN_OPTIONS {


		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			$this->init();
		}

		public function init() {
			
			add_filter('wpe_register_options', array( $this, 'options'), 10, 1 );

			if(is_admin()) {
				add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
			}
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );
        }

		public function adminEnqueueScripts() {
			if(isset($_GET['page']) && ($_GET['page'] == WPE_PLUGIN_PAGESLUG)) {
				WPE_CORE_OPTIONS::adminEnqueueScripts();
			}
		}

		public function enqueueScripts() {
		
        }
        
        public function options($options) {
			if( empty($options) ) {
				$options = array();
			}
			$pages = get_pages(); 
			$posts = get_posts(); 

			$pages_title = array('' => esc_html__('None', 'bestbug'));
			foreach ($pages as $page_data) {
				$pages_title[$page_data->post_name] = $page_data->post_title;
			}
			// foreach ($posts as $post_data) {
			// 	$pages_title[$post_data->post_name] = $post_data->post_title;
			// }

			$posttypes = get_post_types( array( 'public' => true ) );
			unset($posttypes['attachment']);

			$args = array(
				'posts_per_page'  => -1,
				'post_type' => WPE_PLUGIN_POSTTYPE,
				'orderby' => 'title',
				'post_status' => 'publish',
				'order' => 'ASC',
			);
			
			$prefix = 'bbfm_';
			$options[] = array(
				'type' => 'options_fields',
				'menu' => array(
					// add_submenu_page || add_menu_page
					'type' => 'add_menu_page',
					'page_title' => esc_html('Wpe Facebook Messenger', 'bestbug'),
					'menu_title' => esc_html('Wpe Facebook Messenger', 'bestbug'),
					'capability' => 'manage_options',
					'menu_slug' => WPE_PLUGIN_PAGESLUG,
					'position' => '54',
					'icon' => '',
				),
				'fields' => array(
				)
			);
			$options[]=array(
				'type' => 'options_fields',
				'menu' => array(
					// add_submenu_page || add_menu_page
					'type' => 'add_submenu_page',
					'parent_slug' =>  WPE_PLUGIN_PAGESLUG,
					'page_title' => esc_html('Settings', 'bestbug'),
					'menu_title' => esc_html('Settings', 'bestbug'),
					'capability' => 'manage_options',
					'menu_slug' => WPE_PLUGIN_PAGESLUG,
				),
				'fields' => array(
					array(
						'type'       => 'tab',
						'heading'    => esc_html__( '', 'bestbug' ),
						'param_name' => $prefix . 'theme',
						'value'      => array(
							'general' => esc_html__( 'General', 'bestbug' ),
							'mobile' => esc_html__( 'Mobile', 'bestbug' ),
							'wooCommerce' => esc_html__( 'WooCommerce', 'bestbug' ),
						),
						'std' => 'general',
						'description' => esc_html__('', 'bestbug'),
					),
					array(
						'type'        => 'dropdown',
						'heading'    => esc_html__( 'Type', 'bestbug' ),
						'value'       => array(
							'page'  => esc_html__( 'Page', 'bestbug' ),
							'live' => esc_html__( 'Chat Live', 'bestbug' ),
						),
						'param_name' => $prefix . 'type',
						'description' => esc_html('Enter your page url. Example: https://www.facebook.com/bestbugteam.org', 'bestbug'),
						'tab' => array(
							'element' =>  $prefix . 'theme',
							'value' => array('general')
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__('Page ID', 'bestbug'),
						'param_name' => $prefix . 'id_page',
						'value' => '',
						'description' => esc_html('', 'bestbug'),
						'tab' => array(
							'element' =>  $prefix . 'theme',
							'value' => array('general')
						),
						'dependency' => array('element' => $prefix . 'type', 'value' => array('live')),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__('Facebook Page URL', 'bestbug'),
						'param_name' => $prefix . 'url_page',
						'value' => '',
						'description' => esc_html('Enter your page url. Example: https://www.facebook.com/bestbugteam.org', 'bestbug'),
						'tab' => array(
							'element' =>  $prefix . 'theme',
							'value' => array('general')
						),
						'dependency' => array('element' => $prefix . 'type', 'value' => array('page')),
					),
					array(
						'type'        => 'dropdown',
						'heading'    => esc_html__( 'Display header cover', 'bestbug' ),
						'value'       => array(
							'hide'  => esc_html__( 'Hide', 'bestbug' ),
							'small' => esc_html__( 'Small', 'bestbug' ),
							'large' => esc_html__( 'Large', 'bestbug' ),
						),
						'param_name'  => $prefix . 'header_cover',
						'description' => esc_html('Select Facebook header cover type on Messenger popup','bestbug'),
						'std' => 'small',
						'tab' => array(
							'element' =>  $prefix . 'theme',
							'value' => array('general')
						), 
					),
					array(
						'type'        => 'dropdown',
						'heading'    => esc_html__( 'Icon position( horizontal )', 'bestbug' ),
						'value'       => array(
							'left' => esc_html__( 'Left', 'bestbug' ),
							'right' => esc_html__( 'Right', 'bestbug' ),
						),
						'param_name'  => $prefix . 'position_h',
						'description' => '',
						'std' => 'right',
						'tab' => array(
							'element' =>  $prefix . 'theme',
							'value' => array('general')
						),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__('Position H-Space (example: 100px, 10%,...)', 'bestbug'),
						'param_name' => $prefix . 'h_space',
						'value' => '',
						'description' => esc_html('', 'bestbug'),
						'tab' => array(
							'element' =>  $prefix . 'theme',
							'value' => array('general')
						),
					),
					array(
						'type'        => 'dropdown',
						'heading'    => esc_html__( 'Icon position( vertical )', 'bestbug' ),
						'value'       => array(
							'top'    => esc_html__( 'Top', 'bestbug' ),
							'middle' => esc_html__( 'Middle', 'bestbug' ),
							'bottom' => esc_html__( 'Bottom', 'bestbug' ),
						),
						'param_name'  => $prefix . 'position_v',
						'description' => '',
						'std' => 'top',
						'tab' => array(
							'element' =>  $prefix . 'theme',
							'value' => array('general')
						),
					),
					//textfield
					array(
						'type' => 'textfield',
						'heading' => esc_html__('Position V-Space (example: 100px, 10%,...)', 'bestbug'),
						'param_name' => $prefix . 'v_space',
						'value' => '',
						'description' => esc_html('', 'bestbug'),
						'tab' => array(
							'element' =>  $prefix . 'theme',
							'value' => array('general')
						),
						'dependency' => array('element' => $prefix . 'position_v', 'value' => array('top','bottom')),
					),
					array(
						'type'       => 'attach',
						'heading'    => esc_html__( 'Image Icon', 'bestbug' ),
						'param_name' => $prefix .'image_icon',
						'value'      => '',
						'description' => esc_html('Upload images to make your logo (png, svg, jpg, ...)', 'bestbug'),
						'tab' => array(
							'element' =>  $prefix . 'theme',
							'value' => array('general')
						),
					),
					array(
						'type'       => 'number',
						'heading'    => esc_html__( 'Icon width', 'bestbug' ),
						'param_name' => $prefix . 'icon_width',
						'value'      => 50,
						'description' => esc_html('(px)', 'bestbug'),
						'tab' => array(
							'element' =>  $prefix . 'theme',
							'value' => array('general')
						),
					),
					array(
						'type'       => 'number',
						'heading'    => esc_html__( 'Icon height', 'bestbug' ),
						'param_name' => $prefix . 'icon_height',
						'value'      => 50,
						'description' => esc_html('(px)', 'bestbug'),
						'tab' => array(
							'element' =>  $prefix . 'theme',
							'value' => array('general')
						),
					),
					array(
						'type'       => 'number',
						'heading'    => esc_html__( 'Icon border radius', 'bestbug' ),
						'param_name' => $prefix . 'icon_radius',
						'value'      => 0,
						'description' => esc_html('(px)', 'bestbug'),
						'tab' => array(
							'element' =>  $prefix . 'theme',
							'value' => array('general')
						),
					),
					array(
						'type'=> 'dropdown',
						'heading'=> esc_html__( 'language', 'bestbug' ),
						'value'=> array(
							"af_ZA"=> esc_html__( 'Afrikaans', 'bestbug' ),
							"ar_AR"=> esc_html__( 'Arabic', 'bestbug' ),
							"az_AZ"=> esc_html__( 'Azerbaijani', 'bestbug' ),
							"be_BY"=> esc_html__( 'Belarusian', 'bestbug' ),
							"bg_BG"=> esc_html__( 'Bulgarian', 'bestbug' ),
							"bn_IN"=> esc_html__( 'Bengali', 'bestbug' ),
							"bs_BA"=> esc_html__( 'Bosnian', 'bestbug' ),
							"ca_ES"=> esc_html__( 'Catalan', 'bestbug' ),
							"cs_CZ"=> esc_html__( 'Czech', 'bestbug' ),
							"cy_GB"=> esc_html__( 'Welsh', 'bestbug' ),
							"da_DK"=> esc_html__( 'Danish', 'bestbug' ),
							"de_DE"=> esc_html__( 'German', 'bestbug' ),
							"el_GR"=> esc_html__( 'Greek', 'bestbug' ),
							"en_GB"=> esc_html__( 'English (UK)', 'bestbug' ),
							"en_PI"=> esc_html__( 'English (Pirate)', 'bestbug' ),
							"en_UD"=> esc_html__( 'English (Upside Down)', 'bestbug' ),
							"en_US"=> esc_html__( 'English (US)', 'bestbug' ),
							"eo_EO"=> esc_html__( 'Esperanto', 'bestbug' ),
							"es_ES"=> esc_html__( 'Spanish (Spain)', 'bestbug' ),
							"es_LA"=> esc_html__( 'Spanish', 'bestbug' ),
							"et_EE"=> esc_html__( 'Estonian', 'bestbug' ),
							"eu_ES"=> esc_html__( 'Basque', 'bestbug' ),
							"fa_IR"=> esc_html__( 'Persian', 'bestbug' ),
							"fb_LT"=> esc_html__( 'Leet Speak', 'bestbug' ),
							"fi_FI"=> esc_html__( 'Finnish', 'bestbug' ),
							"fo_FO"=> esc_html__( 'Faroese', 'bestbug' ),
							"fr_CA"=> esc_html__( 'French (Canada)', 'bestbug' ),
							"fr_FR"=> esc_html__( 'French (France)', 'bestbug' ),
							"fy_NL"=> esc_html__( 'Frisian', 'bestbug' ),
							"ga_IE"=> esc_html__( 'Irish', 'bestbug' ),
							"gl_ES"=> esc_html__( 'Galician', 'bestbug' ),
							"he_IL"=> esc_html__( 'Hebrew', 'bestbug' ),
							"hi_IN"=> esc_html__( 'Hindi', 'bestbug' ),
							"hr_HR"=> esc_html__( 'Croatian', 'bestbug' ),
							"hu_HU"=> esc_html__( 'Hungarian', 'bestbug' ),
							"hy_AM"=> esc_html__( 'Armenian', 'bestbug' ),
							"id_ID"=> esc_html__( 'Indonesian', 'bestbug' ),
							"is_IS"=> esc_html__( 'Icelandic', 'bestbug' ),
							"it_IT"=> esc_html__( 'Italian', 'bestbug' ),
							"ja_JP"=> esc_html__( 'Japanese', 'bestbug' ),
							"ka_GE"=> esc_html__( 'Georgian', 'bestbug' ),
							"km_KH"=> esc_html__( 'Khmer', 'bestbug' ),
							"ko_KR"=> esc_html__( 'Korean', 'bestbug' ),
							"ku_TR"=> esc_html__( 'Kurdish', 'bestbug' ),
							"la_VA"=> esc_html__( 'Latin', 'bestbug' ),
							"lt_LT"=> esc_html__( 'Lithuanian', 'bestbug' ),
							"lv_LV"=> esc_html__( 'Latvian', 'bestbug' ),
							"mk_MK"=> esc_html__( 'Macedonian', 'bestbug' ),
							"ml_IN"=> esc_html__( 'Malayalam', 'bestbug' ),
							"ms_MY"=> esc_html__( 'Malay', 'bestbug' ),
							"nb_NO"=> esc_html__( 'Norwegian (bokmal)', 'bestbug' ),
							"ne_NP"=> esc_html__( 'Nepali', 'bestbug' ),
							"nl_NL"=> esc_html__( 'Dutch', 'bestbug' ),
							"nn_NO"=> esc_html__( 'Norwegian (nynorsk)', 'bestbug' ),
							"pa_IN"=> esc_html__( 'Punjabi', 'bestbug' ),
							"pl_PL"=> esc_html__( 'Polish', 'bestbug' ),
							"ps_AF"=> esc_html__( 'Pashto', 'bestbug' ),
							"pt_BR"=> esc_html__( 'Portuguese (Brazil)', 'bestbug' ),
							"pt_PT"=> esc_html__( 'Portuguese (Portugal)', 'bestbug' ),
							"ro_RO"=> esc_html__( 'Romanian', 'bestbug' ),
							"ru_RU"=> esc_html__( 'Russian', 'bestbug' ),
							"sk_SK"=> esc_html__( 'Slovak', 'bestbug' ),
							"sl_SI"=> esc_html__( 'Slovenian', 'bestbug' ),
							"sq_AL"=> esc_html__( 'Albanian', 'bestbug' ),
							"sr_RS"=> esc_html__( 'Serbian', 'bestbug' ),
							"sv_SE"=> esc_html__( 'Swedish', 'bestbug' ),
							"sw_KE"=> esc_html__( 'Swahili', 'bestbug' ),
							"ta_IN"=> esc_html__( 'Tamil', 'bestbug' ),
							"te_IN"=> esc_html__( 'Telugu', 'bestbug' ),
							"th_TH"=> esc_html__( 'Thai', 'bestbug' ),
							"tl_PH"=> esc_html__( 'Filipino', 'bestbug' ),
							"tr_TR"=> esc_html__( 'Turkish', 'bestbug' ),
							"uk_UA"=> esc_html__( 'Ukrainian', 'bestbug' ),
							"vi_VN"=> esc_html__( 'Vietnamese', 'bestbug' ),
							"zh_CN"=> esc_html__( 'Simplified Chinese (China)', 'bestbug' ),
							"zh_HK"=> esc_html__( 'Traditional Chinese (Hong Kong)', 'bestbug' ),
							"zh_TW"=> esc_html__( 'Traditional Chinese (Taiwan)', 'bestbug' ),
						),
						'param_name'  => $prefix . 'language',
						'description' => esc_html('','bestbug'),
						'std' => 'en_US',
						'tab' => array(
							'element' =>  $prefix . 'theme',
							'value' => array('general')
						),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Icon color', 'bestbug' ),
						'param_name' =>  $prefix . 'icon_color',
						'value'      => '#ff0000',
						'tab' => array(
							'element' =>  $prefix . 'theme',
							'value' => array('general')
						),
					),
					//pages
					array(
						'type'        => 'toggle',
						'heading'     => esc_html__( 'Display all pages', 'bestbug' ),
						'value'       => array(
							'no' => esc_html__( 'No', 'bestbug' ),
							'yes' => esc_html__( 'Yes', 'bestbug' ),
						),
						'param_name'  => $prefix . 'display_pages',
						'std' => array('true' => 'yes'),
						'tab' => array(
							'element' => $prefix . 'theme',
							'value' => array('general')
						),
						'description' => '',
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Display for page', 'bestbug' ),
						'value'       => $pages_title,
						'padding' => esc_html__( 'all', 'bestbug' ),
						'param_name'  => $prefix . 'display_page',
						'std' => array(),
						'description' => esc_html__( 'Select where you want to display Facebook Messenger','bestbug' ),
						'tab' => array(
							'element' =>  $prefix . 'theme',
							'value' => array('general')
						),
						'dependency'  => array( 'element' => $prefix . 'display_pages', 'value' => array( 'no' ) ),
					),
					// Mobile
					array(
						'type'        => 'dropdown',
						'heading'    => esc_html__( 'MOBILE	', 'bestbug' ),
						'value'       => array(
							'desktop_mobile' => esc_html__( 'Display on desktop and mobile', 'bestbug' ),
							'mobile' => esc_html__( 'Display only mobile', 'bestbug' ),
							'desktop' => esc_html__( 'Hide on mobile', 'bestbug' ),
						),
						'param_name'  => $prefix . 'conditions',
						'description' => esc_html('Select type you want to display Messenger on mobile and desktop','bestbug'),
						'std' => 'desktop_mobile',
						'tab' => array(
							'element' => $prefix . 'theme',
							'value' => array('mobile')
						),
					),
					array(
						'type'        => 'toggle',
						'heading'     => esc_html__( 'Open Messenger app when click button', 'bestbug' ),
						'value'       => array(
							'no' => esc_html__( 'No', 'bestbug' ),
							'yes' => esc_html__( 'Yes', 'bestbug' ),
						),
						'param_name'  => $prefix . 'open_app',
						'std' => array('true' => 'yes'),
						'tab' => array(
							'element' => $prefix . 'theme',
							'value' => array('mobile')
						),
						'description' => esc_html__( 'Use this feature if you want user click to open Messenger app on smartphone (will display a button on Messenger popup)', 'bestbug' ),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__('Text for open Messenger app when click button', 'bestbug'),
						'param_name' => $prefix . 'text_custom',
						'value' => '',
						'description' => esc_html('Custom text for button open Messenger app', 'bestbug'),
						'tab' => array(
							'element' => $prefix . 'theme',
							'value' => array('mobile')
						),
					),
					//woocommerce 
					array(
						'type' => 'textfield',
						'heading' => esc_html__('Custom text for button', 'bestbug'),
						'param_name' => $prefix . 'text_button',
						'value' => '',
						'description' => esc_html('Custom text for the button on WooCommerce product detail page', 'bestbug'),
						'tab' => array(
							'element' =>  $prefix . 'theme',
							'value' => array('wooCommerce')
						),	
					),
					array(
						'type'        => 'dropdown',
						'heading'    => esc_html__( 'Position for button in product detail page	', 'bestbug' ),
						'value'       => array(
							'before' => esc_html__( 'Before button add to cart', 'bestbug' ),
							'after' => esc_html__( 'After button add to cart', 'bestbug' ),
							'hide' => esc_html__( 'Hide', 'bestbug' ),
						),
						'param_name'  => $prefix . 'position_button',
						'description' => '',
						'std' => 'before',
						'tab' => array(
							'element' => $prefix . 'theme',
							'value' => array('wooCommerce')
						),
					),
				)
			);
			$options[]=array(
				'type' => 'options_fields',
				'menu' => array(
					// add_submenu_page || add_menu_page
					'type' => 'add_submenu_page',
					'parent_slug' =>  WPE_PLUGIN_PAGESLUG,
					'page_title' => esc_html('Messenger plugins', 'bestbug'),
					'menu_title' => esc_html('Messenger plugins', 'bestbug'),
					'capability' => 'manage_options',
					'menu_slug' => WPE_PLUGIN_PAGESLUG.'messenger_plugins',
				),
				'fields' => array(
				)
			);
			$options[]=array(
				'type' => 'options_fields',
				'menu' => array(
					// add_submenu_page || add_menu_page
					'type' => 'add_submenu_page',
					'parent_slug' =>  WPE_PLUGIN_PAGESLUG,
					'page_title' => esc_html('Support', 'bestbug'),
					'menu_title' => esc_html('Support', 'bestbug'),
					'capability' => 'manage_options',
					'menu_slug' => WPE_PLUGIN_PAGESLUG.'support',
				),
				'fields' => array(

				)
			);
		return $options;
        }
    }
	new WPE_PLUGIN_OPTIONS();
}


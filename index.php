<?php
/*
Plugin Name: WPE Facebook Messenger
Description: Tool to send indirect messages to facebook messenger.
Author: WPElite.net
Version: 1.0
Author URI: http://wpelite.net/
Text Domain: wpelite
Domain Path: /languages
*/ 

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


defined( 'WPE_PLUGIN_VERSION' ) or define('WPE_PLUGIN_VERSION', '1.0') ;

defined( 'WPE_PLUGIN_URL' ) or define('WPE_PLUGIN_URL', plugins_url( '/', __FILE__ )) ;

defined( 'WPE_PLUGIN_PATH' ) or define('WPE_PLUGIN_PATH', basename( dirname( __FILE__ ))) ;
defined( 'WPE_PLUGIN_TEXTDOMAIN' ) or define('WPE_PLUGIN_TEXTDOMAIN', plugins_url( '/', __FILE__ )) ;
defined( 'WPE_PLUGIN_POSTTYPE' ) or define('WPE_PLUGIN_POSTTYPE', 'wpe_plugin_posttype') ;
defined( 'WPE_META_POST' ) or define('WPE_META_POST', 'wpe_plugin_posttype') ;

defined( 'WPE_PLUGIN_PAGESLUG' ) or define('WPE_PLUGIN_PAGESLUG', 'wpe_plugin_settings') ;

if ( ! class_exists( 'WPE_PLUGIN_CLASS' ) ) {
	/**
	 * WPE_PLUGIN_CLASS Class
	 *
	 * @since	1.0
	 */
	class WPE_PLUGIN_CLASS {
		
		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			// Load core
			if(!class_exists('WPE_CORE_CLASS')) {
				include_once 'wpe-core/index.php';
			}
			WPE_CORE_CLASS::support('options');
			WPE_CORE_CLASS::support('meta_box');
			if(is_admin()) {
				include_once 'includes/admin/index.php';
			}
			WPE_CORE_CLASS::support('posttypes');
			include_once 'includes/index.php';
            add_action( 'init', array( $this, 'init' ) );
		}

		public function init() {
			// Load enqueueScripts
			if(is_admin()) {
				add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
			}
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );
			add_action('wp_head',array($this,'wpe_facebook_messenger'));
		}
		
		public function wpe_facebook_messenger()
		{
			$opt = wp_load_alloptions();
			$id_page = $opt['bbfm_id_page'];
			$language = $opt['bbfm_language'];
			$type = $opt['bbfm_type'];
			$display_pages = $opt['bbfm_display_pages'];
			$display_page = get_option('bbfm_display_page');
			if(empty($id_page)){
				return;
			}
			if(empty($language)){
				return;
			}

			if( $type == 'live'){
				if($display_pages == 'no'){
					foreach ($display_page as $key => $value) {
						if($value == 1){
							if(is_page($key)){
								?><div id="fb-root" class="wpe_fm_root"></div>
								<script>
									window.fbAsyncInit = function() {
										FB.init({
											xfbml            : true,
											version          : 'v6.0'
										});
									};
									(function(d, s, id) {
										var js, fjs = d.getElementsByTagName(s)[0];
										if (d.getElementById(id)) return;
										js = d.createElement(s); js.id = id;
										js.src = 'https://connect.facebook.net/<?php echo esc_attr($language); ?>/sdk/xfbml.customerchat.js';
										fjs.parentNode.insertBefore(js, fjs);
									}(document, 'script', 'facebook-jssdk'));
								</script>
								<div class="fb-customerchat" attribution=setup_tool page_id="<?php echo esc_attr($id_page); ?>"></div><?php
							}
						}
					}
				}else{
					?><div id="fb-root" class="wpe_fm_root"></div>
					<script>
						window.fbAsyncInit = function() {
							FB.init({
								xfbml            : true,
								version          : 'v6.0'
							});
						};
						(function(d, s, id) {
							var js, fjs = d.getElementsByTagName(s)[0];
							if (d.getElementById(id)) return;
							js = d.createElement(s); js.id = id;
							js.src = 'https://connect.facebook.net/<?php echo esc_attr($language); ?>/sdk/xfbml.customerchat.js';
							fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));
					</script>
					<div class="fb-customerchat" attribution=setup_tool page_id="<?php echo esc_attr($id_page); ?>"></div><?php
				}
			}
		}
		
		public function adminEnqueueScripts() {
			WPE_CORE_CLASS::adminEnqueueScripts();
			// wp_enqueue_script( 'demo', WPE_PLUGIN_URL . '/assets/admin/js/demo.js', array( 'jquery' ), WPE_PLUGIN_VERSION, true );
			// wp_enqueue_style( 'demo', WPE_PLUGIN_URL . '/assets/admin/css/demo.css', array(), WPE_PLUGIN_VERSION  );
		}
		public function enqueueScripts() {
			WPE_CORE_CLASS::enqueueScripts();
			$opt = wp_load_alloptions();
			$url_image = wp_get_attachment_image_src(get_option('bbfm_image_icon'))[0];
			$id = array();
			foreach (get_option('bbfm_display_page') as $key => $value) {
				if($value == 1){
					array_push($id,get_page_by_path($key)->ID);
					// array_push($id,get_posts(array(
					// 	'name' => $key->ID,
					// 	'posts_per_page' => 1,)
					// ));
				}
			}
			wp_enqueue_style( 'style', WPE_PLUGIN_URL . '/assets/css/style.css', array(), WPE_PLUGIN_VERSION );
			wp_enqueue_script( 'bbfm-builder', WPE_PLUGIN_URL . '/assets/js/script.js', array( 'jquery' ), WPE_PLUGIN_VERSION, true );
			wp_enqueue_script( 'UI','https://code.jquery.com/ui/1.12.1/jquery-ui.js', array( 'jquery' ), WPE_PLUGIN_VERSION, true );
			
			wp_localize_script('bbfm-builder', 'my_options',array(
				'type' => $opt['bbfm_type'],
				'url_page' => $opt['bbfm_url_page'],
				'header_cover' => $opt['bbfm_header_cover'],
				'position_h' => $opt['bbfm_position_h'],
				'position_v' => $opt['bbfm_position_v'],
				'image_icon' => $url_image,
				'upload_icon' => $opt['bbfm_upload_icon'],
				'language' => $opt['bbfm_language'],
				'icon_color' => $opt['bbfm_icon_color'],
				'conditions' => $opt['bbfm_conditions'],
				'open_app' => $opt['bbfm_open_app'],
				'text_custom' => $opt['bbfm_text_custom'],
				'text_button' => $opt['bbfm_text_button'],
				'position_button' => $opt['bbfm_position_button'],
				'icon_width' => $opt['bbfm_icon_width'],
				'icon_height' => $opt['bbfm_icon_height'],
				'icon_radius' => $opt['bbfm_icon_radius'],
				'h_space'     => $opt['bbfm_h_space'],
				'v_space'     => $opt['bbfm_v_space'],
				'display_pages' => $opt['bbfm_display_pages'],
				'display_page' => $id,
			));
		}
		
		public function loadTextDomain() {
			load_plugin_textdomain( WPE_PLUGIN_TEXTDOMAIN, false, WPE_PLUGIN_PATH . '/languages/' );
		}
		
		
	}
	new WPE_PLUGIN_CLASS();
}

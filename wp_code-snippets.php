<?php

/**
 * Order snippets by name
 *
 * Order snippets by name by default in the snippets table.
 */
add_filter( 'code_snippets/list_table/default_orderby', function () {
	return 'name';
} );

/**
 * Order snippets by date
 *
 * Order snippets by last modification date by default in the snippets table.
 */
add_filter( 'code_snippets/list_table/default_orderby', function () {
	return 'modified';
} );

add_filter( 'code_snippets/list_table/default_order', function () {
	return 'desc';
} );

/**
 * Set logo URL of login page
 *
 * Customize the link URL of the header logo above login form.
 */
add_filter( 'login_headerurl', function () {
	 return get_bloginfo( 'url' );
} );

/**
 * Set link text of login page
 *
 * Customize the link text of the header logo above the login form.
 */
add_filter( 'login_headertext', function () {
	 return get_bloginfo( 'description' );
} );

/**
 * Change address fields for WooCommerce
 *
 * Customize WooCommerce checkout address fields.
 */
add_filter( 'woocommerce_default_address_fields', function ( $fields ) {
	$fields['state']['required'] = false;
	unset( $fields['address_2'] ); // Remove this field
	return $fields;
} );

// add_filter( 'woocommerce_billing_fields', function ( $fields ) {
// 	 $fields['billing_phone']['required'] = false;
//	 return $fields;
// } );

/**
 * Change styles of login page
 *
 * Customize style and the image URL of the header logo above login form.
 */
add_action( 'login_enqueue_scripts', function () {
	$logo = get_theme_mod( 'custom_logo' );
	$image = wp_get_attachment_image_src( $logo , 'full' );
	$image_url = $image[0];
	$image_width = $image[1];
	$image_height = $image[2];
?>
<style>
	#login { 
		padding: 20px !important;
	}
	#login h1 a, .login h1 a {
		background-image: url(<?php echo $image_url; ?>);
		background-position: center bottom;
		background-size: auto;
	}
	#login_error, .login form, .login .message {
		border-radius: 10px;
	}
</style>
<?php } );

/**
 * Disable image compression
 *
 * WordPress automatically compresses images to 80-90% of their original size, this snippet stops automatically compressing image, because tell it to load images at 100% quality. (Another alternative to compress images without loss of quality is the following plugin: https://wordpress.org/plugins/wp-smushit/)
 */
add_filter( 'jpeg_quality', function( $arg ) {
    return 100;
} );

add_filter( 'wp_editor_set_quality', function( $arg ) {
	return 100;
} );

/**
 * Remove generator tags
 *
 * Hide version number by removing generator meta tags.
 */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wc_generator' );

/**
 * Stop the Heartbeat API
 *
 * The main consequences of disabling Heartbeat API:
 * 
 *  	(1) Auto save and revisions will not work.
 *  	(2) Not be able to see real-time statistics and information if any of your installed plugin uses heartbeat API.
 * 
 */
add_action( 'init', function() {
	wp_deregister_script('heartbeat');
} );

/**
 * Turn off Google Fonts in Elementor designs
 *
 * Reduces HTTP requests by turning off Google Font and speeds up the page.
 */
add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );

/**
 * Turn off Font Awesome
 *
 * Reduces HTTP requests by turning off Font Awesome and speeds up the page.
 */
add_action( 'wp_enqueue_scripts', function() {
	wp_dequeue_style( 'font-awesome' );
}, 50 );

/**
 * Change the Heartbeat API settings
 *
 * By default the WordPress heart beats once every 15 seconds. You can lower that rate to anything down to one beat every minute. This lessens the load on your server while still giving you the benefits of the Heartbeat API.
 * 
 * Heartbeat starts running automatically when the page loads, you can turn auto-start off from the default Heartbeat settings.
 */
add_filter( 'heartbeat_settings', function( $settings ) {
	$settings['interval'] = 60; //Anything between 15-120 seconds
	$settings['autostart'] = false;
    return $settings;
} );

/**
 * Set all of WordPress generated image sizes
 *
 * WordPress creates the following extra images for every image that is uploaded via the Media Library or Visual Editors:
 * 
 *  	Thumbnail size (Size based on Media settings)
 *  	Medium size (Size based on Media settings)
 *  	Large size (Size based on Media settings)
 *  	Medium Large size (768px)
 *  	2x Medium Large size (1536px)
 *  	2x Large size (2028px)
 *  	Scaled size (Default threshold: 2560px) - This scaled image will be used as the largest available size. If WordPress has generated the scaled image then it will use it and still keep the original image but it won’t be used and takes unnecessary disk space.
 * 
 * Depending on your theme, even more additional image sizes may be created via the following WordPress core functions:
 * 
 *  	Creates a custom size for featured images - set_post_thumbnail_size()
 *  	Creates extra images of any specified size(s) - add_image_size()
 * 
 */
add_action( 'intermediate_image_sizes_advanced', function( $sizes ) {
	
	/* Default WordPress */
	unset( $sizes[ 'thumbnail' ]);
	unset( $sizes[ 'medium' ] );
	unset( $sizes[ 'large' ]);
	unset( $sizes[ 'medium_large' ]);
	unset( $sizes[ '1536x1536' ]);
	unset( $sizes[ '2048x2048' ]);
	
	/* WooCommerce */
	// unset( $sizes[ 'shop_thumbnail' ]);
	// unset( $sizes[ 'shop_catalog' ]);
	unset( $sizes[ 'shop_single' ]);
	unset( $sizes[ 'woocommerce_gallery_thumbnail' ]);
	unset( $sizes[ 'woocommerce_thumbnail' ]);
	unset( $sizes[ 'woocommerce_single' ]);
	
	return $sizes;
} );

/* Scaled: Change the largest available image size instead of disabling (default threshold: 2560px) */
add_filter( 'big_image_size_threshold', function() {
	return 1920;
} );

/**
 * Apply Custom CSS to Admin Area
 *
 * Overwrite and apply custom CSS to the Wordpress admin area.
 */
add_action( 'admin_head', function() {
  echo '<style>
	.wp-list-table.fixed .column-icl_translations {
    	width: auto;
	}
	@media (max-width: 1440px) and (min-width: 768px) {
		.fixed .column-shortcode {
			width: 22%;
		}
	}
  </style>';
} );

/**
 * Disable Dashboard Widgets
 *
 * Customizing the WordPress Dashboard, remove unwanted widgets and clean up the default WP Dashboard.
 */
add_action('wp_dashboard_setup', function() {
	global $wp_meta_boxes;
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);                     // Activity
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);                        // WordPress Events and News
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);                    // Quick Draft
	unset($wp_meta_boxes['dashboard']['normal']['core']['e-dashboard-overview']);                   // Elementor Overview
	unset($wp_meta_boxes['dashboard']['normal']['core']['woocommerce_dashboard_recent_reviews']);   // Woocommerce Recent Reviews
}, 999 );

/**
 * Redirects default login page to the WC account page
 *
 * Redirects default login page to the WooCommerce account page if WooCommerce is installed and active.
 * 
 * Author: MachineITSvcs &lt;contact@machineitservices.com
 * Author URI: https://www.machineitservices.com
 * Plugin URI: https://wordpress.org/plugins/woo-wp-login/
 */
$network_plugins = apply_filters('active_plugins', get_site_option('active_sitewide_plugins'));
$subsite_plugins = apply_filters('active_plugins', get_option('active_plugins'));

if(!is_admin() && (!function_exists('get_blog_status') || function_exists('get_current_blog_id') && empty(get_blog_status(get_current_blog_id(), 'deleted'))) && ((!empty($subsite_plugins) && in_array('woocommerce/woocommerce.php', $subsite_plugins)) || (!empty($network_plugins) && array_key_exists('woocommerce/woocommerce.php', $network_plugins)))) add_action('init', function() {
  $myaccount = wc_get_page_permalink('myaccount');
  global $pagenow;
  if(home_url() != $myaccount) {
    $action_array = array_unique(array_merge(((array) apply_filters('woo_wp_login_actions', array())), array("logout", "rp", "resetpass", "resetpassword", "enter_recovery_mode")));
    if('wp-login.php' == $pagenow && (!isset($_GET['action']) || !in_array($_GET['action'], $action_array)) && !isset($_REQUEST['interim-login'])) {
      if(!empty($_GET['action']) && $_GET['action'] == "lostpassword") {
        unset($_GET['action']);
        wp_redirect($myaccount . strtok(wc_get_endpoint_url('lost-password'), '/') . ((http_build_query($_GET)) ? '?' . http_build_query($_GET) : "" ));
        exit();
      }  else {
        $woo_wp_redirect = ((isset($_GET['redirect_to'])) ? 'redirect-to=' . strtok($_GET['redirect_to'], '?') : "" );
        unset($_GET['redirect_to'],$_GET['loggedout'],$_GET['reauth']);
        $woo_wp_combined_query = (($woo_wp_redirect) ? $woo_wp_redirect : "" ) . ((http_build_query($_GET)) ? (($woo_wp_redirect) ? '&' : "" ) . http_build_query($_GET) : "" );
        wp_redirect($myaccount . (($woo_wp_combined_query) ? '?' . $woo_wp_combined_query : "" ));
        exit();
      }
    }
    if(isset($_GET['redirect-to'])) {
      if((isset($_SERVER['HTTPS']) ? "https" : "http") . '://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?') == $myaccount && is_user_logged_in() ) {
        $myaccount_redirect = $_GET['redirect-to'];
        unset($_GET['redirect-to'],$_GET['reauth']);
        wp_redirect($myaccount_redirect . ((http_build_query($_GET)) ? '?' . http_build_query($_GET) : "" ));
        exit();
      } elseif((isset($_SERVER['HTTPS']) ? "https" : "http") . '://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?') != $myaccount) {
        unset($_GET['redirect-to'],$_GET['reauth']);
        wp_redirect((isset($_SERVER['HTTPS']) ? "https" : "http") . '://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?') . ((http_build_query($_GET)) ? '?' . http_build_query($_GET) : "" ));
        exit();
      }
    }
  }
});


/**
 * Turn off Auto Update
 *
 * WordPress has automatic background updates (since version 3.7). If this snippet is active, auto-update will be disabled.
 */
// add_filter( 'automatic_updater_disabled', '__return_true' );
add_filter( 'auto_update_core', '__return_false' );
add_filter( 'auto_update_plugin', '__return_false' );
add_filter( 'auto_update_theme', '__return_false' );
add_filter( 'auto_update_translation', '__return_false' );

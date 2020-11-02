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
	unset( $fields['address_2'] );
	return $fields;
} );

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

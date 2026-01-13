<?php
/**
 * Viaje a Guatemala Theme Functions
 *
 * @package Viaje a Guatemala
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Theme Constants
define( 'VGUATE_VERSION', '0.1' );
define( 'VGUATE_THEME_DIR', get_template_directory() );
define( 'VGUATE_THEME_URI', get_template_directory_uri() );

/**
 * Include custom post types
 */
require_once VGUATE_THEME_DIR . '/inc/post-types.php';

/**
 * Include enqueue scripts and styles
 */
require_once VGUATE_THEME_DIR . '/inc/enqueue-scripts.php';

/**
 * Redirect home to blog archive
 */
function vguate_redirect_home_to_blog() {
    if ( is_home() && ! is_paged() ) {
        wp_redirect( home_url( '/blog/' ), 301 );
        exit;
    }
}
add_action( 'template_redirect', 'vguate_redirect_home_to_blog' );

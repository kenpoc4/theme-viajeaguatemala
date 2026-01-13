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

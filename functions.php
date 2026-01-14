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
 * Include theme options
 */
require_once VGUATE_THEME_DIR . '/inc/theme-options.php';

/**
 * Theme Setup
 */
function vguate_theme_setup() {
    // Soporte para imágenes destacadas
    add_theme_support( 'post-thumbnails' );

    // Soporte para títulos dinámicos
    add_theme_support( 'title-tag' );

    // Soporte para HTML5
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ) );

    // Registrar menús de navegación
    register_nav_menus( array(
        'primary' => __( 'Menú Principal', 'vguate' ),
    ) );
}
add_action( 'after_setup_theme', 'vguate_theme_setup' );

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

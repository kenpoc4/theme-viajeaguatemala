<?php
/**
 * Enqueue Scripts and Styles
 *
 * Sistema de registro de estilos globales y específicos
 *
 * @package Viaje a Guatemala
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Registrar y encolar estilos del tema
 */
function vguate_enqueue_styles() {
    // ==========================================
    // ESTILOS GLOBALES
    // ==========================================

    // Fuentes (Google Fonts)
    wp_enqueue_style(
        'vguate-fonts',
        VGUATE_THEME_URI . '/assets/css/global/fonts.css',
        array(),
        VGUATE_VERSION
    );

    // Normalize CSS
    wp_enqueue_style(
        'vguate-normalize',
        VGUATE_THEME_URI . '/assets/css/global/normalize.css',
        array( 'vguate-fonts' ),
        VGUATE_VERSION
    );

    // Estilos globales
    wp_enqueue_style(
        'vguate-global',
        VGUATE_THEME_URI . '/assets/css/global/global.css',
        array( 'vguate-normalize' ),
        VGUATE_VERSION
    );

    // ==========================================
    // ESTILOS ESPECÍFICOS
    // ==========================================

    // Estilos específicos por post type (archive)
    if ( is_post_type_archive() ) {
        $post_type = get_query_var( 'post_type' );
        vguate_enqueue_post_type_style( $post_type );
    }

    // Estilos específicos para singles
    if ( is_singular() ) {
        $post_type = get_post_type();
        vguate_enqueue_single_style( $post_type );
    }

    // Estilos específicos para pages
    if ( is_page() ) {
        $page_slug = get_post_field( 'post_name', get_queried_object_id() );
        vguate_enqueue_page_style( $page_slug );
    }
}
add_action( 'wp_enqueue_scripts', 'vguate_enqueue_styles' );

/**
 * Encolar estilo de post type
 *
 * @param string $post_type Slug del post type
 */
function vguate_enqueue_post_type_style( $post_type ) {
    $file_path = VGUATE_THEME_DIR . '/assets/css/post-types/' . $post_type . '.css';
    $file_uri = VGUATE_THEME_URI . '/assets/css/post-types/' . $post_type . '.css';

    if ( file_exists( $file_path ) ) {
        wp_enqueue_style(
            'vguate-archive-' . $post_type,
            $file_uri,
            array( 'vguate-global' ),
            VGUATE_VERSION
        );
    }
}

/**
 * Encolar estilo de single
 *
 * @param string $post_type Slug del post type
 */
function vguate_enqueue_single_style( $post_type ) {
    $file_path = VGUATE_THEME_DIR . '/assets/css/singles/' . $post_type . '.css';
    $file_uri = VGUATE_THEME_URI . '/assets/css/singles/' . $post_type . '.css';

    if ( file_exists( $file_path ) ) {
        wp_enqueue_style(
            'vguate-single-' . $post_type,
            $file_uri,
            array( 'vguate-global' ),
            VGUATE_VERSION
        );
    }
}

/**
 * Encolar estilo de page
 *
 * @param string $page_slug Slug de la página
 */
function vguate_enqueue_page_style( $page_slug ) {
    $file_path = VGUATE_THEME_DIR . '/assets/css/pages/' . $page_slug . '.css';
    $file_uri = VGUATE_THEME_URI . '/assets/css/pages/' . $page_slug . '.css';

    if ( file_exists( $file_path ) ) {
        wp_enqueue_style(
            'vguate-page-' . $page_slug,
            $file_uri,
            array( 'vguate-global' ),
            VGUATE_VERSION
        );
    }
}

/**
 * Registrar y encolar scripts del tema
 */
function vguate_enqueue_scripts() {
    // Aquí se pueden registrar scripts JavaScript en el futuro
}
add_action( 'wp_enqueue_scripts', 'vguate_enqueue_scripts' );

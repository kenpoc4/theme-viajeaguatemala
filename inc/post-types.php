<?php
/**
 * Custom Post Types
 *
 * @package Viaje a Guatemala
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Custom Post Type Helper Function
 *
 * @param array $args {
 *     Array de configuración para el custom post type
 *
 *     @type string $post_type        Slug del post type (requerido)
 *     @type string $singular_name    Nombre singular (requerido)
 *     @type string $plural_name      Nombre plural (requerido)
 *     @type string $menu_icon        Icono del menú (dashicons)
 *     @type array  $supports         Características soportadas
 *     @type bool   $has_archive      Tiene archivo (default: true)
 *     @type string $rewrite_slug     Slug para rewrite (default: post_type)
 *     @type bool   $public           Es público (default: true)
 *     @type bool   $show_in_rest     Mostrar en REST API (default: true)
 *     @type array  $taxonomies       Taxonomías asociadas
 * }
 * @return void
 */
function vguate_register_post_type( $args ) {
    // Validar parámetros requeridos
    if ( empty( $args['post_type'] ) || empty( $args['singular_name'] ) || empty( $args['plural_name'] ) ) {
        return;
    }

    $post_type = $args['post_type'];
    $singular = $args['singular_name'];
    $plural = $args['plural_name'];

    // Labels por defecto
    $labels = array(
        'name'                  => $plural,
        'singular_name'         => $singular,
        'menu_name'             => $plural,
        'name_admin_bar'        => $singular,
        'add_new'               => sprintf( __( 'Agregar %s', 'vguate' ), $singular ),
        'add_new_item'          => sprintf( __( 'Agregar Nuevo %s', 'vguate' ), $singular ),
        'new_item'              => sprintf( __( 'Nuevo %s', 'vguate' ), $singular ),
        'edit_item'             => sprintf( __( 'Editar %s', 'vguate' ), $singular ),
        'view_item'             => sprintf( __( 'Ver %s', 'vguate' ), $singular ),
        'all_items'             => sprintf( __( 'Todos los %s', 'vguate' ), $plural ),
        'search_items'          => sprintf( __( 'Buscar %s', 'vguate' ), $plural ),
        'parent_item_colon'     => sprintf( __( '%s Padre:', 'vguate' ), $singular ),
        'not_found'             => sprintf( __( 'No se encontraron %s.', 'vguate' ), $plural ),
        'not_found_in_trash'    => sprintf( __( 'No se encontraron %s en la papelera.', 'vguate' ), $plural ),
        'archives'              => sprintf( __( 'Archivo de %s', 'vguate' ), $plural ),
    );

    // Merge labels personalizados si existen
    if ( ! empty( $args['labels'] ) ) {
        $labels = array_merge( $labels, $args['labels'] );
    }

    // Configuración del post type
    $post_type_args = array(
        'labels'             => $labels,
        'public'             => isset( $args['public'] ) ? $args['public'] : true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => isset( $args['rewrite_slug'] ) ? $args['rewrite_slug'] : $post_type ),
        'capability_type'    => 'post',
        'has_archive'        => isset( $args['has_archive'] ) ? $args['has_archive'] : true,
        'hierarchical'       => isset( $args['hierarchical'] ) ? $args['hierarchical'] : false,
        'menu_position'      => isset( $args['menu_position'] ) ? $args['menu_position'] : null,
        'menu_icon'          => isset( $args['menu_icon'] ) ? $args['menu_icon'] : 'dashicons-admin-post',
        'supports'           => isset( $args['supports'] ) ? $args['supports'] : array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'       => isset( $args['show_in_rest'] ) ? $args['show_in_rest'] : true,
        'taxonomies'         => isset( $args['taxonomies'] ) ? $args['taxonomies'] : array(),
    );

    // Registrar el post type
    register_post_type( $post_type, $post_type_args );
}

/**
 * Register all custom post types
 */
function vguate_register_custom_post_types() {
    // Custom Post Type: Blog
    vguate_register_post_type( array(
        'post_type'      => 'blog',
        'singular_name'  => 'Entrada de Blog',
        'plural_name'    => 'Blog',
        'menu_icon'      => 'dashicons-edit-large',
        'rewrite_slug'   => 'blog',
        'has_archive'    => true,
        'supports'       => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments', 'revisions' ),
        'taxonomies'     => array( 'category', 'post_tag' ),
        'menu_position'  => 5,
    ) );
}
add_action( 'init', 'vguate_register_custom_post_types' );

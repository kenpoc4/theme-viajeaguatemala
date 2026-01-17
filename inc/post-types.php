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

/**
 * ==========================================
 * Custom Fields para Blog
 * ==========================================
 */

/**
 * Registrar Meta Box para el post type Blog
 */
function vguate_blog_register_meta_boxes() {
    add_meta_box(
        'vguate_blog_description',
        __( 'Descripción', 'vguate' ),
        'vguate_blog_description_meta_box_callback',
        'blog',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'vguate_blog_register_meta_boxes' );

/**
 * Callback para renderizar el meta box de descripción
 */
function vguate_blog_description_meta_box_callback( $post ) {
    // Agregar nonce para seguridad
    wp_nonce_field( 'vguate_blog_description_nonce', 'vguate_blog_description_nonce' );

    // Obtener valor guardado
    $description = get_post_meta( $post->ID, '_vguate_blog_description', true );
    ?>
    <p>
        <label for="vguate_blog_description" style="display: block; margin-bottom: 8px; font-weight: 600;">
            <?php _e( 'Descripción personalizada para esta entrada:', 'vguate' ); ?>
        </label>
        <textarea
            id="vguate_blog_description"
            name="vguate_blog_description"
            rows="4"
            style="width: 100%;"
            placeholder="<?php esc_attr_e( 'Escribe una descripción breve para esta entrada...', 'vguate' ); ?>"
        ><?php echo esc_textarea( $description ); ?></textarea>
    </p>
    <p class="description">
        <?php _e( 'Esta descripción se puede usar en lugar del extracto automático.', 'vguate' ); ?>
    </p>
    <?php
}

/**
 * Guardar el meta box de descripción
 */
function vguate_blog_save_description_meta_box( $post_id ) {
    // Verificar nonce
    if ( ! isset( $_POST['vguate_blog_description_nonce'] ) ||
         ! wp_verify_nonce( $_POST['vguate_blog_description_nonce'], 'vguate_blog_description_nonce' ) ) {
        return;
    }

    // Verificar autoguardado
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Verificar permisos
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Guardar o eliminar el valor
    if ( isset( $_POST['vguate_blog_description'] ) ) {
        $description = sanitize_textarea_field( $_POST['vguate_blog_description'] );
        update_post_meta( $post_id, '_vguate_blog_description', $description );
    }
}
add_action( 'save_post_blog', 'vguate_blog_save_description_meta_box' );

/**
 * Helper function para obtener la descripción del blog post
 *
 * @param int $post_id ID del post (opcional, usa el actual si no se especifica)
 * @return string Descripción del post o string vacío
 */
function vguate_get_blog_post_description( $post_id = null ) {
    if ( null === $post_id ) {
        $post_id = get_the_ID();
    }

    $description = get_post_meta( $post_id, '_vguate_blog_description', true );

    return $description ? $description : '';
}

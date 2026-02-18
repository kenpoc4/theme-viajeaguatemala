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
        'vguate_blog_subtitle',
        __( 'Subtítulo', 'vguate' ),
        'vguate_blog_subtitle_meta_box_callback',
        'blog',
        'normal',
        'high'
    );

    add_meta_box(
        'vguate_blog_description',
        __( 'Descripción', 'vguate' ),
        'vguate_blog_description_meta_box_callback',
        'blog',
        'normal',
        'high'
    );

    add_meta_box(
        'vguate_blog_audio',
        __( 'Audio del Post', 'vguate' ),
        'vguate_blog_audio_meta_box_callback',
        'blog',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'vguate_blog_register_meta_boxes' );

/**
 * Callback para renderizar el meta box de subtítulo
 */
function vguate_blog_subtitle_meta_box_callback( $post ) {
    // Agregar nonce para seguridad
    wp_nonce_field( 'vguate_blog_subtitle_nonce', 'vguate_blog_subtitle_nonce' );

    // Obtener valor guardado
    $subtitle = get_post_meta( $post->ID, '_vguate_blog_subtitle', true );
    ?>
    <p>
        <label for="vguate_blog_subtitle" style="display: block; margin-bottom: 8px; font-weight: 600;">
            <?php _e( 'Subtítulo de la entrada:', 'vguate' ); ?>
        </label>
        <input
            type="text"
            id="vguate_blog_subtitle"
            name="vguate_blog_subtitle"
            value="<?php echo esc_attr( $subtitle ); ?>"
            style="width: 100%;"
            placeholder="<?php esc_attr_e( 'Escribe un subtítulo para esta entrada...', 'vguate' ); ?>"
        />
    </p>
    <p class="description">
        <?php _e( 'El subtítulo aparecerá debajo del título principal en la entrada.', 'vguate' ); ?>
    </p>
    <?php
}

/**
 * Guardar el meta box de subtítulo
 */
function vguate_blog_save_subtitle_meta_box( $post_id ) {
    // Verificar nonce
    if ( ! isset( $_POST['vguate_blog_subtitle_nonce'] ) ||
         ! wp_verify_nonce( $_POST['vguate_blog_subtitle_nonce'], 'vguate_blog_subtitle_nonce' ) ) {
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
    if ( isset( $_POST['vguate_blog_subtitle'] ) ) {
        $subtitle = sanitize_text_field( $_POST['vguate_blog_subtitle'] );
        update_post_meta( $post_id, '_vguate_blog_subtitle', $subtitle );
    }
}
add_action( 'save_post_blog', 'vguate_blog_save_subtitle_meta_box' );

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
 * Helper function para obtener el subtítulo del blog post
 *
 * @param int $post_id ID del post (opcional, usa el actual si no se especifica)
 * @return string Subtítulo del post o string vacío
 */
function vguate_get_blog_post_subtitle( $post_id = null ) {
    if ( null === $post_id ) {
        $post_id = get_the_ID();
    }

    $subtitle = get_post_meta( $post_id, '_vguate_blog_subtitle', true );

    return $subtitle ? $subtitle : '';
}

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

/**
 * ==========================================
 * Campo de Audio para Blog
 * ==========================================
 */

/**
 * Callback para renderizar el meta box de audio
 */
function vguate_blog_audio_meta_box_callback( $post ) {
    // Agregar nonce para seguridad
    wp_nonce_field( 'vguate_blog_audio_nonce', 'vguate_blog_audio_nonce' );

    // Obtener valores guardados
    $audio_id = get_post_meta( $post->ID, '_vguate_blog_audio_id', true );
    $audio_url = get_post_meta( $post->ID, '_vguate_blog_audio_url', true );
    ?>
    <div class="vguate-audio-upload-wrapper">
        <p>
            <label for="vguate_blog_audio" style="display: block; margin-bottom: 8px; font-weight: 600;">
                <?php _e( 'Archivo de audio:', 'vguate' ); ?>
            </label>
            <input
                type="hidden"
                id="vguate_blog_audio_id"
                name="vguate_blog_audio_id"
                value="<?php echo esc_attr( $audio_id ); ?>"
            />
            <input
                type="text"
                id="vguate_blog_audio_url"
                name="vguate_blog_audio_url"
                value="<?php echo esc_url( $audio_url ); ?>"
                style="width: 70%;"
                readonly
                placeholder="<?php esc_attr_e( 'Selecciona un archivo de audio...', 'vguate' ); ?>"
            />
            <button type="button" class="button vguate-upload-audio-button" style="margin-left: 8px;">
                <?php _e( 'Seleccionar Audio', 'vguate' ); ?>
            </button>
            <button type="button" class="button vguate-remove-audio-button" style="margin-left: 4px; <?php echo empty( $audio_url ) ? 'display:none;' : ''; ?>">
                <?php _e( 'Eliminar', 'vguate' ); ?>
            </button>
        </p>

        <!-- Vista previa del audio -->
        <div class="vguate-audio-preview" style="margin-top: 15px; <?php echo empty( $audio_url ) ? 'display:none;' : ''; ?>">
            <audio controls style="width: 100%; max-width: 400px;">
                <source src="<?php echo esc_url( $audio_url ); ?>" type="audio/mpeg">
                <?php _e( 'Tu navegador no soporta el elemento de audio.', 'vguate' ); ?>
            </audio>
        </div>
    </div>

    <p class="description">
        <?php _e( 'Sube o selecciona un archivo de audio (MP3, WAV, OGG) para esta entrada. Los visitantes podrán escuchar el contenido del post.', 'vguate' ); ?>
    </p>
    <?php
}

/**
 * Guardar el meta box de audio
 */
function vguate_blog_save_audio_meta_box( $post_id ) {
    // Verificar nonce
    if ( ! isset( $_POST['vguate_blog_audio_nonce'] ) ||
         ! wp_verify_nonce( $_POST['vguate_blog_audio_nonce'], 'vguate_blog_audio_nonce' ) ) {
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

    // Guardar o eliminar el ID del audio
    if ( isset( $_POST['vguate_blog_audio_id'] ) ) {
        $audio_id = absint( $_POST['vguate_blog_audio_id'] );
        if ( $audio_id ) {
            update_post_meta( $post_id, '_vguate_blog_audio_id', $audio_id );
        } else {
            delete_post_meta( $post_id, '_vguate_blog_audio_id' );
        }
    }

    // Guardar o eliminar la URL del audio
    if ( isset( $_POST['vguate_blog_audio_url'] ) ) {
        $audio_url = esc_url_raw( $_POST['vguate_blog_audio_url'] );
        if ( $audio_url ) {
            update_post_meta( $post_id, '_vguate_blog_audio_url', $audio_url );
        } else {
            delete_post_meta( $post_id, '_vguate_blog_audio_url' );
        }
    }
}
add_action( 'save_post_blog', 'vguate_blog_save_audio_meta_box' );

/**
 * Helper function para obtener el audio del blog post
 *
 * @param int $post_id ID del post (opcional, usa el actual si no se especifica)
 * @return array|false Array con 'id' y 'url' del audio, o false si no hay audio
 */
function vguate_get_blog_post_audio( $post_id = null ) {
    if ( null === $post_id ) {
        $post_id = get_the_ID();
    }

    $audio_id = get_post_meta( $post_id, '_vguate_blog_audio_id', true );
    $audio_url = get_post_meta( $post_id, '_vguate_blog_audio_url', true );

    if ( ! $audio_url ) {
        return false;
    }

    return array(
        'id'  => $audio_id,
        'url' => $audio_url,
    );
}

/**
 * Enqueue scripts para el Media Uploader en el admin
 */
function vguate_blog_admin_scripts( $hook ) {
    global $post;

    // Solo cargar en la página de edición del post type blog
    if ( $hook !== 'post-new.php' && $hook !== 'post.php' ) {
        return;
    }

    if ( ! $post || $post->post_type !== 'blog' ) {
        return;
    }

    // Enqueue WordPress media scripts
    wp_enqueue_media();

    // Inline script para el Media Uploader
    $script = "
    jQuery(document).ready(function($) {
        var mediaUploader;

        // Botón para seleccionar audio
        $('.vguate-upload-audio-button').on('click', function(e) {
            e.preventDefault();

            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            mediaUploader = wp.media({
                title: '" . esc_js( __( 'Seleccionar Archivo de Audio', 'vguate' ) ) . "',
                button: {
                    text: '" . esc_js( __( 'Usar este audio', 'vguate' ) ) . "'
                },
                library: {
                    type: 'audio'
                },
                multiple: false
            });

            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#vguate_blog_audio_id').val(attachment.id);
                $('#vguate_blog_audio_url').val(attachment.url);
                $('.vguate-audio-preview').show();
                $('.vguate-audio-preview audio source').attr('src', attachment.url);
                $('.vguate-audio-preview audio')[0].load();
                $('.vguate-remove-audio-button').show();
            });

            mediaUploader.open();
        });

        // Botón para eliminar audio
        $('.vguate-remove-audio-button').on('click', function(e) {
            e.preventDefault();
            $('#vguate_blog_audio_id').val('');
            $('#vguate_blog_audio_url').val('');
            $('.vguate-audio-preview').hide();
            $(this).hide();
        });
    });
    ";

    wp_add_inline_script( 'jquery', $script );
}
add_action( 'admin_enqueue_scripts', 'vguate_blog_admin_scripts' );

/**
 * Incluir CPT "blog" en las queries de categoría
 *
 * WordPress por defecto solo muestra posts tipo "post" en categorías.
 * Este hook incluye el CPT "blog" para que las páginas de categoría
 * muestren las entradas del blog.
 */
function vguate_blog_custom_queries( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    $per_page = vguate_get_posts_per_page();

    // Categorías: incluir CPT "blog" + posts por página del dashboard
    if ( $query->is_category() ) {
        $query->set( 'post_type', array( 'post', 'blog' ) );
        $query->set( 'posts_per_page', $per_page );
    }

    // Blog archive: posts por página del dashboard
    if ( $query->is_post_type_archive( 'blog' ) ) {
        $query->set( 'posts_per_page', $per_page );
    }
}
add_action( 'pre_get_posts', 'vguate_blog_custom_queries' );

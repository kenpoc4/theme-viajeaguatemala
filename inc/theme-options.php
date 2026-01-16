<?php
/**
 * Theme Options / Settings
 *
 * Página de configuración del tema en el dashboard
 *
 * @package Viaje a Guatemala
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Definir las pestañas disponibles
 */
function vguate_get_theme_options_tabs() {
    return array(
        'blog' => array(
            'title' => __( 'Opciones del Blog', 'vguate' ),
            'icon'  => 'dashicons-admin-post',
        ),
    );
}

/**
 * Obtener la pestaña activa
 */
function vguate_get_active_tab() {
    $tabs = vguate_get_theme_options_tabs();
    $active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'blog';

    // Validar que la pestaña exista
    if ( ! array_key_exists( $active_tab, $tabs ) ) {
        $active_tab = 'blog';
    }

    return $active_tab;
}

/**
 * Agregar página de opciones al menú de administración
 */
function vguate_add_theme_options_page() {
    add_menu_page(
        __( 'Viaje a Guatemala', 'vguate' ),          // Título de la página
        __( 'Viaje a Guatemala', 'vguate' ),          // Título del menú
        'edit_theme_options',                          // Capacidad requerida
        'vguate-theme-options',                        // Slug del menú
        'vguate_theme_options_page_html',              // Función callback
        'dashicons-airplane',                          // Icono del menú
        60                                             // Posición en el menú
    );
}
add_action( 'admin_menu', 'vguate_add_theme_options_page' );

/**
 * Registrar settings
 */
function vguate_register_theme_settings() {
    // Registrar un nuevo setting para las opciones del tema
    register_setting(
        'vguate_theme_options',              // Grupo de opciones
        'vguate_theme_options',              // Nombre de la opción
        array(
            'sanitize_callback' => 'vguate_sanitize_theme_options',
        )
    );

    // ==========================================
    // PESTAÑA: Blog
    // ==========================================

    // Sección: Header Hero
    add_settings_section(
        'vguate_header_section',                        // ID
        __( 'Header Lateral - Hero', 'vguate' ),        // Título
        'vguate_header_section_callback',               // Callback
        'vguate-theme-options-blog'                     // Página (pestaña blog)
    );

    // Campo: Imagen Hero
    add_settings_field(
        'vguate_hero_image',                            // ID
        __( 'Imagen Hero', 'vguate' ),                  // Título
        'vguate_hero_image_callback',                   // Callback
        'vguate-theme-options-blog',                    // Página (pestaña blog)
        'vguate_header_section'                         // Sección
    );

    // Campo: Descripción del Blog
    add_settings_field(
        'vguate_blog_description',                      // ID
        __( 'Descripción del Blog', 'vguate' ),         // Título
        'vguate_blog_description_callback',             // Callback
        'vguate-theme-options-blog',                    // Página (pestaña blog)
        'vguate_header_section'                         // Sección
    );

    // Sección: Logos
    add_settings_section(
        'vguate_logos_section',                         // ID
        __( 'Logos', 'vguate' ),                        // Título
        'vguate_logos_section_callback',                // Callback
        'vguate-theme-options-blog'                     // Página (pestaña blog)
    );

    // Campo: Logo Principal
    add_settings_field(
        'vguate_logo',                                  // ID
        __( 'Logo Principal', 'vguate' ),               // Título
        'vguate_logo_field_callback',                   // Callback
        'vguate-theme-options-blog',                    // Página (pestaña blog)
        'vguate_logos_section',                         // Sección
        array( 'field' => 'logo' )                      // Args
    );

    // Campo: Logo Black
    add_settings_field(
        'vguate_logo_black',                            // ID
        __( 'Logo Black', 'vguate' ),                   // Título
        'vguate_logo_field_callback',                   // Callback
        'vguate-theme-options-blog',                    // Página (pestaña blog)
        'vguate_logos_section',                         // Sección
        array( 'field' => 'logo_black' )                // Args
    );

    // Campo: Logo White
    add_settings_field(
        'vguate_logo_white',                            // ID
        __( 'Logo White', 'vguate' ),                   // Título
        'vguate_logo_field_callback',                   // Callback
        'vguate-theme-options-blog',                    // Página (pestaña blog)
        'vguate_logos_section',                         // Sección
        array( 'field' => 'logo_white' )                // Args
    );
}
add_action( 'admin_init', 'vguate_register_theme_settings' );

/**
 * Callback de la sección header
 */
function vguate_header_section_callback() {
    echo '<p>' . __( 'Configura la imagen que se mostrará en el header lateral del blog.', 'vguate' ) . '</p>';
}

/**
 * Callback de la sección logos
 */
function vguate_logos_section_callback() {
    echo '<p>' . __( 'Configura las diferentes versiones del logo para el blog.', 'vguate' ) . '</p>';
}

/**
 * Callback del campo descripción del blog
 */
function vguate_blog_description_callback() {
    $options = get_option( 'vguate_theme_options' );
    $description = isset( $options['blog_description'] ) ? $options['blog_description'] : '';
    ?>
    <textarea
        id="vguate_blog_description"
        name="vguate_theme_options[blog_description]"
        rows="4"
        class="large-text"
        placeholder="<?php esc_attr_e( 'Escribe una breve descripción del blog...', 'vguate' ); ?>"
    ><?php echo esc_textarea( $description ); ?></textarea>
    <p class="description">
        <?php _e( 'Esta descripción se mostrará en el header lateral del blog.', 'vguate' ); ?>
    </p>
    <?php
}

/**
 * Callback para campos de logo (reutilizable)
 */
function vguate_logo_field_callback( $args ) {
    $field = $args['field'];
    $options = get_option( 'vguate_theme_options' );
    $image_id = isset( $options[ $field ] ) ? $options[ $field ] : '';
    $image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'medium' ) : '';

    $descriptions = array(
        'logo'       => __( 'Logo principal del sitio. Recomendado: PNG con transparencia.', 'vguate' ),
        'logo_black' => __( 'Versión del logo en negro para fondos claros.', 'vguate' ),
        'logo_white' => __( 'Versión del logo en blanco para fondos oscuros.', 'vguate' ),
    );
    ?>
    <div class="vguate-image-upload vguate-logo-upload" data-field="<?php echo esc_attr( $field ); ?>">
        <input
            type="hidden"
            id="vguate_<?php echo esc_attr( $field ); ?>"
            name="vguate_theme_options[<?php echo esc_attr( $field ); ?>]"
            value="<?php echo esc_attr( $image_id ); ?>"
        />

        <div class="vguate-logo-preview <?php echo $field === 'logo_white' ? 'dark-bg' : ''; ?>" style="margin-bottom: 10px;">
            <?php if ( $image_url ) : ?>
                <img
                    src="<?php echo esc_url( $image_url ); ?>"
                    alt="<?php echo esc_attr( $field ); ?>"
                    style="max-width: 200px; max-height: 80px; width: auto; height: auto; display: block;"
                />
            <?php else : ?>
                <img
                    src=""
                    alt="<?php echo esc_attr( $field ); ?>"
                    style="max-width: 200px; max-height: 80px; width: auto; height: auto; display: none;"
                />
            <?php endif; ?>
        </div>

        <button type="button" class="button vguate-upload-logo-button">
            <?php _e( 'Seleccionar Logo', 'vguate' ); ?>
        </button>

        <button type="button" class="button vguate-remove-logo-button" <?php echo $image_url ? '' : 'style="display:none;"'; ?>>
            <?php _e( 'Remover', 'vguate' ); ?>
        </button>

        <p class="description">
            <?php echo esc_html( $descriptions[ $field ] ); ?>
        </p>
    </div>
    <?php
}

/**
 * Callback del campo imagen hero
 */
function vguate_hero_image_callback() {
    $options = get_option( 'vguate_theme_options' );
    $hero_image_id = isset( $options['hero_image'] ) ? $options['hero_image'] : '';
    $hero_image_url = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'full' ) : '';
    ?>
    <div class="vguate-image-upload">
        <input
            type="hidden"
            id="vguate_hero_image"
            name="vguate_theme_options[hero_image]"
            value="<?php echo esc_attr( $hero_image_id ); ?>"
        />

        <div class="vguate-image-preview" style="margin-bottom: 10px;">
            <?php if ( $hero_image_url ) : ?>
                <img
                    src="<?php echo esc_url( $hero_image_url ); ?>"
                    alt="Hero Image"
                    style="max-width: 300px; height: auto; display: block;"
                />
            <?php else : ?>
                <img
                    src=""
                    alt="Hero Image"
                    style="max-width: 300px; height: auto; display: none;"
                />
            <?php endif; ?>
        </div>

        <button type="button" class="button vguate-upload-image-button">
            <?php _e( 'Seleccionar Imagen', 'vguate' ); ?>
        </button>

        <button type="button" class="button vguate-remove-image-button" <?php echo $hero_image_url ? '' : 'style="display:none;"'; ?>>
            <?php _e( 'Remover Imagen', 'vguate' ); ?>
        </button>

        <p class="description">
            <?php _e( 'Selecciona una imagen para mostrar en el header lateral. Recomendado: 800x1200px o mayor.', 'vguate' ); ?>
        </p>
    </div>

    <script type="text/javascript">
    jQuery(document).ready(function($) {
        var mediaUploader;

        // Abrir Media Library
        $('.vguate-upload-image-button').on('click', function(e) {
            e.preventDefault();

            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            mediaUploader = wp.media({
                title: '<?php _e( 'Seleccionar Imagen Hero', 'vguate' ); ?>',
                button: {
                    text: '<?php _e( 'Usar esta imagen', 'vguate' ); ?>'
                },
                multiple: false
            });

            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#vguate_hero_image').val(attachment.id);
                $('.vguate-image-preview img').attr('src', attachment.url).show();
                $('.vguate-remove-image-button').show();
            });

            mediaUploader.open();
        });

        // Remover imagen
        $('.vguate-remove-image-button').on('click', function(e) {
            e.preventDefault();
            $('#vguate_hero_image').val('');
            $('.vguate-image-preview img').attr('src', '').hide();
            $(this).hide();
        });
    });
    </script>
    <?php
}

/**
 * Sanitizar las opciones del tema
 */
function vguate_sanitize_theme_options( $input ) {
    $sanitized = array();

    // Campos de imagen a sanitizar
    $image_fields = array( 'hero_image', 'logo', 'logo_black', 'logo_white' );

    foreach ( $image_fields as $field ) {
        if ( isset( $input[ $field ] ) ) {
            $sanitized[ $field ] = absint( $input[ $field ] );
        }
    }

    // Sanitizar descripción del blog
    if ( isset( $input['blog_description'] ) ) {
        $sanitized['blog_description'] = sanitize_textarea_field( $input['blog_description'] );
    }

    return $sanitized;
}

/**
 * HTML de la página de opciones
 */
function vguate_theme_options_page_html() {
    // Verificar permisos
    if ( ! current_user_can( 'edit_theme_options' ) ) {
        return;
    }

    // Mensaje de guardado
    if ( isset( $_GET['settings-updated'] ) ) {
        add_settings_error(
            'vguate_theme_options_messages',
            'vguate_theme_options_message',
            __( 'Configuración guardada correctamente.', 'vguate' ),
            'updated'
        );
    }

    $tabs = vguate_get_theme_options_tabs();
    $active_tab = vguate_get_active_tab();

    settings_errors( 'vguate_theme_options_messages' );
    ?>
    <div class="wrap vguate-admin-wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

        <!-- Navegación de pestañas -->
        <nav class="nav-tab-wrapper vguate-nav-tabs">
            <?php foreach ( $tabs as $tab_id => $tab ) : ?>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=vguate-theme-options&tab=' . $tab_id ) ); ?>"
                   class="nav-tab <?php echo $active_tab === $tab_id ? 'nav-tab-active' : ''; ?>">
                    <span class="dashicons <?php echo esc_attr( $tab['icon'] ); ?>"></span>
                    <?php echo esc_html( $tab['title'] ); ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <!-- Contenido de las pestañas -->
        <div class="vguate-tab-content">
            <form action="options.php" method="post">
                <?php
                settings_fields( 'vguate_theme_options' );

                // Mostrar contenido según la pestaña activa
                switch ( $active_tab ) {
                    case 'blog':
                        do_settings_sections( 'vguate-theme-options-blog' );
                        break;
                }

                submit_button( __( 'Guardar Configuración', 'vguate' ) );
                ?>
            </form>
        </div>
    </div>
    <?php
}

/**
 * Función helper para obtener la imagen hero
 */
function vguate_get_hero_image() {
    $options = get_option( 'vguate_theme_options' );
    $hero_image_id = isset( $options['hero_image'] ) ? $options['hero_image'] : '';

    if ( $hero_image_id ) {
        return wp_get_attachment_image_url( $hero_image_id, 'full' );
    }

    return false;
}

/**
 * Función helper para obtener la descripción del blog
 */
function vguate_get_blog_description() {
    $options = get_option( 'vguate_theme_options' );
    return isset( $options['blog_description'] ) ? $options['blog_description'] : '';
}

/**
 * Función helper para obtener un logo
 *
 * @param string $type Tipo de logo: 'logo', 'logo_black', 'logo_white'
 * @param string $size Tamaño de imagen: 'full', 'medium', 'thumbnail'
 * @return string|false URL del logo o false si no existe
 */
function vguate_get_logo( $type = 'logo', $size = 'full' ) {
    $valid_types = array( 'logo', 'logo_black', 'logo_white' );

    if ( ! in_array( $type, $valid_types, true ) ) {
        return false;
    }

    $options = get_option( 'vguate_theme_options' );
    $logo_id = isset( $options[ $type ] ) ? $options[ $type ] : '';

    if ( $logo_id ) {
        return wp_get_attachment_image_url( $logo_id, $size );
    }

    return false;
}

/**
 * Función helper para obtener el tag img del logo
 *
 * @param string $type Tipo de logo: 'logo', 'logo_black', 'logo_white'
 * @param array  $attr Atributos adicionales para el img
 * @return string HTML del img o string vacío si no existe
 */
function vguate_get_logo_img( $type = 'logo', $attr = array() ) {
    $valid_types = array( 'logo', 'logo_black', 'logo_white' );

    if ( ! in_array( $type, $valid_types, true ) ) {
        return '';
    }

    $options = get_option( 'vguate_theme_options' );
    $logo_id = isset( $options[ $type ] ) ? $options[ $type ] : '';

    if ( $logo_id ) {
        $default_attr = array(
            'class' => 'site-logo site-logo--' . str_replace( '_', '-', $type ),
            'alt'   => get_bloginfo( 'name' ),
        );
        $attr = wp_parse_args( $attr, $default_attr );

        return wp_get_attachment_image( $logo_id, 'full', false, $attr );
    }

    return '';
}

/**
 * Encolar scripts para el admin
 */
function vguate_admin_enqueue_scripts( $hook ) {
    // Solo cargar en nuestra página de opciones
    if ( 'toplevel_page_vguate-theme-options' !== $hook ) {
        return;
    }

    // Encolar media uploader
    wp_enqueue_media();

    // Estilos para las pestañas
    wp_add_inline_style( 'wp-admin', '
        .vguate-admin-wrap {
            max-width: 1200px;
        }

        .vguate-nav-tabs {
            margin-bottom: 0;
            border-bottom: 1px solid #c3c4c7;
            padding-top: 10px;
        }

        .vguate-nav-tabs .nav-tab {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 16px;
            font-weight: 500;
            background: #f0f0f1;
            border: 1px solid #c3c4c7;
            border-bottom: none;
            margin-left: 4px;
            border-radius: 4px 4px 0 0;
            transition: all 0.2s ease;
        }

        .vguate-nav-tabs .nav-tab:first-child {
            margin-left: 0;
        }

        .vguate-nav-tabs .nav-tab:hover {
            background: #fff;
        }

        .vguate-nav-tabs .nav-tab-active {
            background: #fff;
            border-bottom: 1px solid #fff;
            margin-bottom: -1px;
            color: #1d2327;
        }

        .vguate-nav-tabs .nav-tab .dashicons {
            font-size: 18px;
            width: 18px;
            height: 18px;
        }

        .vguate-tab-content {
            background: #fff;
            border: 1px solid #c3c4c7;
            border-top: none;
            padding: 24px;
            border-radius: 0 0 4px 4px;
        }

        .vguate-tab-content h2 {
            margin-top: 0;
            padding-top: 0;
            border-bottom: 1px solid #f0f0f1;
            padding-bottom: 12px;
        }

        .vguate-tab-content .form-table {
            margin-top: 0;
        }

        /* Estilos para logos */
        .vguate-logo-upload {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            gap: 8px;
        }

        .vguate-logo-upload .vguate-logo-preview {
            width: 100%;
            padding: 16px;
            background: #f9f9f9;
            border: 1px dashed #c3c4c7;
            border-radius: 4px;
            min-height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .vguate-logo-upload .vguate-logo-preview.dark-bg {
            background: #1d2327;
        }

        .vguate-logo-upload .description {
            width: 100%;
            margin-top: 4px;
        }
    ' );

    // JavaScript para los logos
    wp_add_inline_script( 'jquery', '
        jQuery(document).ready(function($) {
            // Logo upload
            $(".vguate-upload-logo-button").on("click", function(e) {
                e.preventDefault();
                var button = $(this);
                var container = button.closest(".vguate-logo-upload");
                var field = container.data("field");

                var mediaUploader = wp.media({
                    title: "Seleccionar Logo",
                    button: { text: "Usar este logo" },
                    multiple: false
                });

                mediaUploader.on("select", function() {
                    var attachment = mediaUploader.state().get("selection").first().toJSON();
                    container.find("input[type=hidden]").val(attachment.id);
                    container.find(".vguate-logo-preview img").attr("src", attachment.url).show();
                    container.find(".vguate-remove-logo-button").show();
                });

                mediaUploader.open();
            });

            // Remove logo
            $(".vguate-remove-logo-button").on("click", function(e) {
                e.preventDefault();
                var container = $(this).closest(".vguate-logo-upload");
                container.find("input[type=hidden]").val("");
                container.find(".vguate-logo-preview img").attr("src", "").hide();
                $(this).hide();
            });
        });
    ' );
}
add_action( 'admin_enqueue_scripts', 'vguate_admin_enqueue_scripts' );

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
 * Agregar página de opciones al menú de administración
 */
function vguate_add_theme_options_page() {
    add_theme_page(
        __( 'Configuración del Tema', 'vguate' ),     // Título de la página
        __( 'Opciones del Tema', 'vguate' ),          // Título del menú
        'edit_theme_options',                          // Capacidad requerida
        'vguate-theme-options',                        // Slug del menú
        'vguate_theme_options_page_html'               // Función callback
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

    // Sección: Header Hero
    add_settings_section(
        'vguate_header_section',                        // ID
        __( 'Header Lateral - Hero', 'vguate' ),        // Título
        'vguate_header_section_callback',               // Callback
        'vguate-theme-options'                          // Página
    );

    // Campo: Imagen Hero
    add_settings_field(
        'vguate_hero_image',                            // ID
        __( 'Imagen Hero', 'vguate' ),                  // Título
        'vguate_hero_image_callback',                   // Callback
        'vguate-theme-options',                         // Página
        'vguate_header_section'                         // Sección
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

    // Sanitizar el ID de la imagen (debe ser un número)
    if ( isset( $input['hero_image'] ) ) {
        $sanitized['hero_image'] = absint( $input['hero_image'] );
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

    settings_errors( 'vguate_theme_options_messages' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'vguate_theme_options' );
            do_settings_sections( 'vguate-theme-options' );
            submit_button( __( 'Guardar Configuración', 'vguate' ) );
            ?>
        </form>
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
 * Encolar scripts para el admin
 */
function vguate_admin_enqueue_scripts( $hook ) {
    // Solo cargar en nuestra página de opciones
    if ( 'appearance_page_vguate-theme-options' !== $hook ) {
        return;
    }

    // Encolar media uploader
    wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'vguate_admin_enqueue_scripts' );

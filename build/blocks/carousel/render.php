<?php
/**
 * Carrusel de Imágenes — Renderizado en servidor
 *
 * Variables disponibles:
 *   $attributes (array) — Atributos del bloque.
 *
 * @package Viaje a Guatemala
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$images      = isset( $attributes['images'] ) ? $attributes['images'] : array();
$auto_select = ! empty( $attributes['autoSelect'] );

// Filtrar slots vacíos
$valid_images = array_values(
    array_filter( $images, function ( $img ) {
        return is_array( $img ) && ! empty( $img['url'] );
    } )
);

if ( empty( $valid_images ) ) {
    return;
}

$total = count( $valid_images );

/**
 * Closure: obtiene las URLs para los distintos tamaños de una imagen.
 * Se define como función anónima para evitar redeclaración cuando hay
 * múltiples instancias del bloque en la misma página.
 *
 * @param  array $image  Array con keys 'id', 'url', 'alt'.
 * @return array         Keys: 'thumb', 'preview', 'full'.
 */
$get_image_sizes = function ( $image ) {
    $fallback = esc_url( $image['url'] );
    $sizes    = array(
        'thumb'   => $fallback, // Carrusel (medium_large ≈ 768px)
        'preview' => $fallback, // Panel inferior (large ≈ 1024px)
        'full'    => $fallback, // Lightbox (tamaño original)
    );

    if ( ! empty( $image['id'] ) ) {
        $id = absint( $image['id'] );

        $thumb = wp_get_attachment_image_src( $id, 'medium_large' );
        if ( $thumb ) $sizes['thumb'] = esc_url( $thumb[0] );

        $large = wp_get_attachment_image_src( $id, 'large' );
        if ( $large ) $sizes['preview'] = esc_url( $large[0] );

        $full = wp_get_attachment_image_src( $id, 'full' );
        if ( $full ) $sizes['full'] = esc_url( $full[0] );
    }

    return $sizes;
};

// Iconos SVG reutilizables
$icon_prev   = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$icon_next   = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$icon_expand = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$icon_close  = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$icon_x      = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
?>

<div class="vguate-carousel-wrapper">

    <?php /* ── Carrusel ───────────────────────────────────────────── */ ?>
    <div class="vguate-carousel" data-total="<?php echo esc_attr( $total ); ?>"<?php if ( $auto_select ) echo ' data-auto-select="true"'; ?>>

        <div class="vguate-carousel__track-wrapper">
            <div class="vguate-carousel__track">
                <?php foreach ( $valid_images as $index => $image ) :
                    $sizes = $get_image_sizes( $image );
                    $alt   = esc_attr( isset( $image['alt'] ) ? $image['alt'] : '' );
                    // Las 3 primeras imágenes son visibles sin scroll → eager + high priority
                    $loading  = $index < 3 ? 'eager' : 'lazy';
                    $priority = $index < 3 ? 'fetchpriority="high"' : '';
                ?>
                    <div
                        class="vguate-carousel__slide"
                        data-preview="<?php echo $sizes['preview']; ?>"
                        data-full="<?php echo $sizes['full']; ?>"
                        data-alt="<?php echo $alt; ?>"
                        tabindex="0"
                        role="button"
                        aria-label="<?php printf( esc_attr__( 'Ver imagen %d ampliada', 'vguate' ), $index + 1 ); ?>"
                    >
                        <img
                            src="<?php echo $sizes['thumb']; ?>"
                            alt="<?php echo $alt; ?>"
                            loading="<?php echo $loading; ?>"
                            decoding="async"
                            <?php echo $priority; ?>
                        >
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <button class="vguate-carousel__btn vguate-carousel__btn--prev" aria-label="<?php esc_attr_e( 'Imagen anterior', 'vguate' ); ?>">
            <?php echo $icon_prev; ?>
        </button>
        <button class="vguate-carousel__btn vguate-carousel__btn--next" aria-label="<?php esc_attr_e( 'Imagen siguiente', 'vguate' ); ?>">
            <?php echo $icon_next; ?>
        </button>

    </div>
    <?php /* ── Fin Carrusel ─────────────────────────────────────── */ ?>


    <?php /* ── Panel de vista previa ──────────────────────────────── */ ?>
    <div
        class="vguate-carousel__preview"
        aria-hidden="true"
        aria-live="polite"
        aria-label="<?php esc_attr_e( 'Vista previa de imagen', 'vguate' ); ?>"
    >
        <div class="vguate-carousel__preview-inner">

            <?php /* Área de imagen con skeleton y botones superpuestos */ ?>
            <div class="vguate-carousel__preview-media">
                <div class="vguate-carousel__preview-skeleton" aria-hidden="true"></div>
                <img
                    class="vguate-carousel__preview-img"
                    src=""
                    alt=""
                    loading="eager"
                    decoding="async"
                >

                <?php /* Acciones: sobre la imagen, esquina inferior derecha */ ?>
                <div class="vguate-carousel__preview-actions">
                    <?php if ( ! $auto_select ) : ?>
                    <button
                        class="vguate-carousel__preview-btn vguate-carousel__preview-btn--close"
                        aria-label="<?php esc_attr_e( 'Cerrar vista previa', 'vguate' ); ?>"
                    >
                        <?php echo $icon_close; ?>
                        <?php esc_html_e( 'Cerrar', 'vguate' ); ?>
                    </button>
                    <?php endif; ?>
                    <button
                        class="vguate-carousel__preview-btn vguate-carousel__preview-btn--expand"
                        aria-label="<?php esc_attr_e( 'Ver en pantalla completa', 'vguate' ); ?>"
                    >
                        <?php echo $icon_expand; ?>
                        <?php esc_html_e( 'Expandir', 'vguate' ); ?>
                    </button>
                </div>
            </div>

        </div>
    </div>
    <?php /* ── Fin Panel de vista previa ──────────────────────────── */ ?>


    <?php /* ── Lightbox ─────────────────────────────────────────── */ ?>
    <div
        class="vguate-lightbox"
        role="dialog"
        aria-modal="true"
        aria-hidden="true"
        aria-label="<?php esc_attr_e( 'Imagen en pantalla completa', 'vguate' ); ?>"
    >
        <div class="vguate-lightbox__backdrop"></div>

        <div class="vguate-lightbox__container">
            <div class="vguate-lightbox__loader" aria-hidden="true">
                <div class="vguate-lightbox__spinner"></div>
            </div>
            <img
                class="vguate-lightbox__img"
                src=""
                alt=""
                loading="eager"
                decoding="async"
            >
        </div>

        <?php /* Botón cerrar: hijo directo del lightbox (position:fixed) para que
                 top/right sean siempre relativo al viewport, sin importar el tamaño
                 de la imagen. */ ?>
        <button
            class="vguate-lightbox__close"
            aria-label="<?php esc_attr_e( 'Cerrar lightbox', 'vguate' ); ?>"
        >
            <?php echo $icon_x; ?>
        </button>
    </div>
    <?php /* ── Fin Lightbox ──────────────────────────────────────── */ ?>

</div>

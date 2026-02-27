<?php
/**
 * Registro de Bloques Gutenberg
 *
 * @package Viaje a Guatemala
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Registrar la categoría personalizada de bloques.
 * Aparece como "Bloques Viaje a Guatemala" en el insertor.
 *
 * @param array $categories Categorías existentes.
 * @return array
 */
function vguate_register_block_categories( $categories ) {
    return array_merge(
        array(
            array(
                'slug'  => 'vguate-blocks',
                'title' => 'Bloques Viaje a Guatemala',
                'icon'  => 'location-alt',
            ),
        ),
        $categories
    );
}
add_filter( 'block_categories_all', 'vguate_register_block_categories' );

/**
 * Registrar bloques del tema.
 * Cada bloque se auto-configura desde su block.json.
 */
function vguate_register_blocks() {
    register_block_type( VGUATE_THEME_DIR . '/build/blocks/carousel' );
}
add_action( 'init', 'vguate_register_blocks' );

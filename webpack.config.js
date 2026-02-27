const defaultExport = require( '@wordpress/scripts/config/webpack.config' );
const path          = require( 'path' );

// @wordpress/scripts >= 27 puede exportar un array [scriptConfig, moduleConfig].
// Tomamos solo el primero (build estándar, no ESM).
const defaultConfig = Array.isArray( defaultExport ) ? defaultExport[ 0 ] : defaultExport;

// Extendemos el CopyPlugin existente para que también copie archivos CSS.
// (Por defecto solo copia block.json y *.php desde src/)
const plugins = ( defaultConfig.plugins || [] ).map( ( plugin ) => {
    if ( plugin.constructor?.name === 'CopyPlugin' ) {
        plugin.patterns.push( {
            from: '**/*.css',
            context: path.resolve( __dirname, 'src' ),
            noErrorOnMissing: true,
        } );
    }
    return plugin;
} );

/**
 * Configuración de Webpack para los bloques Gutenberg.
 *
 * Fuente   : src/blocks/{nombre}/
 * Compilado: build/blocks/{nombre}/
 *
 * Para agregar un nuevo bloque:
 *   1. Crea src/blocks/{nombre}/  con index.js, view.js, block.json, CSS, render.php
 *   2. Agrega las dos entradas abajo.
 */
module.exports = {
    ...defaultConfig,

    entry: {
        // Bloque: Carrusel de Imágenes
        'blocks/carousel/index': path.resolve( __dirname, 'src/blocks/carousel/index.js' ),
        'blocks/carousel/view':  path.resolve( __dirname, 'src/blocks/carousel/view.js' ),
    },

    output: {
        ...defaultConfig.output,
        path:     path.resolve( __dirname, 'build' ),
        filename: '[name].js',
    },

    plugins,
};

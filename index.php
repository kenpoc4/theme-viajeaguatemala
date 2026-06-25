<?php
/**
 * Template principal - Redirige al blog
 *
 * @package Viaje a Guatemala
 */

// Redirigir a la página de blog (fallback para plantillas sin coincidencia).
wp_safe_redirect( home_url( '/blog/' ), 302 );
exit;
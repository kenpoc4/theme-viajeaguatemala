<?php
/**
 * Template principal - Redirige al blog
 *
 * @package Viaje a Guatemala
 */

// Redirigir a la página de blog
wp_redirect( home_url( '/blog/' ) );
exit;
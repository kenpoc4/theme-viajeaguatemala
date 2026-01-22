<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="site-wrapper">
    <!-- Header Lateral Sticky -->
    <?php
    // Determinar la imagen hero según el contexto
    if ( is_singular( 'blog' ) && has_post_thumbnail() ) {
        // En single blog, usar la imagen destacada del post
        $hero_image = get_the_post_thumbnail_url( get_the_ID(), 'full' );
    } else {
        // En archivo, usar la imagen hero de las opciones del tema
        $hero_image = vguate_get_hero_image();
    }

    $header_style = '';
    $header_class = 'site-header site-header--lateral';

    // Agregar clase específica para single blog
    if ( is_singular( 'blog' ) ) {
        $header_class .= ' site-header--single';
    }

    if ( $hero_image ) {
        $header_style = 'style="background-image: url(' . esc_url( $hero_image ) . '); background-size: cover; background-position: center; background-repeat: no-repeat;"';
        $header_class .= ' has-hero-image';
    }
    ?>
    <header class="<?php echo esc_attr( $header_class ); ?>" <?php echo $header_style; ?>>
        <div class="site-header__inner">
            <!-- Logo/Título del sitio -->
            <div class="site-header__branding">
                <?php if ( is_post_type_archive( 'blog' ) || is_singular( 'blog' ) ) : ?>
                    <?php
                    // Determinar qué logo usar según si hay imagen hero
                    $logo_type = $hero_image ? 'logo_white' : 'logo';
                    $logo_img = vguate_get_logo_img( $logo_type );
                    $is_single_post = is_singular( 'blog' );
                    ?>
                    <?php if ( $logo_img ) : ?>
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'blog' ) ); ?>" class="site-logo-link<?php echo $is_single_post ? ' site-logo-link--small' : ''; ?>">
                            <?php echo $logo_img; ?>
                        </a>
                    <?php else : ?>
                        <h1 class="site-title">
                            <a href="<?php echo esc_url( get_post_type_archive_link( 'blog' ) ); ?>">
                                <?php bloginfo( 'name' ); ?>
                            </a>
                        </h1>
                    <?php endif; ?>

                    <?php if ( ! $is_single_post ) :
                        // En archivo: mostrar descripción del blog
                        $blog_description = vguate_get_blog_description();
                        if ( $blog_description ) : ?>
                        <p class="site-description site-description--blog"><?php echo esc_html( $blog_description ); ?></p>
                    <?php endif; endif; ?>
                <?php else : ?>
                    <h1 class="site-title">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                            <?php bloginfo( 'name' ); ?>
                        </a>
                    </h1>
                    <?php
                    $description = get_bloginfo( 'description', 'display' );
                    if ( $description || is_customize_preview() ) :
                        ?>
                        <p class="site-description"><?php echo $description; ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <?php if ( is_singular( 'blog' ) ) :
                $subtitle = vguate_get_blog_post_subtitle();
                $audio = vguate_get_blog_post_audio();
            ?>
                <!-- Contenedor centrado: Subtítulo + Botón escuchar -->
                <div class="site-header__post-center">
                    <?php if ( $subtitle ) : ?>
                        <p class="site-header__post-subtitle"><?php echo esc_html( $subtitle ); ?></p>
                    <?php endif; ?>

                    <?php if ( $audio ) : ?>
                    <!-- Botón de escuchar post -->
                    <button class="site-header__listen-btn" type="button" aria-label="Escuchar el post" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                            <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
                        </svg>
                        <span class="site-header__listen-text">Escuchar el post</span>
                    </button>
                    <?php endif; ?>
                </div>

                <?php if ( $audio ) : ?>
                <!-- Reproductor de audio expandido -->
                <div class="site-header__audio-player">
                    <button class="site-header__audio-close" type="button" aria-label="Cerrar reproductor">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                    <div class="site-header__audio-player-inner">
                        <h3 class="site-header__audio-title"><?php the_title(); ?></h3>
                        <audio class="site-header__audio" controls preload="metadata">
                            <source src="<?php echo esc_url( $audio['url'] ); ?>" type="audio/mpeg">
                            Tu navegador no soporta el elemento de audio.
                        </audio>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Navegación Principal -->
            <nav class="site-header__navigation">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'primary-menu',
                    'container'      => false,
                    'fallback_cb'    => false,
                ) );
                ?>
            </nav>

            <!-- Información adicional o widgets del header -->
            <div class="site-header__info">
                <?php if ( is_singular( 'blog' ) ) : ?>
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'blog' ) ); ?>" class="site-header__back-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        <span>Volver al blog</span>
                    </a>
                <?php else : ?>
                    <p>Descubre Guatemala</p>
                <?php endif; ?>
            </div>

            <!-- Línea decorativa con icono animado -->
            <div class="site-header__divider">
                <span class="site-header__divider-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                        <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                    </svg>
                </span>
            </div>
        </div>
    </header>

    <!-- Contenedor principal para el contenido -->
    <div class="site-content">

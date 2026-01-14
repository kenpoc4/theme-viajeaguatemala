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
    $hero_image = vguate_get_hero_image();
    $header_style = '';
    $header_class = 'site-header site-header--lateral';
    if ( $hero_image ) {
        $header_style = 'style="background-image: url(' . esc_url( $hero_image ) . '); background-size: cover; background-position: center; background-repeat: no-repeat;"';
        $header_class .= ' has-hero-image';
    }
    ?>
    <header class="<?php echo esc_attr( $header_class ); ?>" <?php echo $header_style; ?>>
        <div class="site-header__inner">
            <!-- Logo/Título del sitio -->
            <div class="site-header__branding">
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
            </div>

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
                <p>Descubre Guatemala</p>
            </div>
        </div>
    </header>

    <!-- Contenedor principal para el contenido -->
    <div class="site-content">

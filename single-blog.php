<?php
/**
 * Template para entradas individuales del Blog
 *
 * @package Viaje a Guatemala
 */

get_header(); ?>

<main id="main" class="site-main blog-single">
    <?php
    while ( have_posts() ) :
        the_post();

        // Obtener la primera categoría del post
        $categories = get_the_category();
        $first_category = ! empty( $categories ) ? $categories[0] : null;
        ?>

        <!-- Breadcrumbs -->
        <nav class="breadcrumbs" aria-label="Breadcrumb">
            <ol class="breadcrumbs__list">
                <li class="breadcrumbs__item">
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'blog' ) ); ?>" class="breadcrumbs__link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Blog</span>
                    </a>
                </li>
                <?php if ( $first_category ) : ?>
                    <li class="breadcrumbs__item">
                        <span class="breadcrumbs__separator">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </span>
                        <a href="<?php echo esc_url( get_category_link( $first_category->term_id ) ); ?>" class="breadcrumbs__link">
                            <?php echo esc_html( $first_category->name ); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="breadcrumbs__item breadcrumbs__item--current">
                    <span class="breadcrumbs__separator">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </span>
                    <span class="breadcrumbs__current"><?php the_title(); ?></span>
                </li>
            </ol>
        </nav>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-single__article' ); ?>>
            <!-- Meta información del post -->
            <div class="blog-single__meta">
                <span class="blog-single__date">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <?php echo esc_html( get_the_date( 'F j, Y' ) ); ?>
                </span>

                <?php
                $categories = get_the_category();
                if ( ! empty( $categories ) ) :
                ?>
                    <span class="blog-single__categories">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                        </svg>
                        <?php
                        $cat_links = array();
                        foreach ( $categories as $category ) {
                            $cat_links[] = '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
                        }
                        echo implode( ', ', $cat_links );
                        ?>
                    </span>
                <?php endif; ?>

                <?php
                // Tiempo de lectura estimado
                $content = get_the_content();
                $word_count = str_word_count( strip_tags( $content ) );
                $reading_time = ceil( $word_count / 200 ); // 200 palabras por minuto
                ?>
                <span class="blog-single__reading-time">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <?php printf( _n( '%d min de lectura', '%d min de lectura', $reading_time, 'vguate' ), $reading_time ); ?>
                </span>
            </div>

            <!-- Contenido del post -->
            <div class="blog-single__content">
                <?php the_content(); ?>
            </div>

            <!-- Tags del post -->
            <?php
            $tags = get_the_tags();
            if ( ! empty( $tags ) ) :
            ?>
                <div class="blog-single__tags">
                    <span class="blog-single__tags-label">Etiquetas:</span>
                    <?php
                    foreach ( $tags as $tag ) {
                        echo '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" class="blog-single__tag">' . esc_html( $tag->name ) . '</a>';
                    }
                    ?>
                </div>
            <?php endif; ?>

            <!-- Navegación entre posts -->
            <nav class="blog-single__navigation">
                <?php
                $prev_post = get_previous_post();
                $next_post = get_next_post();
                ?>

                <?php if ( $prev_post ) : ?>
                    <a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>" class="blog-single__nav-link blog-single__nav-link--prev">
                        <span class="blog-single__nav-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            Anterior
                        </span>
                        <span class="blog-single__nav-title"><?php echo esc_html( get_the_title( $prev_post ) ); ?></span>
                    </a>
                <?php endif; ?>

                <?php if ( $next_post ) : ?>
                    <a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" class="blog-single__nav-link blog-single__nav-link--next">
                        <span class="blog-single__nav-label">
                            Siguiente
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                        <span class="blog-single__nav-title"><?php echo esc_html( get_the_title( $next_post ) ); ?></span>
                    </a>
                <?php endif; ?>
            </nav>

        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>

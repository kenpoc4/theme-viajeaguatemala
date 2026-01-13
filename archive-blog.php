<?php
/**
 * Template para el archivo del Blog
 *
 * @package Viaje a Guatemala
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title">Blog - Viaje a Guatemala</h1>
            <?php
            $archive_description = get_the_archive_description();
            if ( $archive_description ) :
                ?>
                <div class="archive-description"><?php echo wp_kses_post( wpautop( $archive_description ) ); ?></div>
            <?php endif; ?>
        </header>

        <?php if ( have_posts() ) : ?>

            <div class="blog-posts">
                <?php
                while ( have_posts() ) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <?php
                            if ( has_post_thumbnail() ) :
                                ?>
                                <div class="post-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail( 'large' ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <h2 class="entry-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>

                            <div class="entry-meta">
                                <span class="posted-on">
                                    <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                        <?php echo esc_html( get_the_date() ); ?>
                                    </time>
                                </span>
                                <span class="byline">
                                    por <?php the_author(); ?>
                                </span>
                            </div>
                        </header>

                        <div class="entry-summary">
                            <?php the_excerpt(); ?>
                        </div>

                        <footer class="entry-footer">
                            <a href="<?php the_permalink(); ?>" class="read-more">
                                Leer más
                            </a>
                        </footer>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php
            // Paginación
            the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => __( '&laquo; Anterior', 'vguate' ),
                'next_text' => __( 'Siguiente &raquo;', 'vguate' ),
            ) );
            ?>

        <?php else : ?>

            <div class="no-results">
                <h2>No se encontraron entradas</h2>
                <p>No hay entradas de blog publicadas todavía.</p>
            </div>

        <?php endif; ?>
    </div>
</main>

<?php
get_footer();

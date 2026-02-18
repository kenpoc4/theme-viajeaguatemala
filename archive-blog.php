<?php
/**
 * Template para el archivo del Blog
 *
 * @package Viaje a Guatemala
 */

get_header(); ?>

<main id="main" class="site-main blog-archive">
    <!-- Tabs de navegación -->
    <nav class="blog-tabs">
        <button class="blog-tab blog-tab--active" data-tab="posts">
            Posts recientes
        </button>
        <button class="blog-tab" data-tab="categorias">
            Categorías
        </button>
    </nav>

    <!-- Tab: Posts recientes -->
    <div class="blog-tab-content blog-tab-content--active" data-tab-content="posts">
        <?php if ( have_posts() ) : ?>

            <div class="blog-posts">
                <?php
                while ( have_posts() ) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-post-card' ); ?>>
                        <!-- SVG para efecto de borde -->
                        <svg class="card-border-svg" preserveAspectRatio="none">
                            <rect class="card-border-rect" x="1" y="1" rx="12" ry="12" />
                        </svg>

                        <div class="entry-content">
                            <h2 class="entry-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>

                            <?php
                            $descripcion = vguate_get_blog_post_description();
                            if ( ! $descripcion ) {
                                $descripcion = wp_trim_words( get_the_excerpt(), 15 );
                            }
                            ?>
                            <p class="entry-excerpt"><?php echo esc_html( $descripcion ); ?></p>

                            <div class="entry-footer">
                                <span class="entry-date">
                                    <?php echo esc_html( get_the_date( 'M d, Y' ) ); ?>
                                </span>
                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    Leer más →
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php
            // Paginación personalizada prev/next
            $current_page = max( 1, get_query_var( 'paged' ) );
            $total_pages  = $wp_query->max_num_pages;

            if ( $total_pages > 1 ) :
            ?>
                <nav class="blog-pagination" aria-label="Paginación de entradas">
                    <!-- Botón Anterior -->
                    <div class="blog-pagination__prev">
                        <?php if ( $current_page > 1 ) : ?>
                            <a href="<?php echo esc_url( get_pagenum_link( $current_page - 1 ) ); ?>" class="blog-pagination__btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="19" y1="12" x2="5" y2="12"></line>
                                    <polyline points="12 19 5 12 12 5"></polyline>
                                </svg>
                                <span>Anterior</span>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Indicador de página -->
                    <div class="blog-pagination__indicator">
                        <span class="blog-pagination__current"><?php echo esc_html( $current_page ); ?></span>
                        <span class="blog-pagination__separator">/</span>
                        <span class="blog-pagination__total"><?php echo esc_html( $total_pages ); ?></span>
                    </div>

                    <!-- Botón Siguiente -->
                    <div class="blog-pagination__next">
                        <?php if ( $current_page < $total_pages ) : ?>
                            <a href="<?php echo esc_url( get_pagenum_link( $current_page + 1 ) ); ?>" class="blog-pagination__btn">
                                <span>Siguiente</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </nav>
            <?php endif; ?>

        <?php else : ?>

            <div class="no-results">
                <h2>No se encontraron entradas</h2>
                <p>No hay entradas de blog publicadas todavía.</p>
            </div>

        <?php endif; ?>
    </div>

    <!-- Tab: Categorías -->
    <div class="blog-tab-content" data-tab-content="categorias">
        <?php
        $categories = get_terms( array(
            'taxonomy'   => 'category',
            'hide_empty' => true,
            'exclude'    => array( get_cat_ID( 'Uncategorized' ) ),
        ) );

        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
        ?>
            <ul class="blog-categories-list">
                <?php foreach ( $categories as $category ) : ?>
                    <li class="blog-categories-list__item">
                        <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="blog-categories-list__link">
                            <span class="blog-categories-list__name"><?php echo esc_html( $category->name ); ?></span>
                            <span class="blog-categories-list__count"><?php echo esc_html( $category->count ); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <div class="no-results">
                <h2>No hay categorías</h2>
                <p>No se han creado categorías todavía.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tabs functionality
    const tabs = document.querySelectorAll('.blog-tab');
    const contents = document.querySelectorAll('.blog-tab-content');

    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');

            tabs.forEach(function(t) {
                t.classList.remove('blog-tab--active');
            });
            contents.forEach(function(c) {
                c.classList.remove('blog-tab-content--active');
            });

            this.classList.add('blog-tab--active');
            document.querySelector('[data-tab-content="' + targetTab + '"]').classList.add('blog-tab-content--active');
        });
    });

    // SVG Border Animation
    function initCardBorders() {
        const cards = document.querySelectorAll('.blog-post-card');

        cards.forEach(function(card) {
            const rect = card.querySelector('.card-border-rect');
            if (!rect) return;

            const width = card.offsetWidth;
            const height = card.offsetHeight;
            const rx = 12; // border-radius

            // Calcular perímetro aproximado del rectángulo redondeado
            const perimeter = 2 * (width + height) - 8 * rx + 2 * Math.PI * rx;

            rect.style.strokeDasharray = perimeter;
            rect.style.strokeDashoffset = '0'; // Borde visible por defecto
            card.style.setProperty('--perimeter', perimeter);
        });
    }

    initCardBorders();
    window.addEventListener('resize', initCardBorders);

    // Card clickeable
    const cards = document.querySelectorAll('.blog-post-card');
    cards.forEach(function(card) {
        card.addEventListener('click', function(e) {
            const link = card.querySelector('.entry-title a');
            if (link && !e.target.closest('a')) {
                window.location.href = link.href;
            }
        });
    });
});
</script>

<?php
get_footer();

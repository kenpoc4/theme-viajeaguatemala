<?php
/**
 * Template para las páginas de categoría
 *
 * @package Viaje a Guatemala
 */

get_header();

$category    = get_queried_object();
$cat_name    = single_cat_title( '', false );
$cat_desc    = category_description();
$total_posts = $category->count;
?>

<main id="main" class="site-main blog-category">
    <!-- Breadcrumbs -->
    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <ol class="breadcrumbs__list">
            <li class="breadcrumbs__item">
                <a href="<?php echo esc_url( get_post_type_archive_link( 'blog' ) ); ?>" class="breadcrumbs__link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    <span>Blog</span>
                </a>
            </li>
            <li class="breadcrumbs__item">
                <span class="breadcrumbs__separator">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </span>
                <span class="breadcrumbs__current"><?php echo esc_html( $cat_name ); ?></span>
            </li>
        </ol>
    </nav>

    <!-- Header de categoría -->
    <header class="blog-category__header">
        <h1 class="blog-category__title"><?php echo esc_html( $cat_name ); ?></h1>

        <div class="blog-category__meta">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                <?php printf( _n( '%d entrada', '%d entradas', $total_posts, 'vguate' ), $total_posts ); ?>
            </span>
        </div>

        <?php if ( $cat_desc ) : ?>
            <div class="blog-category__description">
                <?php echo $cat_desc; ?>
            </div>
        <?php endif; ?>
    </header>

    <!-- Lista de posts -->
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
                                Leer más &rarr;
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
            <p>No hay entradas en esta categoría todavía.</p>
        </div>

    <?php endif; ?>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // SVG Border Animation
    function initCardBorders() {
        var cards = document.querySelectorAll('.blog-post-card');

        cards.forEach(function(card) {
            var rect = card.querySelector('.card-border-rect');
            if (!rect) return;

            var width = card.offsetWidth;
            var height = card.offsetHeight;
            var rx = 12;

            var perimeter = 2 * (width + height) - 8 * rx + 2 * Math.PI * rx;

            rect.style.strokeDasharray = perimeter;
            rect.style.strokeDashoffset = '0';
            card.style.setProperty('--perimeter', perimeter);
        });
    }

    initCardBorders();
    window.addEventListener('resize', initCardBorders);

    // Card clickeable
    var cards = document.querySelectorAll('.blog-post-card');
    cards.forEach(function(card) {
        card.addEventListener('click', function(e) {
            var link = card.querySelector('.entry-title a');
            if (link && !e.target.closest('a')) {
                window.location.href = link.href;
            }
        });
    });
});
</script>

<?php
get_footer();

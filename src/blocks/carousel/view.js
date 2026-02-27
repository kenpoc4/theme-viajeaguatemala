( function () {
    'use strict';

    /* ═══════════════════════════════════════════════════════════
       Utilidades globales (compartidas si hay varios carruseles)
       ═══════════════════════════════════════════════════════════ */

    /**
     * Caché de imágenes precargadas: url → Promise<boolean>
     * Si la URL ya fue solicitada devuelve la misma Promise.
     */
    const preloadCache = new Map();

    function preloadImage( url ) {
        if ( preloadCache.has( url ) ) return preloadCache.get( url );
        const p = new Promise( ( resolve ) => {
            const img  = new window.Image();
            img.onload  = () => resolve( true );
            img.onerror = () => resolve( false );
            img.src     = url;
        } );
        preloadCache.set( url, p );
        return p;
    }

    /**
     * Muestra una imagen en un elemento <img> con transición suave.
     * 1. Precarga en memoria con preloadImage()
     * 2. Asigna src
     * 3. Usa img.decode() para decodificar antes de mostrar (evita pop-in)
     * 4. Ejecuta onReady() cuando está lista para pintar
     */
    function loadImageInto( imgEl, url, onReady ) {
        preloadImage( url ).then( () => {
            imgEl.src = url;
            if ( typeof imgEl.decode === 'function' ) {
                imgEl.decode()
                    .then( onReady )
                    .catch( onReady ); // fallback si decode falla
            } else {
                // Fallback para navegadores sin decode()
                if ( imgEl.complete && imgEl.naturalWidth ) {
                    onReady();
                } else {
                    imgEl.onload = onReady;
                }
            }
        } );
    }

    /**
     * Scroll lock: previene el scroll del body mientras el lightbox está abierto.
     * Lleva conteo para soportar múltiples carruseles en la misma página.
     */
    let scrollLocks = 0;
    function lockScroll() {
        if ( ++scrollLocks === 1 ) document.body.style.overflow = 'hidden';
    }
    function unlockScroll() {
        if ( --scrollLocks <= 0 ) {
            scrollLocks = 0;
            document.body.style.overflow = '';
        }
    }

    /**
     * Focus trap: mantiene el foco dentro del elemento mientras esté activo.
     * Devuelve una función de limpieza para remover el listener.
     */
    function createFocusTrap( el ) {
        const selector = 'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])';
        function handler( e ) {
            if ( e.key !== 'Tab' ) return;
            const nodes = Array.from( el.querySelectorAll( selector ) );
            if ( ! nodes.length ) return;
            const first = nodes[ 0 ];
            const last  = nodes[ nodes.length - 1 ];
            if ( e.shiftKey ) {
                if ( document.activeElement === first ) { e.preventDefault(); last.focus(); }
            } else {
                if ( document.activeElement === last )  { e.preventDefault(); first.focus(); }
            }
        }
        el.addEventListener( 'keydown', handler );
        return () => el.removeEventListener( 'keydown', handler );
    }


    /* ═══════════════════════════════════════════════════════════
       Inicialización por instancia de carrusel
       ═══════════════════════════════════════════════════════════ */

    function initCarouselWrapper( wrapper ) {

        /* ── Refs ─────────────────────────────────────────────── */
        const carousel      = wrapper.querySelector( '.vguate-carousel' );
        const track         = wrapper.querySelector( '.vguate-carousel__track' );
        const slides        = Array.from( wrapper.querySelectorAll( '.vguate-carousel__slide' ) );
        const prevBtn       = wrapper.querySelector( '.vguate-carousel__btn--prev' );
        const nextBtn       = wrapper.querySelector( '.vguate-carousel__btn--next' );

        const preview       = wrapper.querySelector( '.vguate-carousel__preview' );
        const previewImg    = wrapper.querySelector( '.vguate-carousel__preview-img' );
        const skeleton      = wrapper.querySelector( '.vguate-carousel__preview-skeleton' );
        const expandBtn     = wrapper.querySelector( '.vguate-carousel__preview-btn--expand' );
        const closeBtn      = wrapper.querySelector( '.vguate-carousel__preview-btn--close' );

        const lightbox      = wrapper.querySelector( '.vguate-lightbox' );
        const lbBackdrop    = wrapper.querySelector( '.vguate-lightbox__backdrop' );
        const lbContainer   = wrapper.querySelector( '.vguate-lightbox__container' );
        const lbLoader      = wrapper.querySelector( '.vguate-lightbox__loader' );
        const lbImg         = wrapper.querySelector( '.vguate-lightbox__img' );
        const lbClose       = wrapper.querySelector( '.vguate-lightbox__close' );

        // Mover el lightbox al <body> para que position:fixed funcione
        // correctamente sin verse afectado por stacking contexts del padre
        // (transform, filter, backdrop-filter crean nuevos stacking contexts).
        document.body.appendChild( lightbox );

        /* ── Estado ───────────────────────────────────────────── */
        const total      = slides.length;
        const visible    = 3;
        let   current    = 0;
        const maxIndex   = Math.max( 0, total - visible );
        const autoSelect = carousel.dataset.autoSelect === 'true';

        let activeSlide  = null;   // slide seleccionado actualmente
        let removeTrap   = null;   // cleanup del focus trap del lightbox


        /* ══════════════════════════════════════════════════════
           1. NAVEGACIÓN DEL CARRUSEL
           ══════════════════════════════════════════════════════ */

        if ( total <= visible ) {
            if ( prevBtn ) prevBtn.style.display = 'none';
            if ( nextBtn ) nextBtn.style.display = 'none';
        } else {
            function updateTrack() {
                const pct = current * ( 100 / 3 );
                track.style.transform = `translateX(-${ pct }%)`;
                prevBtn.disabled = current === 0;
                nextBtn.disabled = current >= maxIndex;
            }
            prevBtn.addEventListener( 'click', () => { if ( current > 0 ) { current--; updateTrack(); } } );
            nextBtn.addEventListener( 'click', () => { if ( current < maxIndex ) { current++; updateTrack(); } } );
            updateTrack();
        }


        /* ══════════════════════════════════════════════════════
           2. PRELOAD EN HOVER
           Cuando el cursor entra a un slide empezamos a cargar
           la imagen de preview en segundo plano. Así cuando el
           usuario haga click, la imagen ya estará en caché.
           ══════════════════════════════════════════════════════ */

        slides.forEach( ( slide ) => {
            slide.addEventListener( 'mouseenter', () => {
                const url = slide.dataset.preview;
                if ( url ) preloadImage( url );
            } );
        } );


        /* ══════════════════════════════════════════════════════
           3. PANEL DE VISTA PREVIA
           ══════════════════════════════════════════════════════ */

        function openPreview( slide ) {
            const previewUrl = slide.dataset.preview;
            const alt        = slide.dataset.alt || '';

            activeSlide = slide;

            // Marcar slide seleccionado visualmente
            slides.forEach( s => s.classList.remove( 'is-selected' ) );
            slide.classList.add( 'is-selected' );

            // Resetear estado previo
            previewImg.classList.remove( 'is-loaded' );
            previewImg.src = '';
            previewImg.alt = alt;
            skeleton.classList.add( 'is-loading' );

            // Abrir panel
            preview.classList.add( 'is-open' );
            preview.setAttribute( 'aria-hidden', 'false' );

            // Cargar imagen con transición suave
            loadImageInto( previewImg, previewUrl, () => {
                skeleton.classList.remove( 'is-loading' );
                previewImg.classList.add( 'is-loaded' );
            } );

            // Aprovechar para precargar la imagen full mientras el usuario mira la preview
            const fullUrl = slide.dataset.full;
            if ( fullUrl ) preloadImage( fullUrl );
        }

        function closePreview() {
            preview.classList.remove( 'is-open' );
            preview.setAttribute( 'aria-hidden', 'true' );
            slides.forEach( s => s.classList.remove( 'is-selected' ) );
            activeSlide = null;

            // Limpiamos src tras la animación de cierre para no ver restos
            setTimeout( () => {
                if ( ! preview.classList.contains( 'is-open' ) ) {
                    previewImg.src = '';
                    previewImg.classList.remove( 'is-loaded' );
                    skeleton.classList.remove( 'is-loading' );
                }
            }, 420 );
        }

        // Click / Enter / Space en un slide
        slides.forEach( ( slide ) => {
            function handleSlideActivation() {
                // Toggle: mismo slide cierra (solo si autoSelect está desactivado)
                if ( ! autoSelect && activeSlide === slide && preview.classList.contains( 'is-open' ) ) {
                    closePreview();
                } else {
                    openPreview( slide );
                }
            }
            slide.addEventListener( 'click', handleSlideActivation );
            slide.addEventListener( 'keydown', ( e ) => {
                if ( e.key === 'Enter' || e.key === ' ' ) {
                    e.preventDefault();
                    handleSlideActivation();
                }
            } );
        } );

        if ( closeBtn ) closeBtn.addEventListener( 'click', closePreview );

        // Auto-seleccionar primera imagen si el atributo está activo
        if ( autoSelect && slides.length > 0 ) {
            openPreview( slides[ 0 ] );
        }

        // Precargar full cuando el cursor entra al botón Expandir
        expandBtn.addEventListener( 'mouseenter', () => {
            if ( activeSlide ) preloadImage( activeSlide.dataset.full );
        } );


        /* ══════════════════════════════════════════════════════
           4. LIGHTBOX
           ══════════════════════════════════════════════════════ */

        function openLightbox() {
            if ( ! activeSlide ) return;
            const fullUrl = activeSlide.dataset.full;
            const alt     = activeSlide.dataset.alt || '';

            // Resetear
            lbImg.classList.remove( 'is-loaded' );
            lbImg.src = '';
            lbImg.alt = alt;
            lbLoader.classList.remove( 'is-hidden' );

            // Mostrar lightbox
            lightbox.classList.add( 'is-open' );
            lightbox.setAttribute( 'aria-hidden', 'false' );
            lockScroll();

            // Mover foco al botón de cierre
            requestAnimationFrame( () => lbClose.focus() );

            // Activar focus trap
            removeTrap = createFocusTrap( lightbox );

            // Cargar imagen full con transición
            loadImageInto( lbImg, fullUrl, () => {
                lbLoader.classList.add( 'is-hidden' );
                lbImg.classList.add( 'is-loaded' );
            } );
        }

        function closeLightbox() {
            lightbox.classList.remove( 'is-open' );
            lightbox.setAttribute( 'aria-hidden', 'true' );
            unlockScroll();

            if ( removeTrap ) { removeTrap(); removeTrap = null; }

            // Devolver foco al botón que abrió el lightbox
            if ( expandBtn ) expandBtn.focus();

            // Limpiar tras animación
            setTimeout( () => {
                if ( ! lightbox.classList.contains( 'is-open' ) ) {
                    lbImg.src = '';
                    lbImg.classList.remove( 'is-loaded' );
                    lbLoader.classList.remove( 'is-hidden' );
                }
            }, 320 );
        }

        expandBtn.addEventListener( 'click', openLightbox );
        lbClose.addEventListener( 'click', closeLightbox );
        lbBackdrop.addEventListener( 'click', closeLightbox );

        // Escape cierra lightbox (o preview si no hay lightbox abierto)
        document.addEventListener( 'keydown', ( e ) => {
            if ( e.key !== 'Escape' ) return;
            if ( lightbox.classList.contains( 'is-open' ) ) {
                closeLightbox();
            }
        } );

        // Swipe / clic directo en la imagen del lightbox no cierra
        // (solo el backdrop o los botones)
        lbImg.addEventListener( 'click', ( e ) => e.stopPropagation() );
        lbContainer.addEventListener( 'click', ( e ) => e.stopPropagation() );
    }


    /* ═══════════════════════════════════════════════════════════
       Inicializar todos los wrappers presentes en la página
       ═══════════════════════════════════════════════════════════ */

    document.addEventListener( 'DOMContentLoaded', () => {
        document.querySelectorAll( '.vguate-carousel-wrapper' ).forEach( initCarouselWrapper );
    } );

}() );

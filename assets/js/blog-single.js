/**
 * Blog Single - Sticky Header Detection & Progress Bar
 *
 * @package Viaje a Guatemala
 */

(function() {
    'use strict';

    const stickyHeader = document.querySelector('.blog-single__sticky-header');
    const progressFill = document.querySelector('.blog-single__progress-fill');
    const article = document.querySelector('.blog-single__article');

    if (!stickyHeader) return;

    // Crear un elemento sentinel para detectar el sticky sin parpadeo
    const sentinel = document.createElement('div');
    sentinel.className = 'sticky-sentinel';
    sentinel.style.cssText = 'position: absolute; top: 0; left: 0; right: 0; height: 1px; pointer-events: none;';
    stickyHeader.parentNode.insertBefore(sentinel, stickyHeader);

    // Usar IntersectionObserver en el sentinel
    const observer = new IntersectionObserver(
        ([entry]) => {
            stickyHeader.classList.toggle('is-sticky', !entry.isIntersecting);
        },
        {
            threshold: [0]
        }
    );

    observer.observe(sentinel);

    // Barra de progreso de lectura
    if (progressFill && article) {
        const updateProgress = () => {
            const articleRect = article.getBoundingClientRect();
            const articleTop = articleRect.top + window.scrollY;
            const articleHeight = article.offsetHeight;
            const windowHeight = window.innerHeight;
            const scrollY = window.scrollY;

            // Calcular progreso basado en la posición del artículo
            const start = articleTop - windowHeight;
            const end = articleTop + articleHeight - windowHeight;
            const current = scrollY - start;
            const total = end - start;

            let progress = (current / total) * 100;
            progress = Math.max(0, Math.min(100, progress));

            progressFill.style.width = progress + '%';
        };

        // Actualizar en scroll con throttle para mejor rendimiento
        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    updateProgress();
                    ticking = false;
                });
                ticking = true;
            }
        });

        // Actualizar al cargar
        updateProgress();
    }

    // ==========================================
    // Reproductor de Audio Expandido
    // ==========================================

    const header = document.querySelector('.site-header--lateral');
    const listenBtn = document.querySelector('.site-header__listen-btn');
    const closeBtn = document.querySelector('.site-header__audio-close');
    const audioElement = document.querySelector('.site-header__audio');

    if (listenBtn && header) {
        // Abrir reproductor
        listenBtn.addEventListener('click', () => {
            header.classList.add('is-audio-expanded');
            document.body.classList.add('audio-player-open');
            listenBtn.setAttribute('aria-expanded', 'true');
        });

        // Cerrar reproductor
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                closeAudioPlayer();
            });
        }

        // Cerrar con tecla Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && header.classList.contains('is-audio-expanded')) {
                closeAudioPlayer();
            }
        });

        function closeAudioPlayer() {
            // Agregar clase de cierre para animación de salida
            header.classList.add('is-audio-closing');

            // Pausar el audio
            if (audioElement) {
                audioElement.pause();
            }

            // Esperar a que termine la animación de los elementos internos
            setTimeout(() => {
                header.classList.remove('is-audio-expanded');
                header.classList.remove('is-audio-closing');
                document.body.classList.remove('audio-player-open');
                listenBtn.setAttribute('aria-expanded', 'false');
            }, 600);
        }
    }
})();

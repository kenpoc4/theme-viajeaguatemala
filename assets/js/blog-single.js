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

    // Toggle del reproductor de audio
    const audioToggle = document.querySelector('.blog-single__audio-toggle');
    const audioPlayer = document.getElementById('blog-audio-player');

    if (audioToggle && audioPlayer) {
        audioToggle.addEventListener('click', () => {
            const isExpanded = audioToggle.getAttribute('aria-expanded') === 'true';

            audioToggle.setAttribute('aria-expanded', !isExpanded);
            audioPlayer.hidden = isExpanded;

            // Si se está mostrando el reproductor, hacer scroll suave si es necesario
            if (!isExpanded) {
                setTimeout(() => {
                    audioPlayer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 100);
            }
        });
    }
})();

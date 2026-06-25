/**
 * Blog Archive & Category - Tabs, bordes SVG y tarjetas clicables
 *
 * Compartido por archive-blog.php y category.php.
 * El bloque de tabs solo actúa si existen tabs en la página (archive);
 * en categoría simplemente no encuentra ninguno y continúa.
 *
 * @package Viaje a Guatemala
 */

(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // ==========================================
        // Tabs (solo en el archivo del blog)
        // ==========================================
        var tabs = document.querySelectorAll('.blog-tab');
        var contents = document.querySelectorAll('.blog-tab-content');

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                var targetTab = this.getAttribute('data-tab');

                tabs.forEach(function (t) {
                    t.classList.remove('blog-tab--active');
                });
                contents.forEach(function (c) {
                    c.classList.remove('blog-tab-content--active');
                });

                this.classList.add('blog-tab--active');
                var target = document.querySelector('[data-tab-content="' + targetTab + '"]');
                if (target) {
                    target.classList.add('blog-tab-content--active');
                }
            });
        });

        // ==========================================
        // Animación de borde SVG en las tarjetas
        // ==========================================
        function initCardBorders() {
            var cards = document.querySelectorAll('.blog-post-card');

            cards.forEach(function (card) {
                var rect = card.querySelector('.card-border-rect');
                if (!rect) return;

                var width = card.offsetWidth;
                var height = card.offsetHeight;
                var rx = 12; // border-radius

                // Perímetro aproximado del rectángulo redondeado
                var perimeter = 2 * (width + height) - 8 * rx + 2 * Math.PI * rx;

                rect.style.strokeDasharray = perimeter;
                rect.style.strokeDashoffset = '0';
                card.style.setProperty('--perimeter', perimeter);
            });
        }

        initCardBorders();
        window.addEventListener('resize', initCardBorders);

        // ==========================================
        // Tarjetas clicables
        // ==========================================
        var cards = document.querySelectorAll('.blog-post-card');
        cards.forEach(function (card) {
            card.addEventListener('click', function (e) {
                var link = card.querySelector('.entry-title a');
                if (link && !e.target.closest('a')) {
                    window.location.href = link.href;
                }
            });
        });
    });
})();

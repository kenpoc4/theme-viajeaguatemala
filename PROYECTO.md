# Viaje a Guatemala - Theme WordPress

## Descripción del Proyecto

Theme personalizado para WordPress enfocado en crear un blog sobre viajes a Guatemala. El proyecto está diseñado con una arquitectura modular y escalable, con un diseño inspirado en Spotify for Artists, transmitiendo profesionalismo accesible y modernidad.

**Versión actual:** 0.1
**Autor:** Kenny Poncio
**Text Domain:** vguate
**Sitio de Referencia:** [Spotify for Artists - Get Started](https://artists.spotify.com/get-started)

---

## Estructura del Proyecto

```
viaje-a-guatemala/
├── assets/
│   ├── css/
│   │   ├── global/          # Estilos globales
│   │   │   ├── normalize.css    # Normalización de navegadores
│   │   │   ├── fonts.css        # Importación de Google Fonts
│   │   │   └── global.css       # Estilos globales y variables
│   │   ├── post-types/      # Estilos por post type
│   │   │   └── blog.css         # Estilos del blog (layout 40/60)
│   │   ├── singles/         # Estilos para singles
│   │   └── pages/           # Estilos para pages
│   └── fonts/               # Fuentes locales (preparado para futuro)
├── inc/
│   ├── post-types.php       # Gestión de custom post types
│   ├── enqueue-scripts.php  # Sistema de carga de estilos/scripts
│   └── theme-options.php    # Opciones del tema (imagen hero, etc.)
├── style.css                # Información del tema
├── functions.php            # Funciones principales del tema
├── header.php               # Header lateral con imagen de fondo
├── footer.php               # Footer del sitio
├── index.php                # Redirección al blog
├── archive-blog.php         # Template para /blog/
└── PROYECTO.md              # Este archivo
```

---

## Funcionalidades Implementadas

### 1. Sistema de Custom Post Types

**Archivo:** `inc/post-types.php`

Se creó un sistema reutilizable para registrar custom post types mediante una función helper:

#### Función Principal: `vguate_register_post_type()`

Permite crear custom post types con configuración simple mediante un array:

```php
vguate_register_post_type( array(
    'post_type'      => 'blog',
    'singular_name'  => 'Entrada de Blog',
    'plural_name'    => 'Blog',
    'menu_icon'      => 'dashicons-edit-large',
    'rewrite_slug'   => 'blog',
    'has_archive'    => true,
    'supports'       => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments', 'revisions' ),
    'taxonomies'     => array( 'category', 'post_tag' ),
    'menu_position'  => 5,
) );
```

#### Custom Post Type Actual: Blog

- **URL:** `/blog/`
- **Características:** Título, editor, imagen destacada, extracto, autor, comentarios, revisiones
- **Taxonomías:** Categorías y etiquetas nativas de WordPress
- **Gutenberg:** Habilitado

#### Para agregar más post types en el futuro:

Simplemente agregar más llamadas a `vguate_register_post_type()` en la función `vguate_register_custom_post_types()`.

---

### 2. Sistema de Theme Options

**Archivo:** `inc/theme-options.php`

Sistema de configuración del tema en el dashboard de WordPress.

#### Características:

- **Ubicación:** Apariencia > Opciones del Tema
- **Imagen Hero:** Selector de imagen para background del header lateral
- **Media Library:** Integración completa con WordPress Media Library
- **Preview:** Vista previa en tiempo real de la imagen seleccionada
- **Función Helper:** `vguate_get_hero_image()` para obtener la imagen

#### Uso:

```php
$hero_image = vguate_get_hero_image(); // Retorna URL de la imagen o false
```

**Recomendaciones de imagen:**
- Tamaño: 800x1600px o mayor
- Orientación: Vertical
- Formato: JPG, PNG o WebP

---

### 3. Sistema de Estilos

**Archivo:** `inc/enqueue-scripts.php`

Sistema inteligente que carga estilos globales y específicos automáticamente según el contexto.

#### Orden de carga:

1. **fonts.css** - Fuentes de Google Fonts (Poppins)
2. **normalize.css** - Normalización de estilos entre navegadores
3. **global.css** - Estilos globales con variables CSS
4. **Estilos específicos** (condicionales según contexto)

#### Carga automática de estilos específicos:

- **Post Types (archive):** `assets/css/post-types/{post-type}.css`
  - Ejemplo: `/blog/` → `post-types/blog.css`

- **Singles:** `assets/css/singles/{post-type}.css`
  - Ejemplo: Single de blog → `singles/blog.css`

- **Pages:** `assets/css/pages/{page-slug}.css`
  - Ejemplo: Página "contacto" → `pages/contacto.css`

---

### 4. Header Lateral con Imagen de Fondo

**Archivos:** `header.php` y `footer.php`

Header lateral sticky (40% ancho) con altura fija de 100vh y sin scroll.

#### Características del Header:

- **Layout:** 40% header lateral / 60% contenido
- **Posición:** Sticky con altura fija 100vh
- **Background:** Imagen configurable desde Theme Options
- **Overlay:** Degradado oscuro para legibilidad
- **Distribución:**
  - Branding (arriba) - fijo
  - Navegación (centro) - scrolleable si es necesario
  - Info adicional (abajo) - fija

#### Estilos Adaptativos:

**Sin imagen hero:**
- Fondo gris claro (#ededed)
- Textos oscuros
- Bordes grises

**Con imagen hero:**
- Background image completo
- Textos blancos con sombra
- Overlay degradado
- Bordes blancos semitransparentes

---

### 5. Template del Blog (Layout 40/60)

**Archivo:** `archive-blog.php`

Template con diseño lateral inspirado en Spotify.

#### Características:

- Layout lateral 40/60
- Cards con estilo Spotify:
  - Border radius generoso (16px)
  - Sombras sutiles
  - Hover effects (scale + translateY)
  - Aspect ratio 16:9 para imágenes
- Botón "Leer más" con flecha animada
- Paginación moderna con bordes redondeados

---

### 6. Redirección a Blog

**Archivo:** `index.php` y `functions.php`

Se implementó una redirección automática de la home (`/`) al blog (`/blog/`):

- **index.php:** Redirección directa con `wp_redirect()`
- **functions.php:** Hook `template_redirect` con función `vguate_redirect_home_to_blog()`
- **Tipo de redirección:** 301 (permanente)

---

## Diseño y UX/UI - Inspirado en Spotify

### Referencia de Diseño

**Sitio:** [Spotify for Artists - Get Started](https://artists.spotify.com/get-started)

El diseño replica la sensación profesional y moderna de Spotify, con énfasis en:

- ✨ Profesionalismo accesible
- ✨ Minimalismo con personalidad
- ✨ Interactividad fluida
- ✨ Jerarquía visual clara
- ✨ Espacios blancos generosos

### Paleta de Colores

**Archivo:** `assets/css/global/global.css`

Los colores coinciden con la identidad de Spotify:

```css
/* Colores principales */
--color-primary: #1ED760;      /* Verde Spotify - Principal */
--color-secondary: #ff7439;    /* Naranja - Secundario */
--color-contrast: #4100f4;     /* Púrpura Spotify - Contraste */

/* Colores de texto */
--color-text: #000000;         /* Negro - Texto principal */
--color-text-light: #666666;   /* Gris medio */
--color-text-muted: #999999;   /* Gris claro */

/* Colores de fondo */
--color-background: #ffffff;       /* Blanco - Fondo principal */
--color-background-alt: #ededed;   /* Gris claro - Fondo alternativo */

/* Colores neutros */
--color-white: #ffffff;
--color-black: #000000;
--color-border: #cccccc;
```

### Transiciones y Animaciones - Estilo Spotify

```css
/* Transiciones suaves */
--transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
--transition-base: 300ms cubic-bezier(0.4, 0, 0.2, 1);
--transition-slow: 500ms cubic-bezier(0.4, 0, 0.2, 1);

/* Sombras sutiles */
--shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
--shadow-md: 0 4px 16px rgba(0, 0, 0, 0.12);
--shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.16);
--shadow-xl: 0 16px 48px rgba(0, 0, 0, 0.2);

/* Border radius generosos */
--radius-sm: 4px;
--radius-md: 8px;
--radius-lg: 16px;
--radius-xl: 24px;
```

### Efectos Interactivos

#### Botones:
- Padding: 14px 32px
- Border radius: 24px (muy redondeados)
- Font weight: 700
- Hover: `scale(1.04)` + sombra
- Active: `scale(0.98)`
- Focus: outline púrpura de 3px

#### Cards del Blog:
- Border radius: 16px
- Sombra base: subtle
- Hover: `translateY(-4px)` + `scale(1.01)` + sombra grande
- Imagen: `scale(1.08)` + brightness en hover
- Transiciones: 300ms cubic-bezier

#### Enlaces:
- Hover: color púrpura + `translateY(-1px)`
- Focus: outline púrpura de 3px
- Active: sin transform

### Tipografía

**Fuente:** Poppins (Google Fonts)
**Archivo:** `assets/css/global/fonts.css`

#### Pesos disponibles:
- **300** - Light
- **400** - Regular (cuerpo de texto)
- **500** - Medium
- **600** - SemiBold (títulos h2, h3)
- **700** - Bold (títulos h1, botones)
- **800** - ExtraBold (títulos principales)

#### Características tipográficas:
- Títulos: font-weight 700-800
- Letter-spacing: -0.02em (más compacto)
- Line-height: 1.3 para títulos, 1.6-1.7 para texto
- Font smoothing habilitado

### Espaciados Generosos

```css
--spacing-xs: 0.5rem;   /* 8px */
--spacing-sm: 1rem;     /* 16px */
--spacing-md: 2rem;     /* 32px */
--spacing-lg: 3rem;     /* 48px */
--spacing-xl: 4rem;     /* 64px */
```

**Aplicación:**
- Header: padding-xl (64px)
- Contenido: padding-xl (64px)
- Gap entre cards: spacing-xl
- Padding interno cards: spacing-lg

### Tamaños de Fuente

```css
--font-size-sm: 0.875rem;   /* 14px */
--font-size-base: 1rem;     /* 16px */
--font-size-lg: 1.125rem;   /* 18px */
--font-size-xl: 1.5rem;     /* 24px */
--font-size-2xl: 2rem;      /* 32px */
--font-size-3xl: 2.5rem;    /* 40px */
```

### Breakpoints Responsive

```css
--breakpoint-sm: 576px;
--breakpoint-md: 768px;
--breakpoint-lg: 992px;
--breakpoint-xl: 1200px;
```

#### Comportamiento responsive:

**Mobile (< 768px):**
- Layout vertical (header arriba)
- Header: 60vh mínimo con imagen
- Botones: ancho completo
- Padding reducido pero generoso

**Tablet (769px - 1024px):**
- Layout 35/65
- Padding intermedio
- Tipografía adaptada

**Desktop (> 1024px):**
- Layout 40/60
- Header sticky con altura fija
- Espaciados completos

---

## Constantes del Theme

**Archivo:** `functions.php`

```php
VGUATE_VERSION     // Versión del tema (0.1)
VGUATE_THEME_DIR   // Directorio del tema
VGUATE_THEME_URI   // URI del tema
```

---

## Setup del Theme

**Archivo:** `functions.php` - Función `vguate_theme_setup()`

### Características habilitadas:

- ✅ Imágenes destacadas (`post-thumbnails`)
- ✅ Títulos dinámicos (`title-tag`)
- ✅ HTML5 markup (search-form, comment-form, gallery, etc.)
- ✅ Menú de navegación principal registrado

### Menús registrados:

```php
'primary' => 'Menú Principal'
```

---

## Próximos Pasos Sugeridos

### Alta prioridad:

1. **Template para single blog:**
   - `single-blog.php` - Template para entradas individuales
   - Estilos específicos en `assets/css/singles/blog.css`

2. **Navegación mejorada:**
   - Crear menú en WordPress (Apariencia > Menús)
   - Asignar a ubicación "Menú Principal"

3. **Contenido del header:**
   - Personalizar sección `.site-header__info`
   - Agregar llamados a la acción o información adicional

### Media prioridad:

4. **Optimizaciones:**
   - Tamaños de imágenes personalizados
   - Lazy loading de imágenes
   - Minificación de CSS

5. **SEO y Meta:**
   - Meta tags básicos
   - Open Graph
   - Schema.org markup

6. **Funcionalidades adicionales:**
   - Breadcrumbs
   - Búsqueda personalizada
   - Compartir en redes sociales
   - Related posts

### Baja prioridad:

7. **Post types adicionales:**
   - Destinos turísticos
   - Guías de viaje
   - Testimonios
   - Galería de fotos

8. **Funcionalidades avanzadas:**
   - Modo oscuro
   - Multiidioma
   - Formulario de contacto
   - Newsletter
   - Animaciones de scroll
   - Micro-interacciones

---

## Notas Importantes

### WordPress

- **Versión mínima requerida:** 6.7
- **PHP mínimo:** 7.2
- **Licencia:** GNU General Public License v2 or later

### Después de activar el tema:

1. **Regenerar permalinks:**
   - Ir a Ajustes > Enlaces permanentes
   - Guardar de nuevo (sin cambiar nada)
   - Esto regenera las reglas de rewrite para `/blog/`

2. **Configurar imagen hero:**
   - Ir a Apariencia > Opciones del Tema
   - Subir imagen (recomendado: 800x1600px vertical)
   - Guardar configuración

3. **Crear menú:**
   - Ir a Apariencia > Menús
   - Crear menú y asignarlo a "Menú Principal"

### Desarrollo

- Usar las constantes `VGUATE_*` para rutas
- Seguir el sistema de nomenclatura: `vguate_` como prefijo
- Mantener la estructura modular en `/inc/`
- Documentar funciones con DocBlocks
- Respetar las variables CSS para consistencia

### Accesibilidad

El tema incluye:
- Estados focus visibles (outline de 3px)
- Contraste de colores AA/AAA
- Navegación por teclado
- Semántica HTML5 correcta
- ARIA labels donde corresponde

---

## Comandos Útiles

### Git
```bash
# Ver estado
git status

# Crear commit
git add .
git commit -m "mensaje"

# Ver historial
git log --oneline
```

### WordPress

- **Regenerar permalinks:** Ajustes > Enlaces permanentes > Guardar
- **Debug:** Activar `WP_DEBUG` en `wp-config.php`
- **Limpiar cache:** Plugins de cache si existen
- **Imagen hero:** Apariencia > Opciones del Tema

---

## Recursos y Referencias

### Diseño y UX/UI

- **Referencia principal:** [Spotify for Artists - Get Started](https://artists.spotify.com/get-started)
- **Análisis de diseño:** Layout lateral, cards, botones, transiciones
- **Paleta de colores:** Verde #1ED760, Púrpura #4100f4

### WordPress

- [WordPress Theme Handbook](https://developer.wordpress.org/themes/)
- [WordPress Template Hierarchy](https://developer.wordpress.org/themes/basics/template-hierarchy/)
- [WordPress Options API](https://developer.wordpress.org/plugins/settings/options-api/)

### Tipografía y Estilos

- [Google Fonts - Poppins](https://fonts.google.com/specimen/Poppins)
- [CSS Variables Guide](https://developer.mozilla.org/en-US/docs/Web/CSS/Using_CSS_custom_properties)
- [CSS Transitions](https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Transitions)

---

## Changelog

### v0.1 - 2026-01-14

#### Agregado:
- Sistema de custom post types reutilizable
- Custom post type "blog" con URL `/blog/`
- Sistema de estilos con carga condicional
- Header lateral sticky (40%) con imagen de fondo
- Footer básico
- Theme options para configurar imagen hero
- Diseño inspirado en Spotify for Artists
- Paleta de colores Spotify (verde, púrpura)
- Fuente Poppins con múltiples pesos
- Cards con hover effects estilo Spotify
- Botones con animaciones scale
- Paginación moderna
- Transiciones y animaciones suaves
- Sistema responsive (mobile, tablet, desktop)
- Estados focus accesibles
- Redirección automática a `/blog/`

#### Estilos:
- Variables CSS para colores, espaciados, transiciones
- Normalize.css para consistencia entre navegadores
- Border radius generosos (24px en botones)
- Sombras sutiles en múltiples niveles
- Scroll behavior smooth
- Font smoothing para Poppins

---

## Contacto

**Desarrollador:** Kenny Poncio
**GitHub:** [github.com/kenpoc4](https://github.com/kenpoc4)
**Repositorio:** [github.com/kenpoc4/theme-viajeaguatemala](https://github.com/kenpoc4/theme-viajeaguatemala)

---

**Última actualización:** 2026-01-14

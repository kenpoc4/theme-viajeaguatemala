# Viaje a Guatemala - Theme WordPress

## Descripción del Proyecto

Theme personalizado para WordPress enfocado en crear un blog sobre viajes a Guatemala. El proyecto está diseñado con una arquitectura modular y escalable, preparado para futuras expansiones.

**Versión actual:** 0.1
**Autor:** Kenny Poncio
**Text Domain:** vguate

---

## Estructura del Proyecto

```
viaje-a-guatemala/
├── assets/
│   ├── css/
│   │   ├── global/          # Estilos globales
│   │   │   ├── normalize.css
│   │   │   ├── fonts.css
│   │   │   └── global.css
│   │   ├── post-types/      # Estilos por post type
│   │   ├── singles/         # Estilos para singles
│   │   └── pages/           # Estilos para pages
│   └── fonts/               # Fuentes locales (preparado para futuro)
├── inc/
│   ├── post-types.php       # Gestión de custom post types
│   └── enqueue-scripts.php  # Sistema de carga de estilos/scripts
├── style.css                # Información del tema
├── functions.php            # Funciones principales del tema
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
    'post_type'      => 'blog',           // Slug del post type
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

### 2. Sistema de Estilos

**Archivo:** `inc/enqueue-scripts.php`

Sistema inteligente que carga estilos globales y específicos automáticamente según el contexto.

#### Orden de carga:

1. **fonts.css** - Fuentes de Google Fonts
2. **normalize.css** - Normalización de estilos entre navegadores
3. **global.css** - Estilos globales del sitio
4. **Estilos específicos** (condicionales según contexto)

#### Carga automática de estilos específicos:

- **Post Types (archive):** `assets/css/post-types/{post-type}.css`
  - Ejemplo: `/blog/` → `post-types/blog.css`

- **Singles:** `assets/css/singles/{post-type}.css`
  - Ejemplo: Single de blog → `singles/blog.css`

- **Pages:** `assets/css/pages/{page-slug}.css`
  - Ejemplo: Página "contacto" → `pages/contacto.css`

#### Funciones disponibles:

- `vguate_enqueue_styles()` - Función principal
- `vguate_enqueue_post_type_style()` - Carga estilos de post types
- `vguate_enqueue_single_style()` - Carga estilos de singles
- `vguate_enqueue_page_style()` - Carga estilos de páginas

---

### 3. Redirección a Blog

**Archivo:** `index.php` y `functions.php`

Se implementó una redirección automática de la home (`/`) al blog (`/blog/`):

- **index.php:** Redirección directa con `wp_redirect()`
- **functions.php:** Hook `template_redirect` con función `vguate_redirect_home_to_blog()`
- **Tipo de redirección:** 301 (permanente)

---

### 4. Template del Blog

**Archivo:** `archive-blog.php`

Template para mostrar el listado de entradas del blog con:

- Header con título del blog
- Loop de WordPress mostrando:
  - Imagen destacada (si existe)
  - Título con enlace
  - Fecha y autor
  - Extracto
  - Botón "Leer más"
- Paginación
- Mensaje cuando no hay entradas

---

## Configuración de Estilos

### Paleta de Colores Minimalista

**Archivo:** `assets/css/global/global.css`

```css
/* Colores principales */
--color-primary: #1ED760;      /* Verde brillante - Principal */
--color-secondary: #ff7439;    /* Naranja - Secundario */
--color-contrast: #4100f4;     /* Azul/morado - Contraste */

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

### Tipografía

**Fuente:** Poppins (Google Fonts)
**Archivo:** `assets/css/global/fonts.css`

#### Pesos disponibles:
- **300** - Light
- **400** - Regular (cuerpo de texto)
- **500** - Medium
- **600** - SemiBold (títulos h2, h3)
- **700** - Bold (títulos h1)

#### Variables de fuente:
```css
--font-primary: 'Poppins', sans-serif;    /* Body y textos */
--font-secondary: 'Poppins', sans-serif;  /* Títulos */
```

### Espaciados

```css
--spacing-xs: 0.5rem;   /* 8px */
--spacing-sm: 1rem;     /* 16px */
--spacing-md: 2rem;     /* 32px */
--spacing-lg: 3rem;     /* 48px */
--spacing-xl: 4rem;     /* 64px */
```

### Tamaños de Fuente

```css
--font-size-sm: 0.875rem;   /* 14px */
--font-size-base: 1rem;     /* 16px */
--font-size-lg: 1.125rem;   /* 18px */
--font-size-xl: 1.5rem;     /* 24px */
--font-size-2xl: 2rem;      /* 32px */
--font-size-3xl: 2.5rem;    /* 40px */
```

### Breakpoints

```css
--breakpoint-sm: 576px;
--breakpoint-md: 768px;
--breakpoint-lg: 992px;
--breakpoint-xl: 1200px;
```

---

## Constantes del Theme

**Archivo:** `functions.php`

```php
VGUATE_VERSION     // Versión del tema (0.1)
VGUATE_THEME_DIR   // Directorio del tema
VGUATE_THEME_URI   // URI del tema
```

---

## Próximos Pasos Sugeridos

### Alta prioridad:

1. **Crear templates faltantes:**
   - `header.php` - Cabecera del sitio
   - `footer.php` - Pie de página
   - `single-blog.php` - Template para entradas individuales del blog

2. **Estilos específicos del blog:**
   - `assets/css/post-types/blog.css` - Estilos del listado de blog
   - `assets/css/singles/blog.css` - Estilos de entradas individuales

3. **Navegación:**
   - Menú principal
   - Registro de menús en `functions.php`

4. **Widgets:**
   - Áreas de widgets (sidebar, footer)
   - Registro de sidebars

### Media prioridad:

5. **Optimizaciones:**
   - Tamaños de imágenes personalizados
   - Soporte para imagen destacada
   - Títulos dinámicos
   - Scripts JavaScript (si es necesario)

6. **SEO y Meta:**
   - Meta tags básicos
   - Open Graph
   - Schema.org markup

7. **Funcionalidades adicionales:**
   - Breadcrumbs
   - Búsqueda personalizada
   - Compartir en redes sociales

### Baja prioridad:

8. **Post types adicionales:**
   - Destinos turísticos
   - Guías de viaje
   - Testimonios
   - Galería de fotos

9. **Funcionalidades avanzadas:**
   - Modo oscuro
   - Multiidioma
   - Formulario de contacto
   - Newsletter

---

## Notas Importantes

### WordPress

- **Versión mínima requerida:** 6.7
- **PHP mínimo:** 7.2
- **Licencia:** GNU General Public License v2 or later

### Después de activar el tema:

1. Ir a **Ajustes > Enlaces permanentes**
2. Guardar de nuevo (sin cambiar nada)
3. Esto regenera las reglas de rewrite para que funcione `/blog/`

### Desarrollo

- Usar las constantes `VGUATE_*` para rutas
- Seguir el sistema de nomenclatura: `vguate_` como prefijo
- Mantener la estructura modular en `/inc/`
- Documentar funciones con DocBlocks

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

---

## Recursos

- [WordPress Theme Handbook](https://developer.wordpress.org/themes/)
- [WordPress Template Hierarchy](https://developer.wordpress.org/themes/basics/template-hierarchy/)
- [Google Fonts - Poppins](https://fonts.google.com/specimen/Poppins)
- [CSS Variables Guide](https://developer.mozilla.org/en-US/docs/Web/CSS/Using_CSS_custom_properties)

---

## Contacto

**Desarrollador:** Kenny Poncio
**GitHub:** [github.com/kenpoc4](https://github.com/kenpoc4)
**Repositorio:** [github.com/kenpoc4/theme-viajeaguatemala](https://github.com/kenpoc4/theme-viajeaguatemala)

---

**Última actualización:** 2026-01-13

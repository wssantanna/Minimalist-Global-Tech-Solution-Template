# Theme WordPress

Tema WordPress moderno e responsivo construído com Bootstrap 5.

## Características Técnicas

- Bootstrap 5.3.2 via CDN
- Design responsivo mobile-first
- Conformidade WCAG 2.1 AA
- WordPress Customizer integrado
- HTML5 semântico
- Suporte completo a i18n (text domain: theme-wordpress)
- 2 áreas de widgets (footer_left, footer_right)
- Custom Nav Walker para Bootstrap
- Tamanhos de imagem personalizados

## Requisitos do Sistema

- WordPress 6.0+
- PHP 7.4+
- MySQL 5.7+

## Instalação

1. Upload do tema via WordPress Admin (Aparência > Temas > Adicionar novo)
2. Ativar o tema
3. Configurar via Customizer (Aparência > Personalizar)

## Configuração do Customizer

### Seção: Opções do Tema

**Cores:**
- `theme_primary_color` - Cor primária (padrão: #0d6efd)
- `theme_secondary_color` - Cor secundária (padrão: #6c757d)
- `theme_header_bg_color` - Cor de fundo do header (padrão: #212529)
- `theme_footer_bg_color` - Cor de fundo do footer (padrão: #f8f9fa)

**Layout:**
- `theme_posts_layout` - Colunas de posts: 2, 3 ou 4 (padrão: 3)

**Exibição:**
- `theme_show_post_date` - Exibir data nos cards (padrão: true)
- `theme_show_post_author` - Exibir autor nos cards (padrão: true)

**Texto:**
- `theme_footer_text` - Texto adicional do rodapé (aceita HTML, sanitizado com wp_kses_post)

## Estrutura de Arquivos

```
theme-wordpress/
├── assets/
│   ├── css/
│   │   └── main.css
│   └── js/
│       └── main.js
├── template-parts/
│   └── content-card.php
├── archive.php
├── comments.php
├── footer.php
├── functions.php
├── header.php
├── index.php
├── page.php
├── search.php
├── single.php
└── style.css
```

## Hierarchy de Templates

- **index.php** - Template fallback principal
- **archive.php** - Categorias, tags, taxonomias, archives
- **search.php** - Resultados de busca
- **single.php** - Posts individuais
- **page.php** - Páginas estáticas
- **comments.php** - Sistema de comentários

## Tamanhos de Imagem Registrados

```php
add_image_size( 'theme-card', 800, 450, true );         // 16:9
add_image_size( 'theme-featured', 1200, 675, true );    // 16:9
add_image_size( 'theme-thumb-square', 400, 400, true ); // 1:1
```

Para regenerar thumbnails: usar plugin Regenerate Thumbnails.

## Funções Principais

### functions.php

- `theme_wordpress_setup()` - Configuração do tema
- `theme_wordpress_register_menus()` - Registro de menus
- `theme_wordpress_widgets_init()` - Registro de widgets
- `theme_wordpress_enqueue_scripts()` - Enqueue de assets
- `theme_wordpress_customize_register()` - Customizer settings
- `theme_wordpress_customizer_css()` - Output de CSS customizado
- `theme_wordpress_get_grid_class()` - Helper para classes de grid
- `theme_wordpress_comment_callback()` - Callback de comentários com Bootstrap

### Bootstrap Nav Walker

`Theme_WordPress_Bootstrap_Nav_Walker` - Estende `Walker_Nav_Menu` para compatibilidade com navbar do Bootstrap 5.

Suporta:
- Classes: `nav-item`, `nav-link`, `dropdown`, `dropdown-menu`
- Atributos: `data-bs-toggle`, `aria-expanded`, `role`
- Menu hierárquico com dropdowns

## Áreas de Widgets

Registradas via `register_sidebar()`:

1. **footer_left** - Rodapé Esquerdo
2. **footer_right** - Rodapé Direito

## Acessibilidade

- Skip link (`visually-hidden-focusable`)
- Atributos ARIA em navegação
- Focus visible customizado
- Contraste de cores WCAG AA
- Suporte a `prefers-reduced-motion`
- Marcação semântica HTML5
- Alt text em imagens
- Labels em formulários

## Performance

- Bootstrap 5.3.2 via jsDelivr CDN
- CSS customizado mínimo (181 linhas)
- JavaScript customizado mínimo
- Sem dependência jQuery
- Lazy loading nativo (WordPress)
- Cache busting via `filemtime()`

## Internacionalização

**Text Domain:** `theme-wordpress`
**Diretório:** `/languages/`

Todas as strings usam funções de tradução:
- `__()`, `_e()`, `esc_html__()`, `esc_html_e()`
- `esc_attr__()`, `esc_attr_e()`
- `_n()` para pluralização

## Navegadores Suportados

Baseado no suporte do Bootstrap 5:
- Chrome (últimas 2 versões)
- Firefox (últimas 2 versões)
- Safari (últimas 2 versões)
- Edge (últimas 2 versões)
- iOS Safari (últimas 2 versões)
- Chrome Android (últimas 2 versões)

## Estilos de Impressão

Media query `@media print` incluída no main.css para otimização de impressão.

## Versionamento

Usa Semantic Versioning (semver): **1.0.0**

Atualizar em `style.css` header e usar `wp_get_theme()->get('Version')` para cache busting.

## Dependências Externas

- Bootstrap CSS: `https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css`
- Bootstrap JS: `https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js`

## Hooks do WordPress Utilizados

**Actions:**
- `after_setup_theme`
- `widgets_init`
- `wp_enqueue_scripts`
- `customize_register`
- `wp_head`

**Filters:**
- `image_size_names_choose`
- `nav_menu_css_class`
- `nav_menu_link_attributes`

## Desenvolvimento

Desenvolvido seguindo:
- WordPress Coding Standards
- Theme Review Guidelines
- Accessibility Handbook

## Licença

GPLv2 ou posterior

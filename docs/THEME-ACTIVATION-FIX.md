# Theme Activation Fix

## ✅ Problema Resolvido: Deprecated Function Errors

### Erro Original

Ao ativar o tema, você via:

```
Deprecated: A função theme_wordpress_setup está obsoleta desde a versão 2.0.0!
Deprecated: A função theme_wordpress_register_menus está obsoleta desde a versão 2.0.0!
Warning: Cannot modify header information - headers already sent...
```

### Causa do Problema

O código antigo em `functions.php` **definia as funções deprecadas sempre**, mesmo quando o plugin estava ativo. Apenas definir a função já executava `_deprecated_function()`, causando warnings que impediam o redirect (headers already sent).

**Código Problemático** (antes):

```php
// ❌ ERRADO: Função é SEMPRE definida
function theme_wordpress_setup() {
    _deprecated_function(__FUNCTION__, '2.0.0', '...');

    if (!defined('THEME_CORE_VERSION')) {
        // setup code
    }
}

// Hook só registrado se plugin não estiver ativo
if (!defined('THEME_CORE_VERSION')) {
    add_action('after_setup_theme', 'theme_wordpress_setup');
}
```

### Solução Aplicada

Movemos **toda a definição da função** para dentro do bloco condicional:

**Código Corrigido** (agora):

```php
// ✅ CORRETO: Função só existe se plugin NÃO estiver ativo
if (!defined('THEME_CORE_VERSION')) {
    function theme_wordpress_setup() {
        // setup code - SEM _deprecated_function()
    }
    add_action('after_setup_theme', 'theme_wordpress_setup');
}
```

### O que mudou em `packages/theme/functions.php`

**Linhas 90-129** - Seção "LEGACY FALLBACK":

```php
/**
 * Legacy theme setup - only used if Theme Core Features plugin is not active
 * @deprecated 2.0.0 Use Theme Core Features plugin instead
 */
if ( ! defined( 'THEME_CORE_VERSION' ) ) {
	/**
	 * Theme setup function (legacy fallback)
	 */
	function theme_wordpress_setup() {
		load_theme_textdomain( 'theme-wordpress', get_template_directory() . '/languages' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		// ... mais configurações
	}
	add_action( 'after_setup_theme', 'theme_wordpress_setup' );

	/**
	 * Register navigation menus (legacy fallback)
	 */
	function theme_wordpress_register_menus() {
		register_nav_menus( array(
			'primary_menu' => __( 'Menu Principal', 'theme-wordpress' ),
		) );
	}
	add_action( 'after_setup_theme', 'theme_wordpress_register_menus' );
}
```

### Por que isso funciona?

1. **Plugin ATIVO** (`THEME_CORE_VERSION` definida):
   - Funções `theme_wordpress_setup()` e `theme_wordpress_register_menus()` **NÃO são definidas**
   - Nenhum warning de deprecated
   - Plugin cuida de tudo via `ThemeSetupHook`

2. **Plugin INATIVO** (`THEME_CORE_VERSION` não definida):
   - Funções são definidas e registradas
   - Tema funciona de forma independente (modo fallback)
   - Útil para desenvolvimento sem plugin

### Testando a Correção

```bash
# 1. Reiniciar WordPress
docker restart wordpress_app

# 2. Aguardar 5 segundos
sleep 5

# 3. Acessar WordPress
open http://localhost:8080/wp-admin
```

**Você deve conseguir ativar o tema sem erros agora!**

### Como Ativar o Tema

1. Acesse http://localhost:8080/wp-admin
2. Vá em **Aparência → Temas**
3. Clique em **Ativar** no tema "Theme Globaltech"
4. ✅ **Nenhum erro deve aparecer!**

### Próximos Passos

Após ativar o tema:

1. **Ativar o Plugin**:
   - Vá em **Plugins**
   - Ative **"Theme Core Features"**

2. **Verificar o Customizer**:
   - Vá em **Aparência → Personalizar**
   - Você deve ver:
     - Colors (Primary, Secondary, Background, Text)
     - Typography (Font Family)
     - Layout (Mode, Columns, Sidebar)

3. **Testar Live Preview**:
   - Mude as cores no customizer
   - Observe as mudanças em tempo real
   - Clique em **Publicar**

### Cenários de Uso

#### Cenário 1: Plugin Ativo (Produção)

```
Plugin: ✅ Theme Core Features (v2.0.0) ATIVO
Tema:   ✅ Theme Globaltech (v1.0.0) ATIVO

Resultado:
- Plugin cuida de: theme setup, customizer, dynamic CSS
- Tema cuida de: templates, template parts, theme helpers
- ✅ NENHUM WARNING
```

#### Cenário 2: Plugin Inativo (Fallback)

```
Plugin: ❌ Theme Core Features INATIVO
Tema:   ✅ Theme Globaltech (v1.0.0) ATIVO

Resultado:
- Tema funciona sozinho com funções legacy
- Customizer NÃO disponível
- Funcionalidade básica mantida
```

### Arquivos Modificados

- ✅ [packages/theme/functions.php](../packages/theme/functions.php) - Linhas 90-129

### Commits Relacionados

Quando commitar, use:

```bash
git add packages/theme/functions.php
git commit -m "fix: remove deprecated function warnings when plugin is active

- Move function definitions inside conditional block
- Functions only exist if THEME_CORE_VERSION is not defined
- Prevents _deprecated_function() from running when plugin is active
- Fixes 'headers already sent' error during theme activation

Fixes: Theme activation errors with plugin active"
```

---

## Referências

- [WordPress _deprecated_function()](https://developer.wordpress.org/reference/functions/_deprecated_function/)
- [PHP Conditional Function Definitions](https://www.php.net/manual/en/functions.user-defined.php)
- [WordPress Theme Functions File](https://developer.wordpress.org/themes/basics/theme-functions/)

---

**Status**: ✅ Resolvido
**Data**: 2026-01-12
**Versão**: Theme v1.0.0 + Plugin v2.0.0

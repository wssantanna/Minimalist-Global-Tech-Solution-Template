# Plugin Activation Fix

## ✅ Problema Resolvido: Fatal Error - Unexpected Token "use" + Missing Composer Autoloader

### Erros Originais

Ao tentar ativar o plugin "Theme Core Features", você via:

**Erro 1:**
```
Não foi possível ativar o plugin porque ele gerou um erro fatal.

PHP Parse error: syntax error, unexpected token "use" in /var/www/html/wp-content/plugins/theme-core-features/theme-core-features.php on line 68
```

**Erro 2 (após corrigir erro 1):**
```
Não foi possível ativar o plugin porque ele gerou um erro fatal.

PHP Fatal error: Uncaught Error: Class "ThemeCore\Infrastructure\WordPress\Repository\WPThemeModRepository" not found
```

### Causas dos Problemas

**Problema 1:** O código tinha declarações `use` **dentro de funções** em vez de no **topo do arquivo**.

**Problema 2:** Duas causas:
1. ❌ O Composer não foi executado, então o autoloader não existia
2. ❌ Os namespaces estavam errados (`Infrastructure\WordPress\` em vez de `Infrastructure\Adapter\`)

Em PHP, declarações `use` para importar namespaces devem SEMPRE estar:
- ✅ No topo do arquivo (depois do `namespace`)
- ❌ NUNCA dentro de funções

**Código Problemático** (antes):

```php
namespace ThemeCore;

// ... outras definições ...

function createContainer(): array
{
    use ThemeCore\Application\UseCase\GenerateDynamicCSSUseCase;  // ❌ ERRADO!
    use ThemeCore\Application\UseCase\GetThemeConfigUseCase;       // ❌ ERRADO!
    // ...

    $repository = new WPThemeModRepository();
    // ...
}

function registerHooks(array $container): void
{
    use ThemeCore\Presentation\Hook\CustomizerHook;  // ❌ ERRADO!
    use ThemeCore\Presentation\Hook\ThemeSetupHook;  // ❌ ERRADO!
    // ...

    $registry = new HookRegistry();
    // ...
}
```

### Solução Aplicada

Movemos **todas as declarações `use`** para o topo do arquivo, logo após o `namespace`:

**Código Corrigido** (agora):

```php
<?php
declare(strict_types=1);

namespace ThemeCore;

// ✅ CORRETO: Use statements no topo do arquivo
use ThemeCore\Application\UseCase\GenerateDynamicCSSUseCase;
use ThemeCore\Application\UseCase\GetThemeConfigUseCase;
use ThemeCore\Application\UseCase\UpdateThemeConfigUseCase;
use ThemeCore\Infrastructure\WordPress\Repository\WPThemeModRepository;
use ThemeCore\Infrastructure\WordPress\Service\WPCSSGenerator;
use ThemeCore\Infrastructure\WordPress\Service\WPTransientCache;
use ThemeCore\Presentation\Customizer\CustomizerController;
use ThemeCore\Presentation\Hook\AssetsHook;
use ThemeCore\Presentation\Hook\CustomizerHook;
use ThemeCore\Presentation\Hook\DynamicCSSHook;
use ThemeCore\Presentation\Hook\HookRegistry;
use ThemeCore\Presentation\Hook\ThemeSetupHook;

if (!defined('ABSPATH')) {
    exit;
}

// ... resto do código ...

function createContainer(): array
{
    // Agora pode usar as classes diretamente
    $repository = new WPThemeModRepository();
    $cssGenerator = new WPCSSGenerator();
    // ...
}

function registerHooks(array $container): void
{
    // Agora pode usar as classes diretamente
    $registry = new HookRegistry();
    $registry->add(new ThemeSetupHook());
    // ...
}
```

### O que mudou em `packages/plugin/theme-core-features.php`

**Linhas 24-35** - Todas as declarações `use` agora estão no topo com namespaces corretos:

```php
// Use statements must be at the top of the file
use ThemeCore\Application\UseCase\GenerateDynamicCSSUseCase;
use ThemeCore\Application\UseCase\GetThemeConfigUseCase;
use ThemeCore\Application\UseCase\UpdateThemeConfigUseCase;
use ThemeCore\Infrastructure\Adapter\WPThemeModRepository;        // ✅ Corrigido: Adapter (não WordPress)
use ThemeCore\Infrastructure\Adapter\WPCSSGenerator;              // ✅ Corrigido: Adapter (não WordPress)
use ThemeCore\Infrastructure\Adapter\WPTransientCache;            // ✅ Corrigido: Adapter (não WordPress)
use ThemeCore\Presentation\Customizer\CustomizerController;
use ThemeCore\Presentation\Hook\AssetsHook;
use ThemeCore\Presentation\Hook\CustomizerHook;
use ThemeCore\Presentation\Hook\HookRegistry;
use ThemeCore\Presentation\Hook\ThemeSetupHook;
```

**O que foi corrigido:**
- ❌ `Infrastructure\WordPress\Repository\WPThemeModRepository` → ✅ `Infrastructure\Adapter\WPThemeModRepository`
- ❌ `Infrastructure\WordPress\Service\WPCSSGenerator` → ✅ `Infrastructure\Adapter\WPCSSGenerator`
- ❌ `Infrastructure\WordPress\Service\WPTransientCache` → ✅ `Infrastructure\Adapter\WPTransientCache`

**Linha 77** - Função `createContainer()` limpa:

```php
function createContainer(): array
{
    // Infrastructure layer (sem declarações use)
    $repository = new WPThemeModRepository();
    // ...
}
```

**Linha 108** - Função `registerHooks()` limpa:

```php
function registerHooks(array $container): void
{
    // Create hook registry (sem declarações use)
    $registry = new HookRegistry();
    // ...
}
```

### Por que isso funciona?

Em PHP, declarações `use` para namespaces funcionam assim:

1. **No topo do arquivo**: Importam classes para uso em TODO o arquivo
2. **Dentro de funções**: Só funcionam em closures para "capturar" variáveis do escopo pai

**Exemplo correto de `use` em closure**:
```php
$name = "World";
$greeting = function() use ($name) {  // ✅ Correto: capturando variável
    return "Hello, $name!";
};
```

**Mas para importar namespaces, use SEMPRE no topo**:
```php
namespace MyApp;

use Some\Other\Namespace\ClassName;  // ✅ Correto

function doSomething() {
    $obj = new ClassName();  // Funciona porque use está no topo
}
```

### Testando a Correção

```bash
# 1. Instalar dependências do Composer (dentro do container)
docker exec wordpress_app bash -c "cd /var/www/html/wp-content/plugins/theme-core-features && composer install --no-dev --optimize-autoloader"

# 2. Verificar sintaxe PHP
docker exec wordpress_app php -l /var/www/html/wp-content/plugins/theme-core-features/theme-core-features.php

# Deve retornar: No syntax errors detected

# 3. Testar autoloader
docker exec wordpress_app php -r "
require_once '/var/www/html/wp-content/plugins/theme-core-features/vendor/autoload.php';
\$class = new ReflectionClass('ThemeCore\Infrastructure\Adapter\WPThemeModRepository');
echo 'Success! Class loaded: ' . \$class->getName();
"

# Deve retornar: Success! Class loaded: ThemeCore\Infrastructure\Adapter\WPThemeModRepository
```

**Agora você pode ativar o plugin sem erros!**

### Como Ativar o Plugin

1. Acesse http://localhost:8080/wp-admin
2. Vá em **Plugins**
3. Clique em **Ativar** no plugin "Theme Core Features"
4. ✅ **Nenhum erro deve aparecer!**

### Próximos Passos

Após ativar o plugin:

1. **Verificar o Customizer**:
   - Vá em **Aparência → Personalizar**
   - Você deve ver:
     - Colors (Primary, Secondary, Background, Text)
     - Typography (Font Family)
     - Layout (Mode, Columns, Sidebar)

2. **Testar Live Preview**:
   - Mude as cores no customizer
   - Observe as mudanças em tempo real
   - Clique em **Publicar**

3. **Verificar CSS Dinâmico**:
   - Inspecione a página (F12)
   - Procure por `<style id="theme-core-dynamic-css">`
   - Deve conter as cores e tipografia configuradas

### Cenários de Uso

#### Com Plugin Ativo (Produção)

```
Plugin: ✅ Theme Core Features (v2.0.0) ATIVO
Tema:   ✅ Theme Globaltech (v1.0.0) ATIVO

Resultado:
- Plugin cuida de: theme setup, customizer, dynamic CSS
- Tema cuida de: templates, template parts, theme helpers
- ✅ TUDO FUNCIONANDO
```

#### Sem Plugin (Fallback)

```
Plugin: ❌ Theme Core Features INATIVO
Tema:   ✅ Theme Globaltech (v1.0.0) ATIVO

Resultado:
- Tema funciona sozinho com funções legacy
- Customizer NÃO disponível
- Funcionalidade básica mantida
```

### Arquivos Modificados

- ✅ [packages/plugin/theme-core-features.php](../packages/plugin/theme-core-features.php) - Linhas 24-36, 77, 108

### Commits Relacionados

Quando commitar, use:

```bash
# 1. Não commitar vendor/ (já está no .gitignore)
echo "vendor/" >> packages/plugin/.gitignore

# 2. Commitar as correções
git add packages/plugin/theme-core-features.php packages/plugin/.gitignore
git commit -m "fix: correct namespaces and use statements for plugin activation

- Move all use statements from inside functions to top of file
- Fix Infrastructure namespaces: WordPress → Adapter
- PHP use statements for namespace imports must be at file level
- Run composer install to generate autoloader

Changes:
- Infrastructure\\WordPress\\Repository → Infrastructure\\Adapter
- Infrastructure\\WordPress\\Service → Infrastructure\\Adapter
- Use statements moved to lines 24-35 (top of file)

Fixes: PHP Parse error and class not found errors on plugin activation"
```

**Importante:** O diretório `vendor/` não deve ser commitado. As dependências serão instaladas via `composer install` no ambiente de produção.

---

## Referências

- [PHP Namespaces](https://www.php.net/manual/en/language.namespaces.php)
- [PHP Use Statement](https://www.php.net/manual/en/language.namespaces.importing.php)
- [PSR-12: Extended Coding Style](https://www.php-fig.org/psr/psr-12/)

---

**Status**: ✅ Resolvido
**Data**: 2026-01-12
**Versão**: Plugin v2.0.0
**PHP**: 8.1+

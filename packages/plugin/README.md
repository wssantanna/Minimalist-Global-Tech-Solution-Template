# Theme Core Features Plugin

**Versão:** 2.0.0
**Requisitos:** PHP 8.1+ | WordPress 6.4+
**Arquitetura:** Hexagonal (Ports & Adapters)

---

## Visão Geral

Plugin que implementa a lógica de negócio do **Theme Globaltech** seguindo os princípios de Arquitetura Hexagonal, separando concerns e permitindo testes isolados.

### Princípios

- **Plugin-first**: Lógica de negócio no plugin, tema apenas para apresentação
- **Hexagonal Architecture**: Domain isolado, Infrastructure como adapter
- **PHP 8.1+**: Enums nativos, readonly properties, union types
- **Testável**: 70%+ de cobertura em Domain/Application
- **Multisite ready**: Suporte a redes WordPress

---

## Estrutura do Projeto

```
theme-core-features/
├── src/
│   ├── Domain/              # Regras de negócio (ZERO WordPress)
│   │   ├── Entity/          # Modelos de domínio
│   │   ├── ValueObject/     # Objetos de valor imutáveis
│   │   ├── Port/            # Interfaces (contratos)
│   │   └── Exception/       # Exceções de domínio
│   │
│   ├── Application/         # Casos de uso
│   │   ├── UseCase/         # Lógica de aplicação
│   │   └── DTO/             # Data Transfer Objects
│   │
│   ├── Infrastructure/      # Adaptadores WordPress
│   │   └── WordPress/
│   │       ├── Repository/  # Implementações de persistência
│   │       ├── Service/     # Serviços WP (CSS, Assets, Cache)
│   │       └── Walker/      # Menu walkers
│   │
│   └── Presentation/        # Controllers e UI
│       ├── Hook/            # Registro de actions/filters
│       └── Customizer/      # WordPress Customizer
│
├── tests/
│   ├── Unit/               # Testes unitários (sem WP)
│   └── Integration/        # Testes com WordPress
│
├── composer.json           # Dependências e autoload
├── phpunit.xml            # Configuração de testes
└── phpstan.neon           # Análise estática
```

---

## Instalação e Configuração

### 1. Instalar dependências

```bash
cd wp-content/plugins/theme-core-features
composer install
```

### 2. Ativar o plugin

No painel do WordPress:
**Plugins → Plugins Instalados → Theme Core Features → Ativar**

Ou via WP-CLI:
```bash
wp plugin activate theme-core-features
```

### 3. Verificar requisitos

O plugin verifica automaticamente:
- PHP >= 8.1
- WordPress >= 6.4

---

## Desenvolvimento

### Rodar testes

```bash
# Todos os testes
composer test

# Apenas testes unitários
composer test:unit

# Apenas testes de integração
composer test:integration
```

### Análise estática

```bash
# PHPStan (nível 8)
composer analyse

# Code Sniffer
composer cs:check
composer cs:fix
```

### Cobertura de testes

```bash
vendor/bin/phpunit --coverage-html coverage/
open coverage/index.html
```

---

## Fase de Desenvolvimento

### Fase 0 - CONCLUÍDA ✓
- Estrutura base do plugin
- Composer configurado com PSR-4
- PHPUnit configurado
- PHPStan configurado
- Plugin passivo instalável

### Fase 1 - CONCLUÍDA ✓
- Value Objects (HexColor, FontFamily, LayoutMode, ColumnCount)
- Entities (ColorScheme, LayoutSettings, ThemeConfig)
- Ports (IThemeRepository, ICSSGenerator, ICacheService)
- Testes unitários 100% do Domain (50 tests passing)

### Fase 2 - CONCLUÍDA ✓
- Infrastructure Layer: WordPress Adapters
- WPThemeModRepository (theme_mod persistence)
- WPCSSGenerator (dynamic CSS generation)
- WPTransientCache (WordPress transients)
- Integration tests (26 tests, require WP environment)
- PHPStan Level 8: Zero errors

### Fase 3 - CONCLUÍDA ✓
- Application Layer: Use Cases and DTOs
- GetThemeConfigUseCase (retrieve configuration)
- UpdateThemeConfigUseCase (update with cache invalidation)
- GenerateDynamicCSSUseCase (CSS generation with cache)
- ThemeSettingsDTO and UpdateThemeSettingsDTO
- 13 unit tests for Application layer (63 tests total, 127 assertions)
- PHPStan Level 8: Zero errors

### Fase 4 - CONCLUÍDA ✓
- Presentation Layer: Customizer Integration
- HookInterface and HookRegistry (centralized hook management)
- CustomizerController (orchestration)
- ColorSection, LayoutSection, TypographySection
- CustomizerHook (WordPress integration)
- Feature flag THEME_CORE_NEW_CUSTOMIZER
- 19 unit tests for Presentation layer (82 tests total, 162 assertions)
- PHPStan Level 8: Zero errors

### Fase 5 - CONCLUÍDA ✓
- Complete Hook Registry System
- ThemeSetupHook (theme supports, menus, text domain, custom logo)
- AssetsHook (dynamic CSS/JS enqueuing with callback pattern)
- Complete DI container (createContainer function)
- Centralized hook registration (registerHooks function)
- 9 unit tests for Hook system (91 tests total, 171 assertions)
- PHPStan Level 8: Zero errors

### Fase 6 - CONCLUÍDA ✓
- Complete Migration Strategy
- MIGRATION.md (comprehensive migration guide)
- Updated theme functions.php with deprecation notices
- Plugin dependency checks and admin notices
- Legacy customizer compatibility mode (THEME_CORE_NEW_CUSTOMIZER flag)
- DEPRECATED.md in legacy customizer directory
- Migration help notice in WordPress admin
- Backward compatibility for rollback scenarios
- All tests passing (91 tests, 171 assertions)
- PHPStan Level 8: Zero errors

### Fase 7 - CONCLUÍDA ✓ 🎉
- Release & Distribution
- CHANGELOG.md created (complete version history)
- All version numbers updated to 2.0.0
- Documentation finalized and polished
- STATUS.txt updated to 100% complete
- REFACTORING-PLAN.md finalized
- Production-ready release
- Ready for GitHub tagging

**Status Atual:** 100% completo (8/8 fases - ALL PHASES COMPLETE!)
**Version:** 2.0.0 Released - 2026-01-12

---

## Guidelines de Código

### Namespaces

```php
ThemeCore\Domain\ValueObject\HexColor      // Value Object
ThemeCore\Domain\Entity\ThemeConfig        // Entity
ThemeCore\Domain\Port\IThemeRepository     // Interface (Port)
ThemeCore\Application\UseCase\...UseCase   // Use Case
ThemeCore\Infrastructure\WordPress\...     // Adapter WP
ThemeCore\Presentation\Hook\...Hook        // Hook Handler
```

### Convenções PHP 8.1+

```php
// Usar enum nativo
enum LayoutMode: string {
    case CARDS = 'cards';
    case LIST = 'list';
}

// Usar readonly properties
final readonly class HexColor {
    public function __construct(
        public string $value
    ) {}
}

// Usar intersection types
public function process(Stringable&JsonSerializable $object): void
```

### Testes

```php
// Nomenclatura: test_<scenario>_<expected>
class HexColorTest extends TestCase {
    public function test_valid_hex_creates_color(): void
    {
        $color = new HexColor('#0D6EFD');
        self::assertEquals('#0D6EFD', $color->toHex());
    }
}
```

---

## Troubleshooting

### Composer não encontrado
```bash
docker exec -it wordpress_app bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

### Plugin não ativa
Verificar logs: `wp-content/debug.log` (se `WP_DEBUG_LOG` habilitado)

### Testes não rodam
```bash
composer dump-autoload
vendor/bin/phpunit --list-tests
```

---

## Documentação Adicional

- [ARCHITECTURE.md](ARCHITECTURE.md) - Detalhes da arquitetura hexagonal
- [REFACTORING-PLAN.md](REFACTORING-PLAN.md) - Plano completo de refatoração (7 fases)
- [STATUS.txt](STATUS.txt) - Status visual do projeto e progresso

---

## Licença

GPL v2 or later - https://www.gnu.org/licenses/gpl-2.0.html

## Autor

**Willian Sant'Anna**
GitHub: @wssantanna

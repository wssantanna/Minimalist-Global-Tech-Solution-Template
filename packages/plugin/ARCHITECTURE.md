# Arquitetura Hexagonal - Theme Core Features

## Visão Geral

Este plugin implementa **Arquitetura Hexagonal** (também conhecida como Ports & Adapters), permitindo que a lógica de negócio seja independente do WordPress.

### Princípios Fundamentais

1. **Inversão de Dependência**: Domain não conhece Infrastructure
2. **Testabilidade**: Lógica de negócio 100% testável sem WordPress
3. **Substituibilidade**: Adapters podem ser trocados sem afetar Domain
4. **Separação de Concerns**: Cada camada tem responsabilidade clara

---

## Camadas da Arquitetura

```
┌─────────────────────────────────────────────────────────────────┐
│                      PRESENTATION LAYER                          │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐          │
│  │ Customizer   │  │ Hooks        │  │ Template     │          │
│  │ Controller   │  │ Registry     │  │ Functions    │          │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘          │
└─────────┼──────────────────┼──────────────────┼─────────────────┘
          │                  │                  │
          └──────────────────┼──────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                      APPLICATION LAYER                           │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │                    USE CASES                              │   │
│  │  • GetThemeSettingsUseCase                               │   │
│  │  • UpdateThemeSettingsUseCase                            │   │
│  │  • GenerateDynamicCSSUseCase                             │   │
│  └──────────────────────────────────────────────────────────┘   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │                      DTOs                                 │   │
│  │  • ThemeSettingsDTO                                      │   │
│  │  • ColorSettingsDTO                                      │   │
│  └──────────────────────────────────────────────────────────┘   │
└─────────────────────────────┬───────────────────────────────────┘
                              │ (usa Ports)
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                        DOMAIN LAYER                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐          │
│  │ VALUE        │  │ ENTITIES     │  │ PORTS        │          │
│  │ OBJECTS      │  │              │  │ (Interfaces) │          │
│  │              │  │              │  │              │          │
│  │ • HexColor   │  │ • ThemeConfig│  │ • IThemeRepo │          │
│  │ • FontFamily │  │ • ColorScheme│  │ • ICSSGen    │          │
│  │ • LayoutMode │  │ • LayoutSet  │  │ • ICacheSvc  │          │
│  └──────────────┘  └──────────────┘  └──────────────┘          │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │                    EXCEPTIONS                             │   │
│  │  • InvalidColorException                                 │   │
│  │  • ThemeConfigException                                  │   │
│  └──────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
                              ▲ (implementa Ports)
                              │
┌─────────────────────────────────────────────────────────────────┐
│                    INFRASTRUCTURE LAYER                          │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐          │
│  │ WordPress    │  │ Cache        │  │ External     │          │
│  │ Adapters     │  │ Service      │  │ Services     │          │
│  │              │  │              │  │              │          │
│  │ • WPTheme    │  │ • WPTransient│  │ • GoogleFont │          │
│  │   ModRepo    │  │   Cache      │  │   Adapter    │          │
│  │ • WPCSSGen   │  │              │  │              │          │
│  │ • WPAssetMgr │  │              │  │              │          │
│  └──────────────┘  └──────────────┘  └──────────────┘          │
└─────────────────────────────────────────────────────────────────┘
```

---

## Fluxo de Dados - Exemplo Completo

### Cenário: Usuário muda cor primária no Customizer

**1. User Action (Browser)**
```
Usuário altera cor no color picker
   ↓
JavaScript envia valor via AJAX
```

**2. Presentation Layer**
```php
// CustomizerController recebe input
CustomizerController::register($wp_customize)
   ↓
ColorSection::sanitize('#0D6EFD')  // Validação inicial
   ↓
Cria ColorSettingsDTO
```

**3. Application Layer**
```php
UpdateThemeSettingsUseCase::execute(ColorSettingsDTO)
   ↓
Valida DTO
   ↓
Converte para Domain Entity (HexColor)
   ↓
Chama Port: IThemeRepository->saveConfig()
```

**4. Domain Layer**
```php
new HexColor('#0D6EFD')
   ↓
Valida formato regex
   ↓
Retorna Value Object imutável
```

**5. Infrastructure Layer**
```php
WPThemeModRepository->saveConfig(ThemeConfig)
   ↓
set_theme_mod('theme_primary_color', '#0D6EFD')
   ↓
ICacheService->delete('dynamic_css')  // Invalida cache
```

**6. Frontend Render (próximo request)**
```php
Hook: wp_head
   ↓
EnqueueAssetsHook::enqueueStyles()
   ↓
GenerateDynamicCSSUseCase::execute()
   ↓
   Tenta cache → Miss
   ↓
   IThemeRepository->getConfig()
   ↓
   ICSSGenerator->generate(ThemeConfig)
   ↓
   ICacheService->set('dynamic_css', $css, 3600)
   ↓
wp_add_inline_style('bootstrap', $css)
```

---

## Responsabilidades das Camadas

### Domain Layer (Core Business)

**O QUE FAZ:**
- Define regras de negócio
- Valida invariantes (ex: HexColor deve ser formato válido)
- Modela conceitos do domínio (cores, layout, tipografia)
- Define contratos (Ports) que outros precisam implementar

**O QUE NÃO FAZ:**
- Não acessa banco de dados
- Não chama APIs externas
- Não conhece WordPress
- Não tem lógica de apresentação

**EXEMPLO:**
```php
// Domain/ValueObject/HexColor.php
final readonly class HexColor
{
    public function __construct(public string $value)
    {
        if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $value)) {
            throw new InvalidColorException($value);
        }
    }

    // Lógica de conversão RGB é do domínio
    public function toRgb(): array { ... }
}
```

### Application Layer (Use Cases)

**O QUE FAZ:**
- Orquestra fluxos de negócio
- Coordena Domain e Infrastructure
- Transforma dados (Entity ↔ DTO)
- Aplica regras transacionais (ex: salvar + invalidar cache)

**O QUE NÃO FAZ:**
- Não tem lógica de negócio (delega para Domain)
- Não conhece detalhes de persistência (usa Ports)
- Não manipula HTTP/requests diretamente

**EXEMPLO:**
```php
// Application/UseCase/GenerateDynamicCSSUseCase.php
final readonly class GenerateDynamicCSSUseCase
{
    public function __construct(
        private IThemeRepository $repository,
        private ICSSGenerator $generator,
        private ICacheService $cache,
    ) {}

    public function execute(): string
    {
        // 1. Tenta cache
        $cached = $this->cache->get('dynamic_css');
        if ($cached !== null) {
            return $cached;
        }

        // 2. Busca config via Port
        $config = $this->repository->getConfig();

        // 3. Gera CSS via Port
        $css = $this->generator->generate($config);

        // 4. Cacheia
        $this->cache->set('dynamic_css', $css, 3600);

        return $css;
    }
}
```

### Infrastructure Layer (Adapters)

**O QUE FAZ:**
- Implementa Ports do Domain
- Adapta APIs externas (WordPress, Google Fonts, etc.)
- Persiste/recupera dados
- Lida com detalhes técnicos (transients, wpdb, filesystem)

**O QUE NÃO FAZ:**
- Não tem lógica de negócio
- Não valida invariantes (Domain faz isso)

**EXEMPLO:**
```php
// Infrastructure/WordPress/Repository/WPThemeModRepository.php
final class WPThemeModRepository implements IThemeRepository
{
    public function getConfig(): ThemeConfig
    {
        // Acessa WordPress API
        $primaryHex = get_theme_mod('theme_primary_color', '#0d6efd');

        // Constrói Domain Entity
        return new ThemeConfig(
            colors: new ColorScheme(
                primary: new HexColor($primaryHex),
                // ...
            )
        );
    }
}
```

### Presentation Layer (Controllers/UI)

**O QUE FAZ:**
- Recebe input do usuário (HTTP, Customizer, CLI)
- Valida formato de entrada (sanitização básica)
- Chama Use Cases
- Formata resposta (JSON, HTML, etc.)

**O QUE NÃO FAZ:**
- Não tem lógica de negócio
- Não acessa Infrastructure diretamente (usa Use Cases)

**EXEMPLO:**
```php
// Presentation/Customizer/Section/ColorSection.php
final class ColorSection
{
    public function register(\WP_Customize_Manager $wp_customize): void
    {
        $wp_customize->add_setting('theme_primary_color', [
            'sanitize_callback' => [$this, 'sanitize'],
            'transport' => 'postMessage',
        ]);
    }

    public function sanitize(string $input): string
    {
        try {
            // Usa Domain para validar
            $color = new HexColor($input);
            return $color->toHex();
        } catch (InvalidColorException) {
            return '#0d6efd'; // fallback
        }
    }
}
```

---

## Regras de Dependência

### Direção das Dependências (CRÍTICO)

```
Presentation  →  Application  →  Domain  ←  Infrastructure
     ↓                ↓             ↑              ↑
  (depende)      (depende)    (não depende)  (implementa)
```

**REGRAS:**
1. Domain não importa nada de outras camadas
2. Application importa apenas Domain
3. Infrastructure implementa contratos do Domain
4. Presentation orquestra Application

**EXEMPLO DE VIOLAÇÃO (NÃO FAZER):**
```php
// Domain/Entity/ThemeConfig.php
class ThemeConfig
{
    public function save(): void
    {
        // ❌ ERRADO: Domain não pode chamar WordPress
        set_theme_mod('theme_primary_color', $this->color);
    }
}
```

**CORRETO:**
```php
// Domain/Entity/ThemeConfig.php
class ThemeConfig
{
    // ✅ Apenas state, sem lógica de persistência
    public readonly HexColor $primaryColor;
}

// Application/UseCase/UpdateThemeSettingsUseCase.php
class UpdateThemeSettingsUseCase
{
    public function execute(ThemeSettingsDTO $dto): void
    {
        $config = ThemeConfig::fromDTO($dto);

        // ✅ Application chama Port (abstração)
        $this->repository->saveConfig($config);
    }
}

// Infrastructure/WordPress/Repository/WPThemeModRepository.php
class WPThemeModRepository implements IThemeRepository
{
    public function saveConfig(ThemeConfig $config): void
    {
        // ✅ Infrastructure implementa detalhe WordPress
        set_theme_mod('theme_primary_color', $config->primaryColor->toHex());
    }
}
```

---

## Dependency Injection

### Container Simples (Fase 4+)

```php
// DependencyInjection/Container.php
final class Container
{
    private static ?self $instance = null;
    private array $services = [];

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
            self::$instance->register();
        }
        return self::$instance;
    }

    private function register(): void
    {
        // Infrastructure
        $this->services[IThemeRepository::class] = new WPThemeModRepository();
        $this->services[ICSSGenerator::class] = new WPCSSGenerator();
        $this->services[ICacheService::class] = new WPTransientCache();

        // Application
        $this->services[GetThemeSettingsUseCase::class] = new GetThemeSettingsUseCase(
            $this->get(IThemeRepository::class)
        );

        $this->services[GenerateDynamicCSSUseCase::class] = new GenerateDynamicCSSUseCase(
            $this->get(IThemeRepository::class),
            $this->get(ICSSGenerator::class),
            $this->get(ICacheService::class)
        );

        // Presentation
        $this->services[CustomizerController::class] = new CustomizerController(
            $this->get(GetThemeSettingsUseCase::class),
            $this->get(UpdateThemeSettingsUseCase::class)
        );
    }

    public function get(string $class): object
    {
        if (!isset($this->services[$class])) {
            throw new \RuntimeException("Service not found: {$class}");
        }
        return $this->services[$class];
    }
}
```

**Uso no Plugin:**
```php
// theme-core-features.php
add_action('customize_register', function($wp_customize) {
    $container = Container::getInstance();
    $controller = $container->get(CustomizerController::class);
    $controller->register($wp_customize);
});
```

---

## Testabilidade

### Testes Unitários (Domain/Application)

**SEM WordPress, SEM banco de dados**

```php
// tests/Unit/Domain/ValueObject/HexColorTest.php
final class HexColorTest extends TestCase
{
    public function test_valid_hex_creates_color(): void
    {
        $color = new HexColor('#0D6EFD');

        self::assertEquals('#0D6EFD', $color->toHex());
    }

    public function test_invalid_hex_throws_exception(): void
    {
        $this->expectException(InvalidColorException::class);

        new HexColor('invalid');
    }
}
```

```php
// tests/Unit/Application/UseCase/GenerateDynamicCSSUseCaseTest.php
final class GenerateDynamicCSSUseCaseTest extends TestCase
{
    public function test_returns_cached_css_when_available(): void
    {
        // Arrange: Mock dependencies
        $mockCache = $this->createMock(ICacheService::class);
        $mockCache->method('get')->willReturn('cached-css');

        $mockRepo = $this->createMock(IThemeRepository::class);
        $mockGenerator = $this->createMock(ICSSGenerator::class);

        $useCase = new GenerateDynamicCSSUseCase($mockRepo, $mockGenerator, $mockCache);

        // Act
        $result = $useCase->execute();

        // Assert
        self::assertEquals('cached-css', $result);

        // Verifica que não chamou gerador (cache hit)
        $mockGenerator->expects(self::never())->method('generate');
    }
}
```

### Testes de Integração (Infrastructure)

**COM WordPress (test suite)**

```php
// tests/Integration/Infrastructure/WPThemeModRepositoryTest.php
final class WPThemeModRepositoryTest extends WP_UnitTestCase
{
    private WPThemeModRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new WPThemeModRepository();
    }

    public function test_saves_and_retrieves_config(): void
    {
        // Arrange
        $config = new ThemeConfig(
            colors: new ColorScheme(
                primary: new HexColor('#FF0000'),
                headerBackground: new HexColor('#000000'),
                footerBackground: new HexColor('#FFFFFF'),
            ),
            layout: LayoutSettings::default(),
            typography: TypographySettings::default(),
        );

        // Act
        $this->repository->saveConfig($config);
        $retrieved = $this->repository->getConfig();

        // Assert
        self::assertTrue($config->colors->primary->equals($retrieved->colors->primary));
        self::assertEquals('#FF0000', get_theme_mod('theme_primary_color'));
    }
}
```

---

## Vantagens desta Arquitetura

### 1. Testabilidade
- Domain testável sem WordPress (rápido, confiável)
- Mocks fáceis via interfaces
- Testes isolados por camada

### 2. Manutenibilidade
- Responsabilidades claras
- Mudanças localizadas
- Fácil encontrar código

### 3. Substituibilidade
- Trocar WordPress por outro CMS? Só muda Infrastructure
- Trocar cache de transients para Redis? Só muda WPTransientCache
- Trocar Customizer por Gutenberg? Só muda Presentation

### 4. Performance
- Cache em Application (independente de implementação)
- Geração de CSS otimizada (uma vez, cacheia)
- Lazy loading de configurações

### 5. Evolutibilidade
- Adicionar REST API? Nova camada Presentation
- Adicionar CLI? Novo controller
- Adicionar export/import? Novo Use Case

---

## Comparação: Antes vs Depois

### Antes (Tema Monolítico)

```php
// functions.php
function theme_wordpress_customizer_css() {
    $primary = get_theme_mod('theme_primary_color', '#0d6efd');
    $header_bg = get_theme_mod('theme_header_bg_color', '#212529');

    // 150 linhas de CSS inline...
    $css = ':root { --primary: ' . $primary . '; }';

    wp_add_inline_style('bootstrap', $css);
}
add_action('wp_enqueue_scripts', 'theme_wordpress_customizer_css');
```

**Problemas:**
- Impossível testar sem WordPress
- Lógica misturada (get_theme_mod + geração CSS + enqueue)
- Difícil cachear
- Acoplamento total

### Depois (Hexagonal)

```php
// Domain
final readonly class HexColor { ... }
final readonly class ThemeConfig { ... }

// Application
final readonly class GenerateDynamicCSSUseCase {
    public function execute(): string {
        $cached = $this->cache->get('dynamic_css');
        if ($cached) return $cached;

        $config = $this->repository->getConfig();
        $css = $this->generator->generate($config);

        $this->cache->set('dynamic_css', $css);
        return $css;
    }
}

// Infrastructure
final class WPCSSGenerator implements ICSSGenerator {
    public function generate(ThemeConfig $config): string {
        return ":root { --primary: {$config->colors->primary->toHex()}; }";
    }
}

// Presentation
final class EnqueueAssetsHook implements HookInterface {
    public function register(): void {
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue(): void {
        $css = $this->generateCssUseCase->execute();
        wp_add_inline_style('bootstrap', $css);
    }
}
```

**Benefícios:**
- HexColor testável isoladamente
- Use Case testável com mocks
- Cache transparente
- Trocar WordPress → só muda WPCSSGenerator

---

## Referências

- [Alistair Cockburn - Hexagonal Architecture](https://alistair.cockburn.us/hexagonal-architecture/)
- [Netflix Tech Blog - Ports & Adapters](https://netflixtechblog.com/ready-for-changes-with-hexagonal-architecture-b315ec967749)
- [PHP-DI - Dependency Injection](https://php-di.org/)
- [PSR-4 Autoloading](https://www.php-fig.org/psr/psr-4/)

# Changelog

All notable changes to the Theme Core Features plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [2.0.0] - 2026-01-12

### 🎉 Major Release - Hexagonal Architecture Refactoring

This is a complete architectural rewrite of the theme customizer functionality, moving from a theme-based implementation to a plugin-based hexagonal architecture. This release maintains **100% backward compatibility** while providing a cleaner, more maintainable codebase.

### Added

#### Domain Layer
- **Value Objects** (8 total):
  - `Color` - Immutable color representation with validation
  - `CSSDeclaration` - Type-safe CSS property/value pairs
  - `FontFamily` - Font family with fallback support
  - `ColumnCount` - Grid column count (1-4) with validation
  - `PrimaryColor` - Primary brand color
  - `SecondaryColor` - Secondary brand color
  - `BackgroundColor` - Background color
  - `TextColor` - Text color

- **Entities** (3 total):
  - `ColorConfig` - Complete color configuration
  - `TypographyConfig` - Typography settings
  - `LayoutConfig` - Layout mode, columns, sidebar

- **Enums** (5 total):
  - `LayoutMode` - Grid, List, Masonry
  - `ColorType` - Primary, Secondary, Background, Text
  - `ThemeOption` - All theme option keys
  - `CSSProperty` - Type-safe CSS properties
  - `FontStack` - Predefined font stacks (System, Serif, Monospace)

- **Ports** (3 total):
  - `ThemeConfigRepositoryInterface` - Configuration persistence
  - `CSSGeneratorInterface` - Dynamic CSS generation
  - `CacheInterface` - Caching abstraction

#### Infrastructure Layer
- **WordPress Adapters** (3 total):
  - `WPThemeModRepository` - WordPress theme_mod adapter (get_theme_mod/set_theme_mod)
  - `WPCSSGenerator` - CSS generation using WordPress APIs
  - `WPTransientCache` - WordPress Transients adapter (get/set/delete transient)

#### Application Layer
- **Use Cases** (3 total):
  - `GetThemeConfigUseCase` - Retrieve complete theme configuration
  - `UpdateThemeConfigUseCase` - Update configuration with cache invalidation
  - `GenerateDynamicCSSUseCase` - Generate cached dynamic CSS

- **DTOs** (2 total):
  - `ThemeConfigDTO` - Data transfer object for theme configuration
  - `UpdateThemeConfigDTO` - Update request object

#### Presentation Layer
- **Customizer Controller**:
  - `CustomizerController` - WordPress Customizer integration
  - Complete section/setting/control registration
  - Live preview support

- **Customizer Sections** (3 total):
  - `ColorSection` - Color customization (Primary, Secondary, Background, Text)
  - `TypographySection` - Font family selection
  - `LayoutSection` - Layout mode, columns, sidebar

- **Hooks** (3 total):
  - `ThemeSetupHook` - Theme setup (add_theme_support, menus, text domain)
  - `AssetsHook` - Dynamic CSS/JS enqueuing with callback pattern
  - `CustomizerHook` - Customizer registration hook
  - `HookRegistry` - Centralized hook registration

#### Testing & Quality
- **91 Unit Tests** with 171 assertions
- **PHPStan Level 8** - Maximum static analysis
- **PSR-12** Code style compliance
- **70%+ Test Coverage** - Domain, Application, Presentation layers

#### Documentation
- **README.md** - Complete overview and setup guide
- **ARCHITECTURE.md** - Detailed hexagonal architecture documentation
- **REFACTORING-PLAN.md** - 7-phase implementation plan
- **MIGRATION.md** - Comprehensive migration guide for users
- **QUICKSTART.md** - 15-minute quick start guide
- **STATUS.txt** - Visual project status tracker
- **DEPRECATED.md** - Legacy code deprecation notice

#### Migration Features
- **Feature Flag**: `THEME_CORE_NEW_CUSTOMIZER` for gradual migration
- **Plugin Dependency Check**: Automatic detection in theme functions.php
- **Admin Notices**: Clear user guidance with dismissible notices
- **Backward Compatibility**: Legacy code preserved for v2.x
- **Rollback Support**: Complete rollback instructions
- **Settings Preservation**: All existing theme_mod values maintained

### Changed

#### Architecture
- **Moved** from theme-based to plugin-based architecture
- **Separated** business logic (plugin) from presentation (theme)
- **Introduced** hexagonal architecture (Ports & Adapters pattern)
- **Improved** testability with dependency injection
- **Enhanced** maintainability with clean layer separation
- **Optimized** performance with caching (WP Transients)

#### Theme Integration
- **Updated** `functions.php` with plugin dependency checks
- **Deprecated** legacy customizer functions with `_deprecated_function()`
- **Added** conditional execution (only run if plugin not active)
- **Preserved** theme-specific functions (Bootstrap walker, helpers)

#### Code Quality
- **Upgraded** PHP requirement to 8.1+ (enums, readonly, modern features)
- **Implemented** strict typing (`declare(strict_types=1)`)
- **Applied** constructor property promotion
- **Enforced** immutability (readonly properties, Value Objects)
- **Removed** WordPress dependencies from Domain layer

### Deprecated

The following theme-based files are deprecated and will be removed in v3.0.0:

- `src/inc/customizer/customizer.php` → Use `CustomizerController.php`
- `src/inc/customizer/customizer-colors.php` → Use `ColorSection.php`
- `src/inc/customizer/customizer-typography.php` → Use `TypographySection.php`
- `src/inc/customizer/customizer-layout.php` → Use `LayoutSection.php`
- `src/inc/customizer/customizer-css.php` → Use `WPCSSGenerator.php`
- `src/inc/customizer/customizer-color-helpers.php` → Move to plugin if needed
- `src/inc/customizer/customizer-preview.js` → Handled by WordPress API
- `src/inc/customizer/customizer-logo.php` → Use WordPress native `custom-logo`

The following theme functions are deprecated:

- `theme_wordpress_setup()` → Use `ThemeSetupHook`
- `theme_wordpress_register_menus()` → Use `ThemeSetupHook`
- `theme_wordpress_customize_register()` → Use `CustomizerController`
- `theme_wordpress_customizer_css()` → Use `GenerateDynamicCSSUseCase`

### Security

- **Validated** all user inputs with Value Objects
- **Sanitized** all outputs using WordPress escaping functions
- **Prevented** XSS with proper escaping (esc_attr, esc_html, esc_url)
- **Enforced** capability checks (`current_user_can()`)
- **Protected** against SQL injection (using WordPress APIs)

### Performance

- **Implemented** CSS caching via WP Transients
- **Reduced** database queries with cached configuration
- **Optimized** CSS generation (<50ms with cache)
- **Minimized** WordPress API calls

### Developer Experience

- **Composer Scripts**:
  - `composer test:unit` - Run unit tests
  - `composer analyse` - PHPStan static analysis
  - `composer cs:fix` - Auto-fix code style
  - `composer test` - Run all tests

- **Docker Support**:
  - WordPress 6.7+ with PHP 8.1+
  - MySQL 8.0
  - PHPMyAdmin
  - Complete development environment

- **Git Workflow**:
  - Feature branch: `refactor/hexagonal-architecture`
  - Conventional commits
  - Detailed commit messages

### Technical Stack

- **PHP**: 8.1+ (enums, readonly, attributes)
- **WordPress**: 6.4+ (latest APIs)
- **PHPUnit**: 10.x (unit testing)
- **PHPStan**: Level 8 (static analysis)
- **PHP CS Fixer**: PSR-12 (code style)
- **Composer**: 2.x (dependency management)

### Breaking Changes

**None.** This release maintains 100% backward compatibility.

- All existing `theme_mod` values are preserved
- Legacy customizer can still be used via feature flag
- Deprecated functions continue to work (with notices)
- Rollback is supported at any time

### Migration Path

1. **Backup** your database and theme files
2. **Activate** the "Theme Core Features" plugin
3. **Verify** customizer settings in Appearance → Customize
4. **Test** all theme functionality
5. **Optional**: Set `THEME_CORE_NEW_CUSTOMIZER` to `false` to rollback

See [MIGRATION.md](MIGRATION.md) for complete instructions.

### Upgrade Notice

**Upgrading from v1.x to v2.0.0:**

This is a major architectural update that moves customizer functionality from theme to plugin. While 100% backward compatible, we recommend:

1. Creating a full backup before updating
2. Testing in a staging environment first
3. Reading the [Migration Guide](MIGRATION.md)
4. Keeping legacy files until confident in v2.0.0

**No action required** - settings are preserved automatically.

---

## [1.x] - Legacy (Before 2026-01-11)

### Initial Implementation

Theme-based customizer implementation with direct WordPress integration.

#### Features
- Color customization (Primary color)
- Typography settings
- Layout options (Grid/List modes)
- Dynamic CSS generation
- Live preview support

#### Structure
- All code in theme `inc/customizer/` directory
- Direct WordPress API usage
- No separation of concerns
- Tightly coupled to theme

#### Limitations
- Difficult to test (tight WordPress coupling)
- Hard to maintain (mixed concerns)
- Poor code organization
- No dependency injection
- No static analysis
- Limited documentation

---

## Version Numbering

This project follows [Semantic Versioning](https://semver.org/):

- **Major** (X.0.0): Breaking changes, architectural rewrites
- **Minor** (1.X.0): New features, backward compatible
- **Patch** (1.0.X): Bug fixes, backward compatible

### Release Cycle

- **2.0.0** (2026-01-12): Hexagonal architecture refactoring ✓
- **2.1.0** (Future): Additional features (e.g., advanced layout options)
- **2.2.0** (Future): Enhanced color system (e.g., color schemes)
- **3.0.0** (2027): Remove deprecated legacy code

---

## Support

- **Issues**: [GitHub Issues](https://github.com/wssantanna/theme-globaltech/issues)
- **Documentation**: See [README.md](README.md) and [MIGRATION.md](MIGRATION.md)
- **Contact**: Willian Sant'Anna (@wssantanna)

---

## License

GPL v2 or later

---

## Contributors

- **Willian Sant'Anna** - Initial work and v2.0.0 refactoring

---

**[Unreleased]**: https://github.com/wssantanna/theme-globaltech/compare/v2.0.0...HEAD
**[2.0.0]**: https://github.com/wssantanna/theme-globaltech/compare/v1.x...v2.0.0
**[1.x]**: https://github.com/wssantanna/theme-globaltech/releases/tag/v1.x

# Theme Globaltech - Monorepo

Monorepo containing WordPress theme and plugin with hexagonal architecture.

## 📦 Project Structure

```
custom-theme/                    # Monorepo root
├── packages/
│   ├── theme/                   # WordPress Theme (theme-globaltech)
│   └── plugin/                  # WordPress Plugin (theme-core-features)
├── docker/                      # Development environment
├── docs/                        # Centralized documentation
└── README.md                    # This file
```

## 🚀 Quick Start

### Prerequisites

- Docker Desktop installed and running
- Git

### 1. Clone the repository

```bash
git clone https://github.com/wssantanna/theme-globaltech.git custom-theme
cd custom-theme
```

### 2. Start the Docker environment

```bash
cd docker
docker-compose up -d
```

### 3. Access WordPress

- **WordPress**: http://localhost:8080
- **WordPress Admin**: http://localhost:8080/wp-admin
- **PHPMyAdmin**: http://localhost:8081

### 4. Complete WordPress installation

1. Select language
2. Create admin credentials
3. Complete installation

### 5. Activate theme and plugin

1. Go to **Appearance → Themes** → Activate **"Theme Globaltech"**
2. Go to **Plugins** → Activate **"Theme Core Features"**

## 📚 Documentation

- [Docker Environment Setup](docs/DOCKER.md) - Complete Docker configuration guide
- [Theme Documentation](packages/theme/README.md) - Theme-specific documentation
- [Plugin Architecture](packages/plugin/ARCHITECTURE.md) - Hexagonal architecture details
- [Plugin Migration Guide](packages/plugin/MIGRATION.md) - Migration from v1.x to v2.0
- [Plugin Changelog](packages/plugin/CHANGELOG.md) - Version history

## 🏗️ Architecture

This project uses a **monorepo structure** with two main packages:

### Theme (`packages/theme/`)

WordPress theme providing the user interface:

- Templates (index.php, single.php, archive.php, etc.)
- Template parts (content.php, searchform.php, etc.)
- Theme-specific helpers and functions
- Bootstrap integration
- Responsive design

### Plugin (`packages/plugin/`)

Core business logic using **hexagonal architecture** (Ports & Adapters):

```
plugin/
├── src/
│   ├── Domain/          # Business rules (Value Objects, Entities, Enums)
│   ├── Application/     # Use Cases and DTOs
│   ├── Infrastructure/  # WordPress adapters (Repository, Service)
│   └── Presentation/    # Controllers and Hooks
├── tests/
│   ├── Unit/            # 91 unit tests
│   └── Integration/     # Integration tests
└── theme-core-features.php
```

**Features:**
- Color customization (Primary, Secondary, Background, Text)
- Typography settings (Font family selection)
- Layout options (Grid, List, Masonry with 1-4 columns)
- Dynamic CSS generation with caching
- WordPress Customizer integration

## 🛠️ Development

### Working with the theme

```bash
cd packages/theme
# Edit template files, functions.php, style.css, etc.
```

### Working with the plugin

```bash
cd packages/plugin

# Install dependencies
composer install

# Run tests
composer test:unit

# Static analysis
composer analyse

# Fix code style
composer cs:fix
```

### Docker commands

```bash
cd docker

# Start environment
docker-compose up -d

# Stop environment
docker-compose stop

# View logs
docker-compose logs -f wordpress

# Restart
docker-compose restart

# Remove everything (including database)
docker-compose down -v
```

## 🧪 Testing

The plugin includes comprehensive testing:

```bash
cd packages/plugin

# Unit tests (91 tests, 171 assertions)
composer test:unit

# PHPStan Level 8
composer analyse

# Code style check
composer cs:check

# All checks
composer test
```

## 📋 Requirements

- **PHP**: 8.1+ (enums, readonly properties, modern syntax)
- **WordPress**: 6.4+
- **Docker**: Latest version
- **Composer**: 2.x

## 🎯 Version

- **Theme**: 2.0.0 (Hexagonal architecture)
- **Plugin**: 2.0.0 (Hexagonal architecture)

## 📖 Key Concepts

### Hexagonal Architecture

The plugin follows hexagonal architecture principles:

- **Domain Layer**: Pure business logic (no WordPress dependencies)
- **Application Layer**: Use cases orchestrating domain logic
- **Infrastructure Layer**: WordPress adapters (theme_mod, transients, CSS generation)
- **Presentation Layer**: Hooks and Customizer integration

### Benefits

- **Testability**: 91 unit tests with mocked WordPress functions
- **Maintainability**: Clean separation of concerns
- **Type Safety**: PHP 8.1+ with PHPStan Level 8
- **Performance**: CSS caching via WordPress Transients
- **Extensibility**: Ports & Adapters pattern allows easy replacements

## 🔄 Migration from v1.x

If you're upgrading from the old theme-based architecture to v2.0.0 plugin:

1. Backup your database and theme files
2. Activate the "Theme Core Features" plugin
3. Verify customizer settings are preserved
4. All existing `theme_mod` values are automatically migrated

See [MIGRATION.md](packages/plugin/MIGRATION.md) for complete instructions.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'feat: add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

GPL v2 or later

## 👤 Author

**Willian Sant'Anna**

- GitHub: [@wssantanna](https://github.com/wssantanna)
- Repository: [theme-globaltech](https://github.com/wssantanna/theme-globaltech)

## 🐛 Issues

Report issues at: https://github.com/wssantanna/theme-globaltech/issues

---

**Made with ❤️ using Hexagonal Architecture and WordPress best practices**

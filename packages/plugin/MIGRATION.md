# Migration Guide - Theme Core Features v2.0.0

**Date:** 2026-01-12
**Plugin Version:** 2.0.0
**Required PHP:** 8.1+
**Required WordPress:** 6.4+

---

## Overview

This guide helps you migrate from the legacy theme-based customizer implementation to the new plugin-based hexagonal architecture.

**What Changed:**
- Theme customizer code moved to plugin (`wp-content/plugins/theme-core-features`)
- Cleaner separation between theme (presentation) and business logic (plugin)
- Better testability and maintainability
- No loss of functionality - all settings preserved

---

## Before You Begin

### Backup Checklist

- [ ] Backup your database (wp_options table contains theme_mod values)
- [ ] Backup theme directory (`wp-content/themes/your-theme/`)
- [ ] Export current customizer settings (Appearance → Customize → Export)
- [ ] Take screenshots of your current customizer configuration

### System Requirements

```bash
# Verify PHP version
php -v  # Should be 8.1 or higher

# Verify WordPress version
# Go to Dashboard → Updates (Should be 6.4 or higher)

# Verify plugin is installed
ls wp-content/plugins/theme-core-features/
```

---

## Migration Steps

### Step 1: Activate the Plugin

```bash
# Via WP-CLI
wp plugin activate theme-core-features

# Or via WordPress Admin
# Go to: Plugins → Installed Plugins
# Find: "Theme Core Features"
# Click: Activate
```

**Verification:**
```php
// Check if plugin is loaded
if (defined('THEME_CORE_VERSION')) {
    echo 'Plugin loaded: v' . THEME_CORE_VERSION;
}
```

### Step 2: Verify Customizer Settings

**Navigate to:** Appearance → Customize

**Check these sections:**
- Theme Options → Colors (Primary, Secondary, Background, Text)
- Theme Options → Typography (Font Family)
- Theme Options → Layout (Mode, Columns, Sidebar)

**Expected Result:** All settings should appear and function identically to before.

---

## Feature Flag: Progressive Migration

The plugin includes a feature flag for gradual migration:

```php
// In wp-config.php or theme functions.php

// Use NEW plugin-based customizer (default)
define('THEME_CORE_NEW_CUSTOMIZER', true);

// Use OLD theme-based customizer (legacy mode)
define('THEME_CORE_NEW_CUSTOMIZER', false);
```

**Migration Strategy:**
1. Start with `false` (legacy mode) - verify site works
2. Switch to `true` (new mode) - test all customizer features
3. If issues arise, switch back to `false` and report bugs
4. Once stable, remove the flag entirely (defaults to `true`)

---

## Settings Mapping

### Color Settings

| **Legacy Setting** | **Plugin Setting** | **Status** | **Default** |
|---|---|---|---|
| `theme_primary_color` | `theme_primary_color` | ✓ Preserved | #0d6efd |
| `theme_secondary_color` | `theme_secondary_color` | ✓ Added | #6c757d |
| `theme_header_bg_color` | N/A | ⚠️ Deprecated | Use CSS |
| `theme_footer_bg_color` | N/A | ⚠️ Deprecated | Use CSS |
| N/A | `theme_background_color` | ✓ New | #ffffff |
| N/A | `theme_text_color` | ✓ New | #212529 |

**Migration Note:** Header/Footer colors are now handled via CSS variables. See [Custom CSS](#custom-css) section.

### Typography Settings

| **Legacy Setting** | **Plugin Setting** | **Status** |
|---|---|---|
| `theme_body_font` | `theme_font_family` | ✓ Preserved |
| `theme_heading_font` | `theme_font_family` | ✓ Unified |

**Migration Note:** The plugin uses a single font family for consistency. If you need separate fonts, use custom CSS.

### Layout Settings

| **Legacy Setting** | **Plugin Setting** | **Status** |
|---|---|---|
| `theme_posts_display_style` | `theme_layout_mode` | ✓ Preserved |
| `theme_posts_layout` | `theme_column_count` | ✓ Preserved |
| `theme_show_post_date` | N/A | ⚠️ Theme-level |
| `theme_show_post_author` | N/A | ⚠️ Theme-level |
| N/A | `theme_show_sidebar` | ✓ New |

**Migration Note:** Post metadata visibility (`show_post_date`, `show_post_author`) remains in theme templates as it's presentation logic.

---

## Custom CSS

If you customized header/footer colors using legacy settings, migrate to CSS:

### Before (Legacy Theme):
```php
// customizer-colors.php
$header_bg = get_theme_mod('theme_header_bg_color', '#212529');
$footer_bg = get_theme_mod('theme_footer_bg_color', '#f8f9fa');
```

### After (Plugin CSS Variables):
```css
/* In your theme's style.css or Customizer Additional CSS */
:root {
    --theme-header-bg: #212529;
    --theme-footer-bg: #f8f9fa;
}

header.site-header {
    background-color: var(--theme-header-bg);
}

footer.site-footer {
    background-color: var(--theme-footer-bg);
}
```

**Or use Customizer Additional CSS:**
```
Appearance → Customize → Additional CSS
```

---

## Theme Functions Cleanup

### Before Migration (Legacy):
```php
// src/functions.php
require_once get_template_directory() . '/inc/customizer/customizer.php';
require_once get_template_directory() . '/inc/customizer/customizer-colors.php';
require_once get_template_directory() . '/inc/customizer/customizer-typography.php';
require_once get_template_directory() . '/inc/customizer/customizer-layout.php';
require_once get_template_directory() . '/inc/customizer/customizer-css.php';

function theme_wordpress_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    // ... more setup
}
add_action('after_setup_theme', 'theme_wordpress_setup');
```

### After Migration (Clean):
```php
// src/functions.php

// Plugin handles theme setup, customizer, and assets
// Keep only theme-specific template functions here

// Example: Keep custom walker for menus
class Theme_WordPress_Bootstrap_Nav_Walker extends Walker_Nav_Menu {
    // ... walker implementation
}

// Example: Keep template-specific helpers
function theme_wordpress_custom_excerpt_length() {
    return 20;
}
add_filter('excerpt_length', 'theme_wordpress_custom_excerpt_length');
```

**What the Plugin Now Handles:**
- ✓ Theme setup (`add_theme_support`, menus, text domain)
- ✓ Asset enqueuing (CSS/JS)
- ✓ Customizer registration (colors, typography, layout)
- ✓ Dynamic CSS generation

**What Stays in Theme:**
- Template files (header.php, footer.php, etc.)
- Template parts (content-card.php, content-list.php)
- Custom walkers and template helpers
- Theme-specific JavaScript (main.js)

---

## Deprecated Functions

These functions are no longer needed and will show deprecation notices:

### Theme Setup
```php
// ❌ DEPRECATED - Plugin handles this
theme_wordpress_setup()
theme_wordpress_register_menus()

// ✓ Use plugin's ThemeSetupHook instead
```

### Customizer Registration
```php
// ❌ DEPRECATED - Plugin handles this
theme_wordpress_customize_register()
theme_wordpress_load_customizer_modules()

// ✓ Use plugin's CustomizerController instead
```

### Dynamic CSS
```php
// ❌ DEPRECATED - Plugin handles this
theme_wordpress_customizer_css()
theme_wordpress_customizer_live_preview()

// ✓ Use plugin's GenerateDynamicCSSUseCase instead
```

### Color Helpers
```php
// ⚠️ MOVED TO PLUGIN (if needed)
theme_wordpress_hex_to_rgb()
theme_wordpress_get_contrast_color()
theme_wordpress_adjust_brightness()

// These utilities will be available in the plugin if needed
```

---

## Files You Can Delete

After verifying the plugin works correctly, you can safely delete:

```bash
# Legacy customizer files
src/inc/customizer/customizer.php
src/inc/customizer/customizer-colors.php
src/inc/customizer/customizer-typography.php
src/inc/customizer/customizer-layout.php
src/inc/customizer/customizer-css.php
src/inc/customizer/customizer-color-helpers.php
src/inc/customizer/customizer-preview.js
src/inc/customizer/customizer-logo.php  # Optional - move to plugin if needed

# Or delete entire directory
rm -rf src/inc/customizer/
```

**⚠️ Warning:** Only delete after:
1. Plugin is activated and tested
2. All customizer settings are verified
3. You have a backup
4. You've run visual regression tests

---

## Rollback Procedure

If issues arise, you can rollback:

### Quick Rollback (Feature Flag)
```php
// In wp-config.php
define('THEME_CORE_NEW_CUSTOMIZER', false);
```

### Full Rollback (Deactivate Plugin)
```bash
# Via WP-CLI
wp plugin deactivate theme-core-features

# Via WordPress Admin
# Plugins → Installed Plugins → Theme Core Features → Deactivate
```

### Restore Theme Files
```bash
# Restore from backup
cp -r backup/inc/customizer/ src/inc/customizer/

# Re-require in functions.php
# Uncomment the legacy require_once lines
```

---

## Testing Checklist

After migration, test these areas:

### Visual Tests
- [ ] Homepage displays correctly
- [ ] Colors match previous design (primary, secondary, background, text)
- [ ] Fonts render correctly (body and headings)
- [ ] Layout mode works (grid/list/masonry)
- [ ] Column count adjusts properly
- [ ] Sidebar shows/hides as configured

### Functional Tests
- [ ] Customizer opens without errors
- [ ] Live preview updates in real-time
- [ ] Settings save successfully
- [ ] CSS variables are output in `<head>`
- [ ] No JavaScript console errors
- [ ] No PHP errors in debug.log

### Performance Tests
- [ ] Page load time unchanged or improved
- [ ] Dynamic CSS cached properly
- [ ] No duplicate CSS output

### Browser Tests
- [ ] Chrome/Edge (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Mobile browsers

---

## Common Issues and Solutions

### Issue: Customizer Section Not Appearing

**Symptom:** Theme Options sections missing in Customizer

**Solution:**
```php
// Verify plugin is active
if (!defined('THEME_CORE_VERSION')) {
    // Plugin not loaded - activate it
    wp plugin activate theme-core-features
}

// Check feature flag
var_dump(THEME_CORE_NEW_CUSTOMIZER); // Should be true
```

### Issue: Settings Lost After Migration

**Symptom:** Colors/fonts revert to defaults

**Cause:** Settings stored in database should persist automatically

**Solution:**
```php
// Verify theme_mods in database
wp option get theme_mods_your-theme-name

// Should show:
// theme_primary_color: #0d6efd
// theme_font_family: system-ui
// theme_layout_mode: grid
```

### Issue: Dynamic CSS Not Loading

**Symptom:** Styles not applied, CSS variables missing

**Solution:**
```php
// Check if AssetsHook is registered
add_action('wp_head', function() {
    if (did_action('theme_core_loaded')) {
        echo '<!-- Plugin loaded ✓ -->';
    }
}, 1);

// Clear cache
wp transient delete theme_core_dynamic_css
```

### Issue: PHP Fatal Error After Activation

**Symptom:** White screen or fatal error

**Cause:** PHP version < 8.1 or missing dependencies

**Solution:**
```bash
# Check PHP version
php -v

# If < 8.1, update PHP or deactivate plugin
wp plugin deactivate theme-core-features

# Check composer dependencies
cd wp-content/plugins/theme-core-features
composer install --no-dev
```

---

## Support and Troubleshooting

### Debug Mode

Enable WordPress debug mode to see detailed errors:

```php
// wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

// Check logs
tail -f wp-content/debug.log
```

### Plugin Health Check

```bash
cd wp-content/plugins/theme-core-features

# Run tests
composer test:unit

# Run static analysis
composer analyse

# Check autoloader
composer dump-autoload
```

### Get Help

- **Issues:** https://github.com/wssantanna/theme-globaltech/issues
- **Documentation:** `wp-content/plugins/theme-core-features/README.md`
- **Architecture:** `wp-content/plugins/theme-core-features/ARCHITECTURE.md`

---

## FAQ

### Q: Will my existing customizer settings be lost?

**A:** No. All settings are stored in `wp_options` table as theme_mods and remain intact. The plugin reads from the same location.

### Q: Can I use both old and new customizer simultaneously?

**A:** No. Use the feature flag to switch between them, but only one can be active at a time.

### Q: What if I need custom customizer sections?

**A:** You can still add custom sections by hooking into `customize_register`. The plugin won't interfere with additional customizations.

### Q: Does this work with child themes?

**A:** Yes. The plugin is theme-agnostic and works with parent and child themes.

### Q: Can I revert back to the theme-based approach?

**A:** Yes, at any time. Simply deactivate the plugin and restore your theme's customizer files from backup.

### Q: Is there a performance impact?

**A:** No. The plugin uses caching (WP Transients) and follows WordPress best practices. Performance should be identical or better.

---

## Version History

### v2.0.0 (2026-01-12)
- Complete hexagonal architecture refactoring
- Domain, Infrastructure, Application, Presentation layers
- 91 unit tests, PHPStan Level 8
- Feature flag for gradual migration
- This migration guide

### v1.x (Legacy)
- Theme-based customizer implementation
- Deprecated as of v2.0.0

---

## Next Steps

1. **Read:** [README.md](README.md) for architecture overview
2. **Explore:** [ARCHITECTURE.md](ARCHITECTURE.md) for technical details
3. **Review:** [REFACTORING-PLAN.md](REFACTORING-PLAN.md) for development process
4. **Test:** Run through the testing checklist above
5. **Clean:** Remove legacy theme files after verification

---

**Migration Complete?** Your site should now be running on the clean, maintainable hexagonal architecture. Enjoy better code organization and easier testing!

**Questions?** Open an issue on GitHub or check the documentation.

---

**Author:** Willian Sant'Anna
**Repository:** https://github.com/wssantanna/theme-globaltech
**License:** GPL v2 or later

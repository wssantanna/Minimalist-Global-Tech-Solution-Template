<?php
/**
 * Plugin Name: Theme Core Features
 * Plugin URI: https://github.com/wssantanna/theme-globaltech
 * Description: Core business logic and features for Theme Globaltech (Hexagonal Architecture)
 * Version: 2.0.0
 * Requires at least: 6.4
 * Requires PHP: 8.1
 * Author: Willian Sant'Anna
 * Author URI: https://github.com/wssantanna
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: theme-core
 * Domain Path: /languages
 * Network: true
 *
 * @package ThemeCore
 */

declare(strict_types=1);

namespace ThemeCore;

// Use statements must be at the top of the file
use ThemeCore\Application\UseCase\GenerateDynamicCSSUseCase;
use ThemeCore\Application\UseCase\GetThemeConfigUseCase;
use ThemeCore\Application\UseCase\UpdateThemeConfigUseCase;
use ThemeCore\Infrastructure\Adapter\WPThemeModRepository;
use ThemeCore\Infrastructure\Adapter\WPCSSGenerator;
use ThemeCore\Infrastructure\Adapter\WPTransientCache;
use ThemeCore\Presentation\Customizer\CustomizerController;
use ThemeCore\Presentation\Hook\AssetsHook;
use ThemeCore\Presentation\Hook\CustomizerHook;
use ThemeCore\Presentation\Hook\HookRegistry;
use ThemeCore\Presentation\Hook\ThemeSetupHook;

if (!defined('ABSPATH')) {
    exit;
}

// Plugin constants
define('THEME_CORE_VERSION', '2.0.0');
define('THEME_CORE_PLUGIN_FILE', __FILE__);
define('THEME_CORE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('THEME_CORE_PLUGIN_URL', plugin_dir_url(__FILE__));

// Feature flags
if (!defined('THEME_CORE_NEW_CUSTOMIZER')) {
    define('THEME_CORE_NEW_CUSTOMIZER', true); // Enable new Customizer by default
}

// Composer autoloader
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Main plugin bootstrap
 *
 * Initializes the plugin and registers all hooks.
 */
function bootstrap(): void
{
    // Initialize dependency injection
    $container = createContainer();

    // Register all hooks
    registerHooks($container);

    // Hook for future extensibility
    do_action('theme_core_loaded');
}

/**
 * Create and configure dependency injection container
 *
 * @return array<string, object> Simple service container
 */
function createContainer(): array
{
    // Infrastructure layer
    $repository = new WPThemeModRepository();
    $cssGenerator = new WPCSSGenerator();
    $cache = new WPTransientCache();

    // Application layer
    $getConfigUseCase = new GetThemeConfigUseCase($repository);
    $updateConfigUseCase = new UpdateThemeConfigUseCase($repository, $cache);
    $generateCSSUseCase = new GenerateDynamicCSSUseCase($repository, $cssGenerator, $cache);

    return [
        'repository' => $repository,
        'cssGenerator' => $cssGenerator,
        'cache' => $cache,
        'getConfigUseCase' => $getConfigUseCase,
        'updateConfigUseCase' => $updateConfigUseCase,
        'generateCSSUseCase' => $generateCSSUseCase,
    ];
}

/**
 * Register all application hooks
 *
 * @param array<string, object> $container
 * @return void
 */
function registerHooks(array $container): void
{
    // Create hook registry
    $registry = new HookRegistry();

    // Theme Setup Hook (theme supports, menus, etc.)
    $registry->add(new ThemeSetupHook());

    // Assets Hook (enqueue CSS/JS)
    $registry->add(new AssetsHook(fn() => $container['generateCSSUseCase']->execute()));

    // Customizer Hook (WordPress Customizer integration)
    if (THEME_CORE_NEW_CUSTOMIZER) {
        $controller = new CustomizerController();
        $registry->add(new CustomizerHook($controller));
    }

    // Register all hooks with WordPress
    $registry->registerAll();
}

// Initialize plugin
add_action('plugins_loaded', __NAMESPACE__ . '\\bootstrap', 10);

/**
 * Activation hook
 */
function activate(): void
{
    // Verificar requisitos
    if (version_compare(PHP_VERSION, '8.1.0', '<')) {
        wp_die(
            esc_html__('Theme Core Features requires PHP 8.1 or higher.', 'theme-core'),
            esc_html__('Plugin Activation Error', 'theme-core'),
            ['back_link' => true]
        );
    }

    if (version_compare(get_bloginfo('version'), '6.4', '<')) {
        wp_die(
            esc_html__('Theme Core Features requires WordPress 6.4 or higher.', 'theme-core'),
            esc_html__('Plugin Activation Error', 'theme-core'),
            ['back_link' => true]
        );
    }

    // Flush rewrite rules (se necessário no futuro)
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, __NAMESPACE__ . '\\activate');

/**
 * Deactivation hook
 */
function deactivate(): void
{
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, __NAMESPACE__ . '\\deactivate');

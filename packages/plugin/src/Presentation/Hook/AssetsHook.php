<?php

declare(strict_types=1);

namespace ThemeCore\Presentation\Hook;

/**
 * Assets hook
 *
 * Enqueues dynamic CSS and JavaScript assets for the theme.
 */
final class AssetsHook implements HookInterface
{
    /**
     * CSS callback - will be set after construction
     *
     * @var callable(): string
     */
    private $cssCallback;

    public function __construct(
        ?callable $cssCallback = null
    ) {
        $this->cssCallback = $cssCallback ?? fn() => '';
    }

    /**
     * Register the hook with WordPress
     *
     * @return void
     */
    public function register(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueStyles']);
        add_action('wp_head', [$this, 'outputDynamicCSS'], 99);
    }

    /**
     * Enqueue theme stylesheets
     *
     * @return void
     */
    public function enqueueStyles(): void
    {
        // Enqueue main theme stylesheet (if exists)
        $themeStylePath = get_template_directory() . '/style.css';
        if (file_exists($themeStylePath)) {
            wp_enqueue_style(
                'theme-globaltech-style',
                get_template_directory_uri() . '/style.css',
                [],
                $this->getFileVersion($themeStylePath)
            );
        }
    }

    /**
     * Output dynamic CSS in wp_head
     *
     * @return void
     */
    public function outputDynamicCSS(): void
    {
        $css = ($this->cssCallback)();

        if (!empty($css)) {
            echo "<!-- Theme Core Dynamic CSS -->\n";
            echo "<style id=\"theme-core-dynamic-css\">\n";
            echo $css;
            echo "\n</style>\n";
        }
    }

    /**
     * Get file modification time for cache busting
     *
     * @param string $filePath
     * @return string
     */
    private function getFileVersion(string $filePath): string
    {
        if (file_exists($filePath)) {
            return (string) filemtime($filePath);
        }

        return '1.0.0';
    }
}

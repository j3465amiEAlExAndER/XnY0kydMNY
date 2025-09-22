<?php
// 代码生成时间: 2025-09-23 00:02:18
namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\Extension\StringExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

class XssProtectionService {

    private Environment $twig;

    public function __construct() {
        // Initialize Twig with a filesystem loader
        $loader = new FilesystemLoader(__DIR__);
        $this->twig = new Environment($loader);

        // Add a custom filter to escape HTML
        $this->twig->addFilter(
            new TwigFilter(
                'escape_html',
                function ($string) {
                    return \u003C?php echo htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8'); \u003F\u003E;
                },
                ['is_safe' => ['html']]
            )
        );
    }

    /**
     * Sanitize input to prevent XSS attacks.
     *
     * @param string $input The user input to sanitize.
     * @return string The sanitized input.
     */
    public function sanitizeInput(string $input): string {
        try {
            // Use Twig's escape_html filter to sanitize the input
            return $this->twig->createTemplate('{{ input|escape_html }}')->render(['input' => $input]);
        } catch (\Exception $e) {
            // Handle any potential errors
            // Log the error and return an appropriate message
            // This could be replaced with more sophisticated error handling
            return 'Error sanitizing input.';
        }
    }

    /**
     * Render a template with XSS protection.
     *
     * @param string $template The path to the Twig template file.
     * @param array $parameters The parameters to pass to the template.
     * @return string The rendered template with XSS protection.
     */
    public function renderTemplate(string $template, array $parameters): string {
        try {
            // Render the template with the given parameters
            return $this->twig->render($template, $parameters);
        } catch (\Exception $e) {
            // Handle any potential errors
            // Log the error and return an appropriate message
            return 'Error rendering template.';
        }
    }
}

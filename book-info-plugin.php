<?php
/**
 * Plugin Name:     Books Info Plugin
 * Plugin URI:      https://example.com
 * Description:     Plugin to manage book information.
 * Author:          Your Name
 * Author URI:      https://example.com
 * Text Domain:     books-info-plugin
 * Domain Path:     /languages
 * Version:         1.0.0
 */

namespace BooksInfoPlugin;

use Composer\Autoload\ClassLoader;

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    /** @var ClassLoader $loader */
    $loader = require __DIR__ . '/vendor/autoload.php';
} else {
    add_action('admin_notices', function () {
        echo '<div class="notice notice-error"><p>Rabbit framework not found. Please install dependencies via Composer.</p></div>';
    });
    return;
}

use BooksInfoPlugin\BooksInfoPlugin;

// Initialize the plugin
function books_info_plugin()
{
    return BooksInfoPlugin::get();
}

books_info_plugin();

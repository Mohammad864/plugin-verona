<?php
/**
 * Plugin Name:     Books Info Plugin
 * Plugin URI:      https://github.com/Mohammad864/plugin-verona/tree/main
 * Plugin Prefix:   BIP
 * Description:     Plugin to manage book information.
 * Author:          Mohammad Taghipoor
 * Author URI:      https://linkedin.com/in/mohammad-taghipoor
 * Text Domain:     books-info-plugin
 * Domain Path:     /languages
 * Version:         1.0.0
 */

namespace BooksInfoPlugin;

use Rabbit\Application;
use Rabbit\Database\DatabaseServiceProvider;
use Rabbit\Logger\LoggerServiceProvider;
use Rabbit\Plugin;
use Rabbit\Redirects\AdminNotice;
use Rabbit\Templates\TemplatesServiceProvider;
use Rabbit\Utils\Singleton;
use WP_List_Table;
use Exception;

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require dirname(__FILE__) . '/vendor/autoload.php';
} else {
    // Handle the error, perhaps by showing an admin notice
    add_action('admin_notices', function () {
        echo '<div class="notice notice-error"><p>Rabbit framework not found. Please install dependencies via Composer.</p></div>';
    });
    return;
}

/**
 * Main Plugin Class
 */
class BooksInfoPlugin extends Singleton
{
    private $application;

    public function __construct()
    {
        $this->application = Application::get()->loadPlugin(__DIR__, __FILE__, 'config');
        $this->init();

        // Enqueue scripts
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    public function enqueueScripts()
    {
        wp_enqueue_script('index', plugins_url('assets/src/js/admin/index.js', __FILE__), ['jquery'], null, true);
        wp_localize_script('index', 'BooksInfoAjax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('books_info_nonce'),
        ]);
    }

    public function init()
    {
        try {
            // Register service providers
            $this->application->addServiceProvider(DatabaseServiceProvider::class);
            $this->application->addServiceProvider(LoggerServiceProvider::class);
            $this->application->addServiceProvider(TemplatesServiceProvider::class);
            
            // Register custom post type and taxonomies
            add_action('init', [$this, 'registerCustomPostType']);

            // Add meta box for ISBN
            add_action('add_meta_boxes', [$this, 'addISBNMetaBox']);
            add_action('save_post', [$this, 'saveISBNMetaBox']);

            // Create table on plugin activation
            $this->application->onActivation(function() {
                $this->createBooksTable();
            });

            // Add admin menu to display books_info table
            add_action('admin_menu', [$this, 'addAdminMenu']);

            // AJAX action to save ISBN in real-time
            add_action('wp_ajax_save_isbn', [$this, 'ajaxSaveISBN']);

        } catch (Exception $e) {
            add_action('admin_notices', function () use ($e) {
                AdminNotice::permanent(['type' => 'error', 'message' => $e->getMessage()]);
            });
        }
    }

    public function createBooksTable()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'books_info';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            ID bigint(20) NOT NULL AUTO_INCREMENT,
            post_id bigint(20) NOT NULL,
            isbn varchar(20) NOT NULL,
            PRIMARY KEY (ID),
            UNIQUE KEY isbn (isbn)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function registerCustomPostType()
    {
        register_post_type('book', [
            'labels' => [
                'name' => __('Books', 'books-info-plugin'),
                'singular_name' => __('Book', 'books-info-plugin')
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail'],
            'taxonomies' => ['publisher', 'authors'],
            'show_in_rest' => true
        ]);

        register_taxonomy('publisher', 'book', [
            'label' => __('Publisher', 'books-info-plugin'),
            'rewrite' => ['slug' => 'publisher'],
            'hierarchical' => true,
            'show_in_rest' => true,
        ]);

        register_taxonomy('authors', 'book', [
            'label' => __('Authors', 'books-info-plugin'),
            'rewrite' => ['slug' => 'authors'],
            'hierarchical' => false,
            'show_in_rest' => true,
        ]);
    }

    public function addISBNMetaBox()
    {
        add_meta_box(
            'isbn_meta_box',
            __('ISBN', 'books-info-plugin'),
            [$this, 'renderISBNMetaBox'],
            'book',
            'side'
        );
    }

    public function renderISBNMetaBox($post)
    {
        wp_nonce_field('isbn_meta_box', 'isbn_meta_box_nonce');
        $isbn = get_post_meta($post->ID, '_isbn', true);
        echo '<input type="text" name="isbn" value="' . esc_attr($isbn) . '" class="widefat">';
    }

    public function saveISBNMetaBox($post_id)
    {
        if (!isset($_POST['isbn_meta_box_nonce']) || !wp_verify_nonce($_POST['isbn_meta_box_nonce'], 'isbn_meta_box')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (isset($_POST['post_type']) && 'book' === $_POST['post_type']) {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }

        if (!isset($_POST['isbn'])) {
            return;
        }

        $isbn = sanitize_text_field($_POST['isbn']);
        update_post_meta($post_id, '_isbn', $isbn);

        global $wpdb;
        $table_name = $wpdb->prefix . 'books_info';

        $wpdb->replace($table_name, [
            'post_id' => $post_id,
            'isbn' => $isbn
        ], ['%d', '%s']);
    }

    public function ajaxSaveISBN()
    {
        check_ajax_referer('books_info_nonce', 'nonce');

        if (!current_user_can('edit_post', $_POST['post_id'])) {
            wp_send_json_error('You do not have permission to edit this post.');
        }

        $post_id = intval($_POST['post_id']);
        $isbn = sanitize_text_field($_POST['isbn']);

        update_post_meta($post_id, '_isbn', $isbn);

        global $wpdb;
        $table_name = $wpdb->prefix . 'books_info';

        $existing_record = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE post_id = %d", $post_id));

        if ($existing_record) {
            $wpdb->update($table_name, ['isbn' => $isbn], ['post_id' => $post_id], ['%s'], ['%d']);
        } else {
            $wpdb->insert($table_name, ['post_id' => $post_id, 'isbn' => $isbn], ['%d', '%s']);
        }

        wp_send_json_success('ISBN saved successfully.');
    }

    public function addAdminMenu()
    {
        add_menu_page(
            __('Books Info', 'books-info-plugin'),
            __('Books Info', 'books-info-plugin'),
            'manage_options',
            'books-info',
            [$this, 'renderAdminPage']
        );
    }

    public function renderAdminPage()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'books_info';

        $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

        echo '<div class="wrap"><h1>' . esc_html__('Books Info', 'books-info-plugin') . '</h1>';
        if (!empty($results)) {
            echo '<table class="widefat fixed" cellspacing="0"><thead><tr>';
            echo '<th class="manage-column">' . esc_html__('ID', 'books-info-plugin') . '</th>';
            echo '<th class="manage-column">' . esc_html__('Post ID', 'books-info-plugin') . '</th>';
            echo '<th class="manage-column">' . esc_html__('ISBN', 'books-info-plugin') . '</th>';
            echo '</tr></thead><tbody>';

            foreach ($results as $row) {
                echo '<tr>';
                echo '<td>' . esc_html($row['ID']) . '</td>';
                echo '<td>' . esc_html($row['post_id']) . '</td>';
                echo '<td>' . esc_html($row['isbn']) . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table>';
        } else {
            echo '<p>' . esc_html__('No books information found.', 'books-info-plugin') . '</p>';
        }
        echo '</div>';
    }

    public function getApplication()
    {
        return $this->application;
    }
}

function BooksInfoPlugin()
{
    return BooksInfoPlugin::get();
}

BooksInfoPlugin();

<?php

namespace BooksInfoPlugin;

use wpdb;

class BooksTableManager
{
    private $wpdb;

    public function __construct(wpdb $wpdb)
    {
        $this->wpdb = $wpdb;
    }

    public function createTable()
    {
        $table_name = $this->wpdb->prefix . 'books_info';
        $charset_collate = $this->wpdb->get_charset_collate();

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

    public function replaceBook($post_id, $isbn)
    {
        $table_name = $this->wpdb->prefix . 'books_info';

        $this->wpdb->replace($table_name, [
            'post_id' => $post_id,
            'isbn' => $isbn
        ], ['%d', '%s']);
    }
}

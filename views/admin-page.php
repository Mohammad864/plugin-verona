<div class="wrap">
    <h1><?php echo esc_html__('Books Info', 'books-info-plugin'); ?></h1>
    <?php
    global $wpdb;
    $table_name = $wpdb->prefix . 'books_info';

    $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

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
    ?>
</div>

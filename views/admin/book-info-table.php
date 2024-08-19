<div class="wrap">
    <h2><?php _e('Book Information', 'book-info-plugin'); ?></h2>
    <?php
    $book_table = new \RabbitExamplePlugin\Admin\BookInfoTable();
    $book_table->display_table();
    ?>
</div>

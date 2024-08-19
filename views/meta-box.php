<?php
wp_nonce_field('isbn_meta_box', 'isbn_meta_box_nonce');
$isbn = get_post_meta($post->ID, '_isbn', true);
?>
<input type="text" name="isbn" value="<?php echo esc_attr($isbn); ?>" class="widefat">

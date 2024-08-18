<?php

namespace RabbitExamplePlugin\PostTypes;

class Book
{
    public static function register()
    {
        register_post_type('book', [
            'labels' => [
                'name' => __('Books', 'book-info-plugin'),
                'singular_name' => __('Book', 'book-info-plugin'),
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor'],
            'taxonomies' => ['publisher', 'author'],
        ]);
    }

    public static function registerTaxonomies()
    {
        register_taxonomy('publisher', 'book', [
            'label' => __('Publishers', 'book-info-plugin'),
            'hierarchical' => true,
        ]);

        register_taxonomy('author', 'book', [
            'label' => __('Authors', 'book-info-plugin'),
            'hierarchical' => false,
        ]);
    }
}

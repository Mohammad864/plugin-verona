<?php

namespace RabbitExamplePlugin\ServiceProviders;

use Rabbit\Providers\ServiceProvider;
use RabbitExamplePlugin\PostTypes\Book;

class BookInfoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->application->singleton('book_info_service', function () {
            return new BookInfoService();
        });
    }

    public function boot()
    {
        add_action('init', [Book::class, 'register']);
        add_action('init', [Book::class, 'registerTaxonomies']);
    }
}

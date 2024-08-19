<?php

namespace BooksInfoPlugin;

use League\Container\ServiceProvider\AbstractServiceProvider;
use wpdb;

/**
 * Class BooksServiceProvider
 *
 * This class registers the BooksTableManager service in the container.
 *
 * @package BooksInfoPlugin
 */
class BooksServiceProvider extends AbstractServiceProvider
{
    /**
     * Services provided by this service provider.
     *
     * @var array
     */
    protected $provides = [
        BooksTableManager::class,
    ];

    /**
     * Register services in the container.
     */
    public function register()
    {
        $this->container->share(BooksTableManager::class, function () {
            global $wpdb;
            return new BooksTableManager($wpdb);
        });
    }
}

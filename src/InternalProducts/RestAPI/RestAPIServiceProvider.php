<?php

declare(strict_types=1);

/**
 * Boots the rest API service provider.
 */

namespace OWC\OpenPub\InternalProducts\RestAPI;

use OWC\OpenPub\Base\Foundation\ServiceProvider;

/**
 * Boots the rest API service provider.
 */
class RestAPIServiceProvider extends ServiceProvider
{

    /**
     * Endpoint of the rest API.
     *
     * @var string;
     */
    private $namespace = 'owc/openpub/v1';

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->plugin->loader->addAction('rest_api_init', $this, 'registerRoutes');
    }

    /**
     * Register routes on the rest API.
     *
     * We are overriding the default endpoints
     */
    public function registerRoutes()
    {
        register_rest_route($this->namespace, 'items', [
            'methods'             => 'GET',
            'callback'            => [(new ItemsFactory('getItems', $this->plugin)), 'retrieve'],
            'permission_callback' => '__return_true',
        ], true);

        register_rest_route($this->namespace, 'items/(?P<id>\d+)', [
            'methods'             => 'GET',
            'callback'            => [(new ItemsFactory('getItem', $this->plugin)), 'retrieve'],
            'permission_callback' => '__return_true',
        ], true);

        register_rest_route($this->namespace, 'items/(?P<slug>[\w-]+)', [
            'methods'             => 'GET',
            'callback'            => [(new ItemsFactory('getItemBySlug', $this->plugin)), 'retrieve'],
            'permission_callback' => '__return_true',
        ], true);

        register_rest_route($this->namespace, 'items/active', [
            'methods'             => 'GET',
            'callback'            => [(new ItemsFactory('getActiveItems', $this->plugin)), 'retrieve'],
            'permission_callback' => '__return_true',
        ], true);
    }
}

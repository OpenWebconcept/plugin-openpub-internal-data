<?php

declare(strict_types=1);

/**
 * Boots the rest API service provider.
 */

namespace OWC\OpenPub\InternalData\RestAPI;

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
        // Overriding a route drops the args OpenPub Base declared for it, so declare them again.
        $controller = new InternalItemsController($this->plugin);

        register_rest_route($this->namespace, 'items', [
            'methods' => 'GET',
            'callback' => [(new ItemsFactory('getItems', $this->plugin)), 'retrieve'],
            'args' => $controller->collectionArguments(),
            'permission_callback' => '__return_true',
        ], true);

		register_rest_route($this->namespace, 'items/active', [
			'methods' => 'GET',
			'callback' => [(new ItemsFactory('getActiveItems', $this->plugin)), 'retrieve'],
			'args' => $controller->collectionArguments(),
			'permission_callback' => '__return_true',
		], true);

        register_rest_route($this->namespace, 'items/(?P<id>\d+)', [
            'methods' => 'GET',
            'callback' => [(new ItemsFactory('getItem', $this->plugin)), 'retrieve'],
            'args' => $controller->singleArguments(),
            'permission_callback' => '__return_true',
        ], true);

        register_rest_route($this->namespace, 'items/(?P<slug>[\w-]+)', [
            'methods' => 'GET',
            'callback' => [(new ItemsFactory('getItemBySlug', $this->plugin)), 'retrieve'],
            'args' => $controller->singleArguments(),
            'permission_callback' => '__return_true',
        ], true);
    }
}

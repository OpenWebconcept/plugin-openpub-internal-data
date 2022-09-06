<?php

declare(strict_types=1);

namespace OWC\OpenPub\InternalProducts\RestAPI;

use OWC\OpenPub\InternalProducts\Auth\AuthValidator;
use OWC\OpenPub\InternalProducts\Foundation\Plugin;
use OWC\OpenPub\InternalProducts\Interfaces\ItemController;
use WP_REST_Request;

/**
 * Selects the correct controller based on the request.
 */
class ItemsFactory
{
    public function __construct(string $method, Plugin $plugin)
    {
        $this->method = $method;
        $this->plugin = $plugin;
    }

    /**
     * Executes the correct controller based on the request.
     */
    public function retrieve(WP_REST_Request $request): array
    {
        $controller = $this->getController($request);

        return $controller->{$this->method}($request);
    }

    /**
     * Gets the desired controller depeneding on auth status
     */
    private function getController(WP_REST_Request $request): ItemController
    {
        if (AuthValidator::validate($request)) {
            return new InternalItemsController($this->plugin);
        }

        return new BaseItemsController($this->plugin);
    }
}

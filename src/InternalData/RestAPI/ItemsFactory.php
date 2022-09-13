<?php

declare(strict_types=1);

namespace OWC\OpenPub\InternalData\RestAPI;

use OWC\OpenPub\InternalData\Foundation\Plugin;
use OWC\OpenPub\InternalData\Interfaces\ItemController;

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
     * Executes the correct controller with provided method
     */
    public function retrieve(\WP_REST_Request $request): array
    {
        $controller = $this->getController($request);

        return $controller->{$this->method}($request);
    }

    /**
     * Gets the desired controller depending on auth status
     */
    private function getController(\WP_REST_Request $request): ItemController
    {
        if (\is_user_logged_in()) {
            return new InternalItemsController($this->plugin);
        }

        return new BaseItemsController($this->plugin);
    }
}

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
    protected string $method;
    protected Plugin $plugin;
    
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
        $response = $controller->{$this->method}($request);

        if (! is_array($response)) {
            return ['errors' => $response->errors['no_item_found'][0] ?? 'Something went wrong with fetching the result'];
        }
        
        return $response;
    }

    /**
     * Gets the desired controller depending on auth status
     */
    private function getController(\WP_REST_Request $request): ItemController
    {
        // Application passwords are not available on accept, so check here.
        if ('accept' === ($_ENV['APP_ENV'] ?? '')) {
            return new InternalItemsController($this->plugin);
        }

        if (\is_user_logged_in()) {
            return new InternalItemsController($this->plugin);
        }

        return new BaseItemsController($this->plugin);
    }
}

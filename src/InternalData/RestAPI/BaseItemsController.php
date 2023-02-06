<?php

declare(strict_types=1);

namespace OWC\OpenPub\InternalData\RestAPI;

use OWC\OpenPub\Base\RestAPI\Controllers\ItemController as BaseItemController;
use OWC\OpenPub\InternalData\Foundation\Plugin;
use OWC\OpenPub\InternalData\Interfaces\ItemController;
use WP_REST_Request;

/*
* Facade that controls the retrieval of the internal OpenPub items from the base plugin.
* Using the interface ensures that we have the correct methods available.
*/
class BaseItemsController implements ItemController
{
    protected BaseItemController $baseController;
    
    public function __construct(Plugin $plugin)
    {
        $this->baseController = new BaseItemController($plugin);
    }

    public function getItems(WP_REST_Request $request): array
    {
        return $this->baseController->getItems($request);
    }

    public function getItem(WP_REST_Request $request)
    {
        return $this->baseController->getItem($request);
    }

    public function getItemBySlug(WP_REST_Request $request): array
    {
        return $this->baseController->getItemBySlug($request);
    }

    public function getActiveItems(WP_REST_Request $request)
    {
        return $this->baseController->getActiveItems($request);
    }
}

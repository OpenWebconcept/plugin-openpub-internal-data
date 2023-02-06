<?php

declare(strict_types=1);

/**
 * Get items, including the 'internal' fields
 */

namespace OWC\OpenPub\InternalData\RestAPI;

use OWC\OpenPub\Base\Foundation\Plugin;
use OWC\OpenPub\Base\Repositories\Item;
use OWC\OpenPub\Base\RestAPI\Controllers\BaseController;
use OWC\OpenPub\InternalData\Data\DataServiceProvider;
use OWC\OpenPub\InternalData\Interfaces\ItemController;
use WP_REST_Request;

/**
 * Controls the retrieval of the internal OpenPub items.
 */
class InternalItemsController extends BaseController implements ItemController
{
    protected Plugin $plugin;

    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    public function getItems(WP_REST_Request $request): array
    {
        $this->addFields();
        $parameters = $this->convertParameters($request->get_params());

        $items      = (new Item())
            ->query($this->getPaginatorParams($request));

        if (false === $parameters['include-connected']) {
            $items->hide(['connected']);
        }

        $posts = $items->all();

        return $this->addPaginator($posts, $items->getQuery());
    }

    /**
     * @return array|WP_Error
     */
    public function getItem(WP_REST_Request $request)
    {
        $this->addFields();

        $id = (int) $request->get_param('id');

        $item = (new Item)
            ->query(apply_filters('owc/openpub/rest-api/items/query/single', []))
            ->find($id);

        if (! $item) {
            return new \WP_Error('no_item_found', sprintf("Item with ID '%d' not found", $id), [
                'status' => 404,
            ]);
        }

        return $item;
    }

    /**
     * Get a list of all active items.
     */
    public function getActiveItems(WP_REST_Request $request): array
    {
        $this->addFields();
        $parameters = $this->convertParameters($request->get_params());

        $items = (new Item())
            ->query(
                array_merge(
                    $this->getPaginatorParams($request),
                    Item::addExpirationParameters()
                )
            );

        if (false === $parameters['include-connected']) {
            $items->hide(['connected']);
        }

        $posts = $items->all();

        return $this->addPaginator($posts, $items->getQuery());
    }

    /**
     * @return array|WP_Error
     */
    public function getItemBySlug(WP_REST_Request $request)
    {
        $this->addFields();

        $slug = $request->get_param('slug');

        $item = (new Item)
            ->query(apply_filters('owc/openpub/rest-api/items/query/single', []))
            ->findBySlug($slug);

        if (! $item) {
            return new \WP_Error('no_item_found', sprintf("Item with SLUG '%s' not found", $slug), [
                'status' => 404,
            ]);
        }

        return $item;
    }

    /**
     * Register the DataServiceProvider.
     */
    protected function addFields(): void
    {
        (new DataServiceProvider($this->plugin))->register();
    }

    /**
     * Convert the parameters to the allowed ones.
     */
    protected function convertParameters(array $parametersFromRequest): array
    {
        $parameters = [];

        if (isset($parametersFromRequest['name'])) {
            $parameters['name'] = esc_attr($parametersFromRequest['name']);
        }

        $parameters['include-connected'] = (isset($parametersFromRequest['include-connected'])) ? true : false;

        if (isset($parametersFromRequest['slug'])) {
            $parameters['name'] = esc_attr($parametersFromRequest['slug']);
            unset($parametersFromRequest['slug']);
        }

        if (isset($parametersFromRequest['id'])) {
            $parameters['p'] = absint($parametersFromRequest['id']);
            unset($parametersFromRequest['slug']);
        }

        if (isset($parametersFromRequest['exclude'])) {
            $parameters['exclude'] = esc_attr($parametersFromRequest['exclude']);
            unset($parametersFromRequest['exclude']);
        }

        return $parameters;
    }
}

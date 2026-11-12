<?php

declare(strict_types=1);

namespace OWC\OpenPub\InternalData\RestAPI;

use OWC\OpenPub\Base\Foundation\Plugin;
use OWC\OpenPub\Base\Repositories\Item;
use OWC\OpenPub\Base\RestAPI\Controllers\ItemController as BaseController;
use OWC\OpenPub\InternalData\Data\DataServiceProvider;
use OWC\OpenPub\InternalData\Interfaces\ItemController;
use WP_REST_Request;

class InternalItemsController extends BaseController implements ItemController
{
    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    public function getItems(WP_REST_Request $request): array
    {
        $this->addFields();

        $items = (new Item())
            ->query($this->getPaginatorParams($request));

        if (null === $request->get_param('include-connected')) {
            $items->hide(['connected']);
        }

        if ($this->getTypeParam($request)) {
            $items->query(Item::addTypeParameter($this->getTypeParam($request)));
        }

        if ($request->get_param('source') && $this->plugin->settings->useShowOn()) {
            $items->query(Item::addShowOnParameter((string) $request->get_param('source')));
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

        $item = $this->singleItemQueryBuilder($request)->find($id);

        if (! $item) {
            return new \WP_Error('no_item_found', sprintf("Item with ID '%d' not found", $id), [
                'status' => 404,
            ]);
        }

        $item['related'] = $this->addRelated($item, $request);

        return $item;
    }

    /**
     * Get a list of all active items.
     */
    public function getActiveItems(WP_REST_Request $request): array
    {
        $this->addFields();

        $items = (new Item())
            ->query(
                array_merge(
                    $this->getPaginatorParams($request),
                    Item::addExpirationParameters()
                )
            );

        if (null === $request->get_param('include-connected')) {
            $items->hide(['connected']);
        }

        if ($this->getTypeParam($request)) {
            $items->query(Item::addTypeParameter($this->getTypeParam($request)));
        }

        if ($request->get_param('source') && $this->plugin->settings->useShowOn()) {
            $items->query(Item::addShowOnParameter((string) $request->get_param('source')));
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

        $item = $this->singleItemQueryBuilder($request)->findBySlug($slug);

        if (! $item) {
            return new \WP_Error('no_item_found', sprintf("Item with SLUG '%s' not found", $slug), [
                'status' => 404,
            ]);
        }

        $item['related'] = $this->addRelated($item, $request);

        return $item;
    }

    /**
     * Register the DataServiceProvider.
     */
    protected function addFields(): void
    {
        (new DataServiceProvider($this->plugin))->register();
    }

    public function collectionArguments(): array
    {
        return array_merge(parent::collectionArguments(), [
            // Untyped: a bare ?include-connected enables it, which a boolean type would reject.
            'include-connected' => [
                'description' => __('Include connected items. Logged-in requests only.', 'openpub-internal-data'),
            ],
        ]);
    }
}

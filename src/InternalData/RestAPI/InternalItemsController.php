<?php

declare(strict_types=1);

namespace OWC\OpenPub\InternalData\RestAPI;

use OWC\OpenPub\Base\Foundation\Plugin;
use OWC\OpenPub\Base\Repositories\Item;
use OWC\OpenPub\Base\RestAPI\Controllers\BaseController;
use OWC\OpenPub\Base\RestAPI\ItemFields\FeaturedImageField;
use OWC\OpenPub\InternalData\Data\DataServiceProvider;
use OWC\OpenPub\InternalData\Interfaces\ItemController;
use WP_Post;
use WP_Query;
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
        $parameters = $this->convertParameters($request->get_params());

        $items = (new Item())
            ->query($this->getPaginatorParams($request));

        if (false === $parameters['include-connected']) {
            $items->hide(['connected']);
        }

        if ($this->getTypeParam($request)) {
            $items->query(Item::addTypeParameter($this->getTypeParam($request)));
        }

        if ($this->showOnParamIsValid($request) && $this->plugin->settings->useShowOn()) {
            $items->query(Item::addShowOnParameter($request->get_param('source')));
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

        if ($this->getTypeParam($request)) {
            $items->query(Item::addTypeParameter($this->getTypeParam($request)));
        }

        if ($this->showOnParamIsValid($request) && $this->plugin->settings->useShowOn()) {
            $items->query(Item::addShowOnParameter($request->get_param('source')));
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

    public function singleItemQueryBuilder(WP_REST_Request $request): Item
    {
        $item = (new Item)
            ->query(apply_filters('owc/openpub/rest-api/items/query/single', []));

        $preview = filter_var($request->get_param('draft-preview'), FILTER_VALIDATE_BOOLEAN);

        if (true === $preview) {
            $item->query(['post_status' => ['publish', 'draft']]);
        }

        return $item;
    }

    /**
     * Get related items
     */
    protected function addRelated(array $item, WP_REST_Request $request): array
    {
        $items = (new Item())
            ->query([
                'post__not_in' => [$item['id']],
                'posts_per_page' => 10,
                'post_status' => 'publish',
                'post_type' => 'openpub-item',
            ])
            ->query(Item::addExpirationParameters());

        if ($this->showOnParamIsValid($request) && $this->plugin->settings->useShowOn()) {
            $items->query(Item::addShowOnParameter($request->get_param('source')));
        }

        $query = new WP_Query($items->getQueryArgs());

        return array_map([$this, 'transform'], $query->posts);
    }

    /**
     * Transform a single WP_Post item into an array
     */
    public function transform(WP_Post $post): array
    {
        $data = [
            'id' => $post->ID,
            'title' => $post->post_title,
            'content' => \apply_filters('the_content', $post->post_content),
            'excerpt' => $post->post_excerpt,
            'date' => $post->post_date,
            'thumbnail_url' => \get_the_post_thumbnail_url($post->ID),
            'image' => $this->getImageUrl($post),
            'slug' => $post->post_name,
        ];

        return $data;
    }

    public function getImageUrl(WP_Post $post): array
    {
        return (new FeaturedImageField($this->plugin))->create($post);
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

    protected function getTypeParam(WP_REST_Request $request): string
    {
        $typeParam = $request->get_param('type');

        return ! empty($typeParam) && is_string($typeParam) ? $typeParam : '';
    }

    /**
     * Validate if show on param is valid.
     * Param should be a numeric value.
     *
     * @param WP_REST_Request $request
     *
     * @return bool
     */
    protected function showOnParamIsValid(WP_REST_Request $request): bool
    {
        if (empty($request->get_param('source'))) {
            return false;
        }

        if (! is_numeric($request->get_param('source'))) {
            return false;
        }

        return true;
    }
}

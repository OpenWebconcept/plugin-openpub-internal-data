<?php

declare(strict_types=1);

namespace OWC\OpenPub\InternalProducts\Interfaces;

use WP_REST_Request;

/**
 * Interface for controllers that handle the retrieval of items.
 */
interface ItemController
{
    public function getItems(WP_REST_Request $request): array;
    public function getItem(WP_REST_Request $request);
    public function getActiveItems(WP_REST_Request $request);
    public function getItemBySlug(WP_REST_Request $request);
}

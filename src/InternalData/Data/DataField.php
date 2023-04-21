<?php

declare(strict_types=1);
/**
 * Responsible for the filtering the internal keyword.
 */

namespace OWC\OpenPub\InternalData\Data;

use OWC\OpenPub\Base\Support\CreatesFields;
use WP_Post;

/**
 * Filters the internal keyword, and returns array.
 */
class DataField extends CreatesFields
{
    /**
     * Create the internaldata field for a given post.
     */
    public function create(WP_Post $post): array
    {
        return array_map(function ($item) {
            return [
                'title' => $item['internaldata_key'],
                'content' => apply_filters('the_content', $item['internaldata_value']),
            ];
        }, $this->getData($post));
    }

    /**
     * Filters the post if internal data of a given post exists.
     */
    private function getData(WP_Post $post): array
    {
        return array_filter(get_post_meta($post->ID, '_owc_openpub_internaldata', true) ?: [], function ($item) {
            return ! empty($item['internaldata_key']) && ! empty($item['internaldata_value']);
        });
    }
}

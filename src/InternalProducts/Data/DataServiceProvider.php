<?php

/**
 * Boots the DataServiceProvider.
 */

namespace OWC\OpenPub\InternalProducts\Data;

use OWC\OpenPub\Base\Foundation\ServiceProvider;
use OWC\OpenPub\Base\Repositories\Item;

/**
 * Boots the DataServiceProvider.
 */
class DataServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider, only when in admin or if accessed via the /internal endpoint.
     */
    public function register()
    {
        // Add the internal data to all openpub items, when in admin or if accessed via the /internal endpoint.
        Item::addGlobalField('internal-data', new DataField($this->plugin), function () {
            return true;
        });

        $this->plugin->loader->addAction('owc/openpub-base/plugin', new Metaboxes($this->plugin), 'register', 10, 1);
    }
}

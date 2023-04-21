<?php

declare(strict_types=1);

namespace OWC\OpenPub\InternalData\Data;

use OWC\OpenPub\Base\Foundation\Plugin;

/**
 * Registers the metabox field.
 *
 * This is achieved based on the config key "metaboxes.internaldata".
 */
class Metaboxes
{
    private Plugin $plugin;

    /**
     * Dependency injection of the plugin, for future use.
     */
    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Register metaboxes for internal data into openpub-base plugin.
     */
    public function register(Plugin $basePlugin): void
    {
        $basePlugin->config->set('cmb2_metaboxes.internaldata', $this->plugin->config->get('metaboxes.internaldata'));
    }
}

<?php

declare(strict_types=1);
/**
 * Registers the metabox field.
 */

namespace OWC\OpenPub\InternalProducts\Data;

use OWC\OpenPub\Base\Foundation\Plugin;

/**
 * Registers the metabox field.
 *
 * This is achieved based on the config key "metaboxes.internaldata".
 */
class Metaboxes
{

    /**
     * Instance of the Plugin.
     *
     * @var Plugin
     */
    private $plugin;

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
    public function register(Plugin $basePlugin)
    {
        $basePlugin->config->set('metaboxes.internaldata', $this->plugin->config->get('metaboxes.internaldata'));
    }
}

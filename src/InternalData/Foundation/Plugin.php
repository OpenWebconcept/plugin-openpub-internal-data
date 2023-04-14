<?php

declare(strict_types=1);

/**
 * The base of the plugin.
 */

namespace OWC\OpenPub\InternalData\Foundation;

use OWC\OpenPub\Base\Foundation\Plugin as BasePlugin;

/**
 * Sets the name and version of the plugin.
 */
class Plugin extends BasePlugin
{
    /**
     * Name of the plugin.
     *
     * @const string NAME
     */
    const NAME = 'openpub-internal-data';

    /**
     * Version of the plugin.
     * Used for setting versions of enqueue scripts and styles.
     *
     * @const string VERSION
     */
    const VERSION = '1.0.9';
}

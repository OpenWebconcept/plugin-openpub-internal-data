<?php

declare(strict_types=1);

return [
    /**
     * Service Providers.
     */
    'providers' => [
        /**
         * Global providers.
         */
        OWC\OpenPub\InternalProducts\RestAPI\RestAPIServiceProvider::class,

        /**
         * Providers specific to the admin.
         */
        'admin' => [
            OWC\OpenPub\InternalProducts\Data\DataServiceProvider::class,
        ],
    ],

    /**
     * Dependencies upon which the plugin relies.
     *
     * Required: type, label
     * Optional: message
     *
     * Type: plugin
     * - Required: file
     * - Optional: version
     *
     * Type: class
     * - Required: name
     */
    'dependencies' => [
        [
            'type'    => 'plugin',
            'label'   => 'OpenPub Base',
            'version' => '2.0.0',
            'file'    => 'openpub-base/openpub-base.php',
        ],
    ],
];

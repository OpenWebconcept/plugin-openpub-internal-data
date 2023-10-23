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
        OWC\OpenPub\InternalData\RestAPI\RestAPIServiceProvider::class,

        /**
         * Providers specific to the admin.
         */
        'admin' => [
            OWC\OpenPub\InternalData\Data\DataServiceProvider::class,
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
            'version' => '3.5',
            'file'    => 'openpub-base/openpub-base.php',
        ],
        [
            'type'    => 'plugin',
            'label'   => 'CMB2',
            'version' => '2.10.1',
            'file'    => 'cmb2/init.php',
        ],
    ],
];

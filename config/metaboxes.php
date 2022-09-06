<?php

return [
    'internaldata' => [
        'id'         => 'openpub_internaldata',
        'title'      => __('Internal Data', 'openpub-internal-products'),
        'post_types' => ['openpub-item'],
        'context'    => 'normal',
        'priority'   => 'high',
        'autosave'   => true,
        'include'    => [
            'openpub-type' => 'internal',
        ],
        'fields'     => [
            'internaldata' => [
                'group' => [
                    'id'         => 'openpub_internaldata',
                    'type'       => 'group',
                    'clone'      => true,
                    'sort_clone' => true,
                    'add_button' => __('Add internal data', 'openpub-internal-products'),
                    'fields'     => [
                        [
                            'id'   => 'internaldata_key',
                            'name' => __('Title', 'openpub-internal-products'),
                            'type' => 'text',
                        ],
                        [
                            'id'      => 'internaldata_value',
                            'name'    => __('Content', 'openpub-internal-products'),
                            'type'    => 'wysiwyg',
                            'desc'    => __('Use of HTML is allowed', 'openpub-internal-products'),
                            'options' => [
                                'textarea_rows' => 4,
                                'teeny'         => false,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];

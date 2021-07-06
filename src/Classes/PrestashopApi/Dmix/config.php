<?php

return [
    'resources' => [
        'categories' => [
            'contains' => [
                'products' => [
                    'child_key' => 'default_category_id',
                    'parent_key' => 'products_ids',
                    'field_name' => 'products'
                ]
            ]
        ]
    ]
];

<?php

return [
    'store_id' => 'agromarsbd',
    'signature_key' => '55a8b737e882cab6b1b2a99f2cf9d116',
    'sandbox' => false,
    'redirect_url' => [
        'success' => [
            'route' => 'payment.success'
        ],
        'cancel' => [
            'route' => 'payment.cancel'
        ]
    ]
];

